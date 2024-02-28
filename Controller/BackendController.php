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

use Modules\Admin\Models\LocalizationMapper;
use Modules\Media\Models\MediaMapper;
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
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 * @codeCoverageIgnore
 */
final class BackendController extends Controller
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
     * @since 1.0.0
     */
    public function setupProfileStyles(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        /** @var \phpOMS\Model\Html\Head $head */
        $head = $response->data['Content']->head;
        $head->addAsset(AssetType::CSS, 'Modules/Profile/Theme/Backend/css/styles.css?v=' . self::VERSION);
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     */
    public function viewProfileList(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $view->setTemplate('/Modules/Profile/Theme/Backend/profile-list');

        if ($request->getData('ptype') === 'p') {
            $view->data['accounts'] = ProfileMapper::getAll()
                    ->with('account')
                    ->with('image')
                    ->where('id', $request->getDataInt('id') ?? 0, '<')
                    ->limit(25)->execute();
        } elseif ($request->getData('ptype') === 'n') {
            $view->data['accounts'] = ProfileMapper::getAll()
                    ->with('account')
                    ->with('image')
                    ->where('id', $request->getDataInt('id') ?? 0, '>')
                    ->limit(25)->execute();
        } else {
            $view->data['accounts'] = ProfileMapper::getAll()
                    ->with('account')
                    ->with('image')
                    ->where('id', 0, '>')
                    ->limit(25)->execute();
        }

        /** @var \Model\Setting $profileImage */
        $profileImage = $this->app->appSettings->get(names: SettingsEnum::DEFAULT_PROFILE_IMAGE, module: 'Profile');

        /** @var \Modules\Media\Models\Media $image */
        $image = MediaMapper::get()
            ->where('id', (int) $profileImage->content)
            ->execute();

        $view->data['defaultImage'] = $image;

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     */
    public function viewProfileView(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        /** @var \phpOMS\Model\Html\Head $head */
        $head = $response->data['Content']->head;
        $head->addAsset(AssetType::CSS, '/Modules/Calendar/Theme/Backend/css/styles.css?v=' . self::VERSION);

        $view->setTemplate('/Modules/Profile/Theme/Backend/profile-view');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1000301001, $request, $response);

        $mediaListView = new \Modules\Media\Theme\Backend\Components\Media\ListView($this->app->l11nManager, $request, $response);
        $mediaListView->setTemplate('/Modules/Media/Theme/Backend/Components/Media/list');
        $view->data['medialist'] = $mediaListView;

        $calendarView = new \Modules\Calendar\Theme\Backend\Components\Calendar\BaseView($this->app->l11nManager, $request, $response);
        $calendarView->setTemplate('/Modules/Calendar/Theme/Backend/Components/Calendar/mini');
        $view->data['calendar'] = $calendarView;

        $mapperQuery = ProfileMapper::get()
            ->with('account')
            ->with('account/addresses')
            ->with('image');

        /** @var \Modules\Profile\Models\Profile $profile */
        $profile = $request->hasData('for')
            ? $mapperQuery->where('account', (int) $request->getData('for'))->execute()
            : $mapperQuery->where('id', (int) $request->getData('id'))->execute();

        $view->data['account'] = $profile;

        $l11n = null;
        if ($profile->account->id === $request->header->account) {
            /** @var \phpOMS\Localization\Localization $l11n */
            $l11n = LocalizationMapper::get()->where('id', $profile->account->l11n->id)->execute();
        }

        $view->data['l11n'] = $l11n ?? new NullLocalization();

        $accGrpSelector               = new \Modules\Profile\Theme\Backend\Components\AccountGroupSelector\BaseView($this->app->l11nManager, $request, $response);
        $view->data['accGrpSelector'] = $accGrpSelector;

        /** @var \Modules\Media\Models\Media[] $media */
        $media = MediaMapper::getAll()
            ->with('createdBy')
            ->where('createdBy', (int) $profile->account->id)
            ->limit(25)
            ->execute();

        $view->data['media'] = $media;

        /** @var \Model\Setting $profileImage */
        $profileImage = $this->app->appSettings->get(names: SettingsEnum::DEFAULT_PROFILE_IMAGE, module: 'Profile');

        /** @var \Modules\Media\Models\Media $image */
        $image = MediaMapper::get()->where('id', (int) $profileImage->content)->execute();

        $view->data['defaultImage'] = $image;

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     */
    public function viewModuleSettings(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/' . static::NAME . '/Admin/Settings/Theme/Backend/settings');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1000300000, $request, $response);

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     */
    public function viewProfileAdminCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Profile/Theme/Backend/modules-create');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1000300000, $request, $response);

        $accGrpSelector               = new \Modules\Profile\Theme\Backend\Components\AccountGroupSelector\BaseView($this->app->l11nManager, $request, $response);
        $view->data['accGrpSelector'] = $accGrpSelector;

        return $view;
    }
}
