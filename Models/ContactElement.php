<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\Profile\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Profile\Models;

/**
 * Contact element class.
 *
 * Information such as phone number, email, ...
 *
 * @package Modules\Profile\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class ContactElement
{
    /**
     * ID.
     *
     * @var int
     * @since 1.0.0
     */
    public int $id = 0;

    /**
     * Contact element type.
     *
     * @var int
     * @since 1.0.0
     */
    public int $type = 0;

    /**
     * Contact element subtype.
     *
     * @var int
     * @since 1.0.0
     */
    public int $subtype = 0;

    /**
     * Content.
     *
     * @var string
     * @since 1.0.0
     */
    public string $content = '';

    /**
     * Order.
     *
     * @var int
     * @since 1.0.0
     */
    public int $order = 0;

    public int $contact = 0;

    /**
     * Set subtype
     *
     * @param int $subtype Subtype
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setSubtype(int $subtype) : void
    {
        $this->subtype = $subtype;
    }

    /**
     * Get subtype
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getSubtype() : int
    {
        return $this->subtype;
    }
}
