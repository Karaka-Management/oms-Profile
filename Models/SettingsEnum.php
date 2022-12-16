<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Profile\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Profile\Models;

use phpOMS\Stdlib\Base\Enum;

/**
 * Default settings enum.
 *
 * @package Modules\Profile\Models
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class SettingsEnum extends Enum
{
    public const DEFAULT_PROFILE_IMAGE = '1000300001';
}
