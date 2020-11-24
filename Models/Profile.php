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

use Modules\Admin\Models\Account;
use Modules\Admin\Models\NullAccount;
use Modules\Media\Models\Media;
use Modules\Media\Models\NullMedia;
use phpOMS\Stdlib\Base\Exception\InvalidEnumValue;
use phpOMS\Stdlib\Base\Location;

/**
 * Profile class.
 *
 * @package Modules\Profile\Models
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Profile implements \JsonSerializable
{
    /**
     * Id.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $id = 0;

    /**
     * Profile image.
     *
     * @var Media
     * @since 1.0.0
     */
    public Media $image;

    /**
     * Birthday.
     *
     * @var null|\DateTime
     * @since 1.0.0
     */
    public ?\DateTime $birthday = null;

    /**
     * Account.
     *
     * @var Account
     * @since 1.0.0
     */
    public Account $account;

    /**
     * Location data.
     *
     * @var Location[]
     * @since 1.0.0
     */
    protected array $location = [];

    /**
     * Contact data.
     *
     * @var ContactElement[]
     * @since 1.0.0
     */
    protected array $contactElements = [];

    /**
     * Gender.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $gender = GenderType::OTHER;

    /**
     * Sex.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $sex = SexType::OTHER;

    /**
     * Constructor.
     *
     * @param null|Account $account Account to initialize this profile with
     *
     * @since 1.0.0
     */
    public function __construct(Account $account = null)
    {
        $this->image    = new NullMedia();
        $this->account  = $account ?? new NullAccount();
    }

    /**
     * Get account id.
     *
     * @return int Account id
     *
     * @since 1.0.0
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get gender.
     *
     * @return int Returns the gender (GenderType)
     *
     * @since 1.0.0
     */
    public function getGender() : int
    {
        return $this->gender;
    }

    /**
     * Get gender.
     *
     * @param int $gender Gender
     *
     * @return void
     *
     * @throws InvalidEnumValue This exception is thrown if a invalid gender is used
     *
     * @since 1.0.0
     */
    public function setGender(int $gender) : void
    {
        if (!GenderType::isValidValue($gender)) {
            throw new InvalidEnumValue($gender);
        }

        $this->gender = $gender;
    }

    /**
     * Get sex.
     *
     * @return int Returns the sex (SexType)
     *
     * @since 1.0.0
     */
    public function getSex() : int
    {
        return $this->sex;
    }

    /**
     * Get sex.
     *
     * @param int $sex Sex
     *
     * @return void
     *
     * @throws InvalidEnumValue This exception is thrown if a invalid sex is used
     *
     * @since 1.0.0
     */
    public function setSex(int $sex) : void
    {
        if (!SexType::isValidValue($sex)) {
            throw new InvalidEnumValue($sex);
        }

        $this->sex = $sex;
    }

    /**
     * Get account locations.
     *
     * @return Location[]
     *
     * @since 1.0.0
     */
    public function getLocation() : array
    {
        return $this->location;
    }

    /**
     * Add location.
     *
     * @param Location $location Location
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addLocation(Location $location) : void
    {
        $this->location[] = $location;
    }

    /**
     * Get account contact element.
     *
     * @return ContactElement[]
     *
     * @since 1.0.0
     */
    public function getContactElements() : array
    {
        return $this->contactElements;
    }

    /**
     * Add contact element.
     *
     * @param ContactElement $contactElement Contact Element
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addContactElement(ContactElement $contactElement) : void
    {
        $this->contactElements[] = $contactElement;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'id'              => $this->id,
            'sex'             => $this->sex,
            'gender'          => $this->gender,
            'account'         => $this->account,
            'image'           => $this->image,
            'birthday'        => $this->birthday,
            'locations'       => $this->location,
            'contactelements' => $this->contactElements,
        ];
    }
}
