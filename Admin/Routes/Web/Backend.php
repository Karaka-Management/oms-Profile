<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

use Modules\Profile\Controller\BackendController;
use Modules\Profile\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/profile.*$' => [
        [
            'dest'       => '\Modules\Profile\Controller\BackendController:setupProfileStyles',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::PROFILE,
            ],
        ],
    ],
    '^.*/profile/list.*$' => [
        [
            'dest'       => '\Modules\Profile\Controller\BackendController:viewProfileList',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::PROFILE,
            ],
        ],
    ],
    '^.*/profile/single.*$' => [
        [
            'dest'       => '\Modules\Profile\Controller\BackendController:viewProfileSingle',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::PROFILE,
            ],
        ],
    ],
    '^.*/admin/module/settings/profile/settings.*$' => [
        [
            'dest'       => '\Modules\Profile\Controller\BackendController:viewProfileAdminSettings',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::PROFILE,
            ],
        ],
    ],
    '^.*/admin/module/settings/profile/create.*$' => [
        [
            'dest'       => '\Modules\Profile\Controller\BackendController:viewProfileAdminCreate',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::PROFILE,
            ],
        ],
    ],
];
