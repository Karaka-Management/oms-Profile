<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\Profile\Controller\BackendController;
use Modules\Profile\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\Profile\Controller\BackendController:setupProfileStyles',
            'verb'       => RouteVerb::GET,
            'active'     => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::PROFILE,
            ],
        ],
    ],
    '^/profile/list(\?.*$|$)' => [
        [
            'dest'       => '\Modules\Profile\Controller\BackendController:viewProfileList',
            'verb'       => RouteVerb::GET,
            'active'     => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::PROFILE,
            ],
        ],
    ],
    '^/profile/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\Profile\Controller\BackendController:viewProfileView',
            'verb'       => RouteVerb::GET,
            'active'     => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::PROFILE,
            ],
        ],
    ],
    '^/admin/module/settings/view/create(\?.*$|$)' => [
        [
            'dest'       => '\Modules\Profile\Controller\BackendController:viewProfileAdminCreate',
            'verb'       => RouteVerb::GET,
            'active'     => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::PROFILE,
            ],
        ],
    ],
];
