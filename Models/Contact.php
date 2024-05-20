<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\Profile\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Profile\Models;

use Modules\Media\Models\Media;
use Modules\Media\Models\NullMedia;
use phpOMS\Stdlib\Base\Location;

/**
 * Contact element class.
 *
 * @package Modules\Profile\Models
 * @license OMS License 2.2
 * @link    https://jingga.app
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
    public int $id = 0;

    /**
     * Name1
     *
     * @var string
     * @since 1.0.0
     */
    public string $name1 = '';

    /**
     * Name2
     *
     * @var string
     * @since 1.0.0
     */
    public string $name2 = '';

    /**
     * Name2
     *
     * @var string
     * @since 1.0.0
     */
    public string $name3 = '';

    /**
     * Description
     *
     * @var string
     * @since 1.0.0
     */
    public string $description = '';

    /**
     * Company name
     *
     * @var string
     * @since 1.0.0
     */
    public string $company = '';

    /**
     * Job title
     *
     * @var string
     * @since 1.0.0
     */
    public string $job = '';

    /**
     * Birthday
     *
     * @var null|\DateTime
     * @since 1.0.0
     */
    public ?\DateTime $birthday = null;

    /**
     * Contact image
     *
     * @var Media
     * @since 1.0.0
     */
    public Media $image;

    /**
     * Account this contact belongs to
     *
     * @var int
     * @since 1.0.0
     */
    public int $profile = 0;

    /**
     * Location data.
     *
     * @var Location[]
     * @since 1.0.0
     */
    public array $locations = [];

    /**
     * Contact data.
     *
     * @var Contact[]
     * @since 1.0.0
     */
    public array $contacts = [];

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->image = new NullMedia();
    }
}
