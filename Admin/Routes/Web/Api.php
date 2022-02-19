<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

use Modules\Admin\Controller\ApiController as AdminApiController;
use Modules\Admin\Models\PermissionState as AdminPermissionState;
use Modules\Profile\Controller\ApiController;
use Modules\Profile\Models\PermissionState;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/profile.*$' => [
        [
            'dest'       => '\Modules\Profile\Controller\ApiController:apiProfileCreate',
            'verb'       => RouteVerb::PUT,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionState::PROFILE,
            ],
        ],
    ],

    '^.*/profile/settings/localization(\?.*|$)' => [
        [
            'dest'       => '\Modules\Admin\Controller\ApiController:apiSettingsAccountLocalizationSet',
            'verb'       => RouteVerb::SET,
            'permission' => [
                'module' => AdminApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => AdminPermissionState::ACCOUNT_SETTINGS,
            ],
        ],
    ],
    '^.*/profile/settings/image(\?.*|$)' => [
        [
            'dest'       => '\Modules\Profile\Controller\ApiController:apiSettingsAccountImageSet',
            'verb'       => RouteVerb::SET,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionState::PROFILE,
            ],
        ],
    ],
];
