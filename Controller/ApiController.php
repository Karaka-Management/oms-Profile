<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Profile
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Profile\Controller;

use Modules\Admin\Models\AccountMapper;
use Modules\Admin\Models\Address;
use Modules\Admin\Models\AddressMapper;
use Modules\Media\Models\NullMedia;
use Modules\Media\Models\MediaMapper;
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
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
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
    public function apiProfileCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
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

        $this->createModel($request->header->account, $profile, ProfileMapper::class, 'profile', $request->getOrigin());

        return true;
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
    public function apiProfileTempLoginCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        /** @var \Modules\Admin\Models\Account $account */
        $account               = AccountMapper::get()->where('id', $request->header->account)->execute();
        $account->tempPassword = \password_hash(\random_bytes(64), \PASSWORD_BCRYPT);

        $this->updateModel($request->header->account, $account, $account, AccountMapper::class, 'profile', $request->getOrigin());

        $this->fillJsonResponse(
            $request, $response,
            NotificationLevel::OK,
            'Profile',
            'Temp password successfully created.',
            $account->tempPassword
        );
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
        /** @var \Modules\Profile\Models\Profile[] $profiles */
        $profiles = [];
        $accounts = $request->getDataList('iaccount-idlist');

        foreach ($accounts as $account) {
            $account = (int) \trim($account);

            /** @var Profile $isInDb */
            $isInDb = ProfileMapper::get()->where('account', $account)->execute();

            if ($isInDb->getId() !== 0) {
                $profiles[] = $isInDb;
                continue;
            }

            /** @var \Modules\Admin\Models\Account $dbAccount */
            $dbAccount  = AccountMapper::get()->where('id', $account)->execute();
            $profiles[] = new Profile($dbAccount);
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
    public function apiSettingsAccountImageSet(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        $uploadedFiles = $request->getFiles();

        if (empty($uploadedFiles)) {
            $this->fillJsonResponse($request, $response, NotificationLevel::ERROR, 'Profile', 'Invalid profile image', $uploadedFiles);
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        /** @var Profile $profile */
        $profile = ProfileMapper::get()->with('account')->where('account', $request->header->account)->execute();
        $old     = clone $profile;

        $uploaded = $this->app->moduleManager->get('Media')->uploadFiles(
            names: $request->getDataList('names'),
            fileNames: $request->getDataList('filenames'),
            files: $uploadedFiles,
            account: $request->header->account,
            basePath: __DIR__ . '/../../../Modules/Media/Files/Accounts/' . $profile->account->getId(),
            virtualPath: '/Accounts/' . $profile->account->getId() . ' ' . $profile->account->login,
            pathSettings: PathSettings::FILE_PATH
        );

        if ($request->hasData('type')) {
            foreach ($uploaded as $file) {
                $this->createModelRelation(
                    $request->header->account,
                    $file->getId(),
                    $request->getDataInt('type'),
                    MediaMapper::class,
                    'types',
                    '',
                    $request->getOrigin()
                );
            }
        }

        $profile->image = !empty($uploaded) ? \reset($uploaded) : new NullMedia();
        if (!($profile->image instanceof NullMedia)) {
            $profile->image = $this->app->moduleManager->get('Media')->resizeImage($profile->image, 100, 100, false);
        }

        $this->updateModel($request->header->account, $old, $profile, ProfileMapper::class, 'profile', $request->getOrigin());
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
    public function apiContactElementCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateContactElementCreate($request))) {
            $response->set('contact_element_create', new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $profile = 0;
        if ($request->hasData('profile')) {
            $profile = $request->getDataInt('profile') ?? 0;
        } else {
            /** @var \Modules\Profile\Models\Profile $profileObj */
            $profileObj = ProfileMapper::get()
                ->where('account', $request->getDataInt('account') ?? 0)
                ->execute();

            $profile = $profileObj->getId();
        }

        $contactElement = $this->createContactElementFromRequest($request);

        $this->createModel($request->header->account, $contactElement, ContactElementMapper::class, 'profile-contactElement', $request->getOrigin());
        $this->createModelRelation($request->header->account, $profile, $contactElement->getId(), ProfileMapper::class, 'contactElements', '', $request->getOrigin());
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
    public function validateContactElementCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['account'] = (empty($request->getData('account')) && empty($request->getData('profile'))))
            || ($val['type'] = !\is_numeric($request->getData('type')))
            || ($val['content'] = empty($request->getData('content')))
            || ($val['contact'] = empty($request->getData('contact')))
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
        $element->setType($request->getDataInt('type') ?? 0);
        $element->setSubtype($request->getDataInt('subtype') ?? 0);
        $element->content = $request->getDataString('content') ?? '';
        $element->contact = $request->getDataInt('contact') ?? 0;

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
    public function apiAddressCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateAddressCreate($request))) {
            $response->set('address_create', new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $profile = 0;
        if ($request->hasData('profile')) {
            $profile = $request->getDataInt('profile') ?? 0;
        } else {
            /** @var \Modules\Profile\Models\Profile $profileObj */
            $profileObj = ProfileMapper::get()
                ->where('account', $request->getDataInt('account') ?? 0)
                ->execute();

            $profile = $profileObj->getId();
        }

        $address = $this->createAddressFromRequest($request);

        $this->createModel($request->header->account, $address, AddressMapper::class, 'profile-address', $request->getOrigin());
        $this->createModelRelation($request->header->account, $profile, $address->getId(), ProfileMapper::class, 'location', '', $request->getOrigin());
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
        $element           = new Address();
        $element->name     = $request->getDataString('name') ?? '';
        $element->addition = $request->getDataString('addition') ?? '';
        $element->postal   = $request->getDataString('postal') ?? '';
        $element->city     = $request->getDataString('city') ?? '';
        $element->address  = $request->getDataString('address') ?? '';
        $element->state    = $request->getDataString('state') ?? '';
        $element->setCountry($request->getDataString('country') ?? '');
        $element->setType($request->getDataInt('type') ?? 0);

        return $element;
    }
}
