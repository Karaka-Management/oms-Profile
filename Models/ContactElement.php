<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   Modules\Profile\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\Profile\Models;

/**
 * Contact element class.
 *
 * @package Modules\Profile\Models
 * @license OMS License 1.0
 * @link    https://orange-management.org
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
    protected int $id = 0;

    /**
     * Contact element type.
     *
     * @var int
     * @since 1.0.0
     */
    private int $type = 0;

    /**
     * Contact element subtype.
     *
     * @var int
     * @since 1.0.0
     */
    private int $subtype = 0;

    /**
     * Content.
     *
     * @var string
     * @since 1.0.0
     */
    private string $content = '';

    /**
     * Order.
     *
     * @var int
     * @since 1.0.0
     */
    private int $order = 0;

    /**
     * Get id.
     *
     * @return int Model id
     *
     * @since 1.0.0
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param int $type Type
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setType(int $type) : void
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getType() : int
    {
        return $this->type;
    }

    /**
     * Set order
     *
     * @param int $order Type
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setOrder(int $order) : void
    {
        $this->order = $order;
    }

    /**
     * Get order
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getOrder() : int
    {
        return $this->order;
    }

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

    /**
     * Get content
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getContent() : string
    {
        return $this->content;
    }

    /**
     * Set content
     *
     * @param string $content Content
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setContent(string $content) : void
    {
        $this->content = $content;
    }
}
