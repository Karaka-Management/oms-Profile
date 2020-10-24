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

use Modules\Media\Models\Media;
use Modules\Media\Models\NullMedia;

/**
 * Contact element class.
 *
 * @package Modules\Profile\Models
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Contact
{
    /**
     * Id.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $id = 0;

    /**
     * Name1
     *
     * @var string
     * @since 1.0.0
     */
    protected string $name1 = '';

    /**
     * Name2
     *
     * @var string
     * @since 1.0.0
     */
    protected string $name2 = '';

    /**
     * Name2
     *
     * @var string
     * @since 1.0.0
     */
    protected string $name3 = '';

    /**
     * Description
     *
     * @var string
     * @since 1.0.0
     */
    protected string $description = '';

    /**
     * Company name
     *
     * @var string
     * @since 1.0.0
     */
    protected string $company = '';

    /**
     * Job title
     *
     * @var string
     * @since 1.0.0
     */
    protected string $job = '';

    /**
     * Birthday
     *
     * @var null|\DateTime
     * @since 1.0.0
     */
    protected ?\DateTime $birthday = null;

    /**
     * Contact image
     *
     * @var Media
     * @since 1.0.0
     */
    protected Media $image;

    /**
     * Profile this contact belongs to
     *
     * @var int
     * @since 1.0.0
     */
    private int $profile = 0;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->image = new NullMedia();
    }

    /**
     * Get id.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get name1.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getName1() : string
    {
        return $this->name1;
    }

    /**
     * Set name1
     *
     * @param string $name Name
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setName1(string $name) : void
    {
        $this->name1 = $name;
    }

    /**
     * Get name2.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getName2() : string
    {
        return $this->name2;
    }

    /**
     * Set name2
     *
     * @param string $name Name
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setName2(string $name) : void
    {
        $this->name2 = $name;
    }

    /**
     * Get name3.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getName3() : string
    {
        return $this->name3;
    }

    /**
     * Set name3
     *
     * @param string $name Name
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setName3(string $name) : void
    {
        $this->name3 = $name;
    }

    /**
     * Get the contact description
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * Set the description
     *
     * @param string $description Description
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setDescription(string $description) : void
    {
        $this->description = $description;
    }

    /**
     * Set the image
     *
     * @param Media $image Image
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setImage(Media $image) : void
    {
        $this->image = $image;
    }

    /**
     * Get the image
     *
     * @return Media
     *
     * @since 1.0.0
     */
    public function getImage() : Media
    {
        return $this->image;
    }
}
