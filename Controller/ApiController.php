<?php
/**
 * Jingga
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
use Modules\Admin\Models\ContactType;
use Modules\Media\Models\MediaMapper;
use Modules\Media\Models\NullMedia;
use Modules\Media\Models\PathSettings;
use Modules\Profile\Models\ContactElement;
use Modules\Profile\Models\ContactElementMapper;
use Modules\Profile\Models\Profile;
use Modules\Profile\Models\ProfileMapper;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\NotificationLevel;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;

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
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiProfileCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        $profiles = $this->createProfilesFromRequest($request);
        $created  = [];

        foreach ($profiles as $profile) {
            if ($profile->id === 0) {
                $this->createModel($request->header->account, $profile, ProfileMapper::class, 'profile', $request->getOrigin());
            }

            $created[] = $profile;
        }

        $this->createStandardCreateResponse($request, $response, $created);
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiProfileTempLoginCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
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
            $account = (int) $account;

            /** @var Profile $isInDb */
            $isInDb = ProfileMapper::get()->where('account', $account)->execute();

            if ($isInDb->id !== 0) {
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
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiSettingsAccountImageSet(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        $uploadedFiles = $request->files;

        if (empty($uploadedFiles)) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidUpdateResponse($request, $response, $uploadedFiles);

            return;
        }

        /** @var Profile $profile */
        $profile = ProfileMapper::get()
            ->with('account')
            ->where('account', $request->header->account)
            ->execute();

        $old = clone $profile;

        $uploaded = $this->app->moduleManager->get('Media', 'Api')->uploadFiles(
            names: $request->getDataList('names'),
            fileNames: $request->getDataList('filenames'),
            files: $uploadedFiles,
            account: $request->header->account,
            basePath: __DIR__ . '/../../../Modules/Media/Files/Accounts/' . $profile->account->id,
            virtualPath: '/Accounts/' . $profile->account->id . ' ' . $profile->account->login,
            pathSettings: PathSettings::FILE_PATH
        );

        if ($request->hasData('type')) {
            foreach ($uploaded as $file) {
                $this->createModelRelation(
                    $request->header->account,
                    $file->id,
                    $request->getDataInt('type'),
                    MediaMapper::class,
                    'types',
                    '',
                    $request->getOrigin()
                );
            }
        }

        $profile->image = empty($uploaded) ? new NullMedia() : \reset($uploaded);
        if ($profile->image->id > 0) {
            $profile->image = $this->app->moduleManager->get('Media')->resizeImage($profile->image, 100, 100, false);
        }

        $this->updateModel($request->header->account, $old, $profile, ProfileMapper::class, 'profile', $request->getOrigin());
        $this->createStandardUpdateResponse($request, $response, $profile);
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiContactElementCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateContactElementCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

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

            $profile = $profileObj->id;
        }

        $contactElement = $this->createContactElementFromRequest($request);

        $this->createModel($request->header->account, $contactElement, ContactElementMapper::class, 'profile-contactElement', $request->getOrigin());
        $this->createModelRelation($request->header->account, $profile, $contactElement->id, ProfileMapper::class, 'contactElements', '', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $contactElement);
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
        if (($val['account'] = (!$request->hasData('account') && !$request->hasData('profile')))
            || ($val['type'] = !\is_numeric($request->getData('type')))
            || ($val['content'] = !$request->hasData('content'))
            || ($val['contact'] = !$request->hasData('contact'))
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
        $element          = new ContactElement();
        $element->type    = ContactType::tryFromValue($request->getDataInt('type')) ?? ContactType::EMAIL;
        $element->subtype = $request->getDataInt('subtype') ?? 0;
        $element->content = $request->getDataString('content') ?? '';
        $element->contact = $request->getDataInt('contact') ?? 0;

        return $element;
    }
}
