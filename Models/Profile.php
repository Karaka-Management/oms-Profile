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

use Modules\Admin\Models\Account;
use Modules\Admin\Models\NullAccount;
use Modules\Media\Models\Media;
use Modules\Media\Models\NullMedia;
use phpOMS\Stdlib\Base\Exception\InvalidEnumValue;

/**
 * Profile class.
 *
 * @package Modules\Profile\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
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
    public int $id = 0;

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
     * Gender.
     *
     * @var int
     * @since 1.0.0
     */
    public int $gender = GenderType::OTHER;

    /**
     * Sex.
     *
     * @var int
     * @since 1.0.0
     */
    public int $sex = SexType::OTHER;

    /**
     * Confirmation key.
     *
     * @var string
     * @since 1.0.0
     */
    public string $confirmation = '';

    /**
     * Constructor.
     *
     * @param null|Account $account Account to initialize this profile with
     *
     * @since 1.0.0
     */
    public function __construct(?Account $account = null)
    {
        $this->image   = new NullMedia();
        $this->account = $account ?? new NullAccount();
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
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return [
            'id'       => $this->id,
            'sex'      => $this->sex,
            'gender'   => $this->gender,
            'account'  => $this->account,
            'image'    => $this->image,
            'birthday' => $this->birthday,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : mixed
    {
        return $this->toArray();
    }
}
