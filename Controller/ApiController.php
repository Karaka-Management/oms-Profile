<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   Modules\Profile
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\Profile\Controller;

use Modules\Admin\Models\AccountMapper;
use Modules\Admin\Models\Address;
use Modules\Admin\Models\AddressMapper;
use Modules\Media\Models\PathSettings;
use Modules\Profile\Models\ContactElement;
use Modules\Profile\Models\ContactElementMapper;
use Modules\Profile\Models\Profile;
use Modules\Profile\Models\ProfileMapper;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\NotificationLevel;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Model\Message\FormValidation;

/**
 * Profile class.
 *
 * @package Modules\Profile
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 *
 * @todo Orange-Management/Modules#138
 *  Allow Admin to login as user
 *  Admins should be allowed to log in as users.
 *  This doesn't mean that the admins can create content in the name of a user but they get to see all the things this user can see.
 *  They basically log in with the same permissions.
 */
final class ApiController extends Controller
{
    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiProfileCreate(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        $profiles = $this->createProfilesFromRequest($request);
        $created  = [];
        $status   = true;

        foreach ($profiles as $profile) {
            $status = $status && $this->apiProfileCreateDbEntry($profile, $request);

            $created[] = $profile;
        }

        $this->fillJsonResponse(
            $request, $response,
            $status ? NotificationLevel::OK : NotificationLevel::WARNING,
            'Profil',
            $status ? 'Profil successfully created.' : 'Profile already existing.',
            $created
        );
    }

    /**
     * @param Profile         $profile Profile to create in the database
     * @param RequestAbstract $request Request
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function apiProfileCreateDbEntry(Profile $profile, RequestAbstract $request) : bool
    {
        if ($profile->getId() !== 0) {
            return false;
        }

        $this->createModel($request->getHeader()->getAccount(), $profile, ProfileMapper::class, 'profile', $request->getOrigin());

        return true;
    }

    /**
     * Method to create profile from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return array<Profile>
     *
     * @since 1.0.0
     */
    private function createProfilesFromRequest(RequestAbstract $request) : array
    {
        $profiles = [];
        $accounts = $request->getDataList('iaccount-idlist');

        foreach ($accounts as $account) {
            $account = (int) \trim($account);

            /** @var Profile $isInDb */
            $isInDb = ProfileMapper::getFor($account, 'account');

            if ($isInDb->getId() !== 0) {
                $profiles[] = $isInDb;
                continue;
            }

            $profiles[] = new Profile(AccountMapper::get($account));
        }

        return $profiles;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiSettingsAccountImageSet(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        $uploadedFiles = $request->getFiles() ?? [];

        if (empty($uploadedFiles)) {
            $this->fillJsonResponse($request, $response, NotificationLevel::ERROR, 'Profile', 'Invalid profile image', $uploadedFiles);
            $response->getHeader()->setStatusCode(RequestStatusCode::R_400);

            return;
        }

        /** @var Profile $profile */
        $profile = ProfileMapper::getFor($request->getHeader()->getAccount(), 'account');
        $old     = clone $profile;

        $uploaded = $this->app->moduleManager->get('Media')->uploadFiles(
            $request->getData('name') ?? '',
            $uploadedFiles,
            $request->getHeader()->getAccount(),
            'Modules/Media/Files/Accounts/' . $profile->getAccount()->getId() . ' ' . $profile->getAccount()->getName(),
            '/Accounts/' . $profile->getAccount()->getId() . ' ' . $profile->getAccount()->getName(),
            '',
            '',
            PathSettings::FILE_PATH
        );

        $profile->setImage(\reset($uploaded));

        $this->updateModel($request->getHeader()->getAccount(), $old, $profile, ProfileMapper::class, 'profile', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Profile', 'Profile successfully updated', $profile);
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiContactElementCreate(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        if (!empty($val = $this->validateContactElementCreate($request))) {
            $response->set('contact_element_create', new FormValidation($val));
            $response->getHeader()->setStatusCode(RequestStatusCode::R_400);

            return;
        }

        /** @var Profile $profile */
        $profile = (int) ($request->getData('profile') ?? ProfileMapper::getFor($request->getData('account'), 'account')->getId());

        $contactElement = $this->createContactElementFromRequest($request);

        $this->createModel($request->getHeader()->getAccount(), $contactElement, ContactElementMapper::class, 'profile-contactElement', $request->getOrigin());
        $this->createModelRelation($request->getHeader()->getAccount(), $profile, $contactElement->getId(), ProfileMapper::class, 'contactElements', '', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Contact Element', 'Contact element successfully created', $contactElement);
    }

    /**
     * Validate contact element create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateContactElementCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['account'] = (empty($request->getData('account')) && empty($request->getData('profile'))))
            || ($val['type'] = !\is_numeric($request->getData('type')))
            || ($val['content'] = empty($request->getData('content')))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Method to create a contact element from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return ContactElement
     *
     * @since 1.0.0
     */
    public function createContactElementFromRequest(RequestAbstract $request) : ContactElement
    {
        /** @var ContactElement $element */
        $element = new ContactElement();
        $element->setType((int) ($request->getData('type') ?? 0));
        $element->setSubtype((int) ($request->getData('subtype') ?? 0));
        $element->setContent((string) ($request->getData('content') ?? ''));

        return $element;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiAddressCreate(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        if (!empty($val = $this->validateAddressCreate($request))) {
            $response->set('address_create', new FormValidation($val));
            $response->getHeader()->setStatusCode(RequestStatusCode::R_400);

            return;
        }

        /** @var Profile $profile */
        $profile = (int) ($request->getData('profile') ?? ProfileMapper::getFor($request->getData('account'), 'account')->getId());

        $address = $this->createAddressFromRequest($request);

        $this->createModel($request->getHeader()->getAccount(), $address, AddressMapper::class, 'profile-address', $request->getOrigin());
        $this->createModelRelation($request->getHeader()->getAccount(), $profile, $address->getId(), ProfileMapper::class, 'location', '', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Address', 'Address successfully created', $address);
    }

    /**
     * Validate contact element create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateAddressCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['account'] = (empty($request->getData('account')) && empty($request->getData('profile'))))
            || ($val['type'] = !\is_numeric($request->getData('type')))
            || ($val['country'] = empty($request->getData('country')))
            || ($val['city'] = empty($request->getData('city')))
            || ($val['address'] = empty($request->getData('address')))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Method to create a contact element from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return Address
     *
     * @since 1.0.0
     */
    public function createAddressFromRequest(RequestAbstract $request) : Address
    {
        /** @var Address $element */
        $element = new Address();
        $element->setName((string) ($request->getData('name') ?? ''));
        $element->setAddition((string) ($request->getData('addition') ?? ''));
        $element->setPostal((string) ($request->getData('postal') ?? ''));
        $element->setCity((string) ($request->getData('city') ?? ''));
        $element->setAddress((string) ($request->getData('address') ?? ''));
        $element->setCountry((string) ($request->getData('country') ?? ''));
        $element->setState((string) ($request->getData('state') ?? ''));
        $element->setType((int) ($request->getData('type') ?? 0));

        return $element;
    }
}
