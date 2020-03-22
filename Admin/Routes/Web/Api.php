<?php declare(strict_types=1);

use Modules\Admin\Controller\ApiController as AdminApiController;
use Modules\Admin\Models\PermissionState as AdminPermissionState;
use Modules\Profile\Controller\ApiController;
use Modules\Profile\Models\PermissionState;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/profile.*$' => [
        [
            'dest' => '\Modules\Profile\Controller\ApiController:apiProfileCreate',
            'verb' => RouteVerb::PUT,
            'permission' => [
                'module' => ApiController::MODULE_NAME,
                'type'  => PermissionType::CREATE,
                'state' => PermissionState::PROFILE,
            ],
        ],
    ],

    '^.*/profile/settings/localization(\?.*|$)' => [
        [
            'dest' => '\Modules\Admin\Controller\ApiController:apiSettingsAccountLocalizationSet',
            'verb' => RouteVerb::SET,
            'permission' => [
                'module' => AdminApiController::MODULE_NAME,
                'type'  => PermissionType::MODIFY,
                'state' => AdminPermissionState::ACCOUNT_SETTINGS,
            ],
        ],
    ],
];
