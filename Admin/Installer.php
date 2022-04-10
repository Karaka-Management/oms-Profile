<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   Modules\Profile\Admin
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\Profile\Admin;

use Modules\Admin\Models\AccountMapper;
use Modules\Profile\Models\Profile;
use Modules\Profile\Models\ProfileMapper;
use phpOMS\Application\ApplicationAbstract;
use phpOMS\Config\SettingsInterface;
use phpOMS\Module\InstallerAbstract;
use phpOMS\Module\ModuleInfo;

/**
 * Installer class.
 *
 * @package Modules\Profile\Admin
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class Installer extends InstallerAbstract
{
    /**
     * Path of the file
     *
     * @var string
     * @since 1.0.0
     */
    public const PATH = __DIR__;

    /**
     * {@inheritdoc}
     */
    public static function install(ApplicationAbstract $app, ModuleInfo $info, SettingsInterface $cfgHandler) : void
    {
        parent::install($app, $info, $cfgHandler);

        self::createProfiles();
    }

    /**
     * Create profiles for already installed accounts
     *
     * @return void
     *
     * @since 1.0.0
     */
    private static function createProfiles() : void
    {
        $profile = new Profile(AccountMapper::get()->where('id', 1)->execute());
        ProfileMapper::create()->execute($profile);
    }
}
