<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\Profile\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Profile\Models;

use phpOMS\Stdlib\Base\Enum;

/**
 * Gender type enum.
 *
 * @package Modules\Profile\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class GenderType extends Enum
{
    public const OTHER = 1;

    public const MALE = 2;

    public const FEMALE = 3;
}
