<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Profile\Admin\Install
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Profile\Admin\Install;

use Model\Setting;
use Model\SettingMapper;
use Modules\Profile\Models\SettingsEnum;
use phpOMS\Application\ApplicationAbstract;

/**
 * Media class.
 *
 * @package Modules\Profile\Admin\Install
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Media
{
    /**
     * Install media providing
     *
     * @param ApplicationAbstract $app  Application
     * @param string              $path Module path
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function install(ApplicationAbstract $app, string $path) : void
    {
        $media = \Modules\Media\Admin\Installer::installExternal($app, ['path' => __DIR__ . '/Media.install.json']);

        $defaultProfileImage = (int) \reset($media['upload'][0]);

        $setting = new Setting();
        SettingMapper::create()->execute($setting->with(0, SettingsEnum::DEFAULT_PROFILE_IMAGE, (string) $defaultProfileImage, '\\d+', null, 'Profile'));
    }
}
