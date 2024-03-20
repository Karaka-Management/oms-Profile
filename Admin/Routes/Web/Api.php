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

use Modules\Admin\Controller\ApiController as AdminApiController;
use Modules\Admin\Models\PermissionCategory as AdminPermissionCategory;
use Modules\Profile\Controller\ApiController;
use Modules\Profile\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/view$' => [
        [
            'dest'       => '\Modules\Profile\Controller\ApiController:apiProfileCreate',
            'verb'       => RouteVerb::PUT,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::PROFILE,
            ],
        ],
    ],

    '^.*/view/settings/localization(\?.*|$)' => [
        [
            'dest'       => '\Modules\Admin\Controller\ApiController:apiSettingsAccountLocalizationSet',
            'verb'       => RouteVerb::SET,
            'permission' => [
                'module' => AdminApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => AdminPermissionCategory::ACCOUNT_SETTINGS,
            ],
        ],
    ],
    '^.*/view/settings/password(\?.*|$)' => [
        [
            'dest'       => '\Modules\Admin\Controller\ApiController:apiSettingsAccountPasswordSet',
            'verb'       => RouteVerb::SET,
            'permission' => [
                'module' => AdminApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => AdminPermissionCategory::ACCOUNT_SETTINGS,
            ],
        ],
    ],
    '^.*/view/settings/image(\?.*|$)' => [
        [
            'dest'       => '\Modules\Profile\Controller\ApiController:apiSettingsAccountImageSet',
            'verb'       => RouteVerb::SET,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::PROFILE,
            ],
        ],
    ],
];
