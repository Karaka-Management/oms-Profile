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
    protected int $id = 0;

    protected string $name1 = '';

    protected string $name2 = '';

    protected string $name3 = '';

    protected string $description = '';

    protected string $company = '';

    protected string $job = '';

    protected ?\DateTime $birthday = null;

    protected Media $image;

    private int $profile = 0;

    public function __construct()
    {
        $this->image = new NullMedia();
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getName1() : string
    {
        return $this->name1;
    }

    public function setName1(string $name) : void
    {
        $this->name1 = $name;
    }

    public function getName2() : string
    {
        return $this->name2;
    }

    public function setName2(string $name) : void
    {
        $this->name2 = $name;
    }

    public function getName3() : string
    {
        return $this->name3;
    }

    public function setName3(string $name) : void
    {
        $this->name3 = $name;
    }

    public function getDescription() : string
    {
        return $this->description;
    }

    public function setDescription(string $description) : void
    {
        $this->description = $description;
    }

    public function setImage(Media $image) : void
    {
        $this->image = $image;
    }

    public function getImage() : Media
    {
        return $this->image;
    }
}
