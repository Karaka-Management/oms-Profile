<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Profile
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\Profile\Controller;

use Modules\Admin\Models\LocalizationMapper;
use Modules\Media\Models\MediaMapper;
use Modules\Media\Models\NullMedia;
use Modules\Profile\Models\ProfileMapper;
use Modules\Profile\Models\SettingsEnum;
use phpOMS\Asset\AssetType;
use phpOMS\Contract\RenderableInterface;
use phpOMS\Localization\NullLocalization;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;

/**
 * Profile class.
 *
 * @package Modules\Profile
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 * @codeCoverageIgnore
 */
final class BackendController extends Controller
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
     * @since 1.0.0
     */
    public function setupProfileStyles(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        /** @var \phpOMS\Model\Html\Head $head */
        $head = $response->get('Content')->getData('head');
        $head->addAsset(AssetType::CSS, 'Modules/Profile/Theme/Backend/css/styles.css');
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     */
    public function viewProfileList(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $view->setTemplate('/Modules/Profile/Theme/Backend/profile-list');

        if ($request->getData('ptype') === 'p') {
            $view->setData('accounts',
                ProfileMapper::getAll()
                    ->with('account')
                    ->with('image')
                    ->where('id', (int) ($request->getData('id') ?? 0), '<')
                    ->limit(25)->execute()
                );
        } elseif ($request->getData('ptype') === 'n') {
            $view->setData('accounts',
                ProfileMapper::getAll()
                    ->with('account')
                    ->with('image')
                    ->where('id', (int) ($request->getData('id') ?? 0), '>')
                    ->limit(25)->execute()
                );
        } else {
            $view->setData('accounts',
                ProfileMapper::getAll()
                    ->with('account')
                    ->with('image')
                    ->where('id', 0, '>')
                    ->limit(25)->execute()
            );
        }

        $profileImage = $this->app->appSettings->get(names: SettingsEnum::DEFAULT_PROFILE_IMAGE, module: 'Profile');

        /** @var \Modules\Media\Modles\Media $image */
        $image = MediaMapper::get()->where('id', (int) $profileImage->content)->execute();

        $view->setData('defaultImage', $image);

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     */
    public function viewProfileSingle(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        /** @var \phpOMS\Model\Html\Head $head */
        $head = $response->get('Content')->getData('head');
        $head->addAsset(AssetType::CSS, '/Modules/Calendar/Theme/Backend/css/styles.css');

        $view->setTemplate('/Modules/Profile/Theme/Backend/profile-single');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1000301001, $request, $response));

        $mediaListView = new \Modules\Media\Theme\Backend\Components\Media\ListView($this->app->l11nManager, $request, $response);
        $mediaListView->setTemplate('/Modules/Media/Theme/Backend/Components/Media/list');
        $view->addData('medialist', $mediaListView);

        $calendarView = new \Modules\Calendar\Theme\Backend\Components\Calendar\BaseView($this->app->l11nManager, $request, $response);
        $calendarView->setTemplate('/Modules/Calendar/Theme/Backend/Components/Calendar/mini');
        $view->addData('calendar', $calendarView);

        $mapperQuery = ProfileMapper::get()
            ->with('account')
            ->with('image')
            ->with('location')
            ->with('contactElements');

        /** @var \Modules\Profile\Modles\Profile $profile */
        $profile = $request->getData('for') !== null
            ? $mapperQuery->where('account', (int) $request->getData('for'))->execute()
            : $mapperQuery->where('id', (int) $request->getData('id'))->execute();

        $view->setData('account', $profile);

        $l11n = null;
        if ($profile->account->getId() === $request->header->account) {
            /** @var \phpOMS\Localization\Localization $l11n */
            $l11n = LocalizationMapper::get()->where('id', $profile->account->l11n->getId())->execute();
        }

        $view->setData('l11n', $l11n ?? new NullLocalization());

        $accGrpSelector = new \Modules\Profile\Theme\Backend\Components\AccountGroupSelector\BaseView($this->app->l11nManager, $request, $response);
        $view->addData('accGrpSelector', $accGrpSelector);

        /** @var \Modules\Media\Modles\Media[] $media */
        $media = MediaMapper::getAll()
            ->with('createdBy')
            ->where('createdBy', (int) $profile->account->getId())
            ->limit(25)
            ->execute();

        $view->setData('media', $media instanceof NullMedia ? [] : (!\is_array($media) ? [$media] : $media));

        $profileImage = $this->app->appSettings->get(names: SettingsEnum::DEFAULT_PROFILE_IMAGE, module: 'Profile');
        $image        = MediaMapper::get()->where('id', (int) $profileImage->content)->execute();

        $view->setData('defaultImage', $image);

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     */
    public function viewProfileAdminSettings(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Profile/Theme/Backend/modules-settings');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1000300000, $request, $response));

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     */
    public function viewProfileAdminCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Profile/Theme/Backend/modules-create');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1000300000, $request, $response));

        $accGrpSelector = new \Modules\Profile\Theme\Backend\Components\AccountGroupSelector\BaseView($this->app->l11nManager, $request, $response);
        $view->addData('accGrpSelector', $accGrpSelector);

        return $view;
    }
}
