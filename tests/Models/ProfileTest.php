<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package    tests
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\Profile\tests\Models;

use Modules\Admin\Models\NullAccount;
use Modules\Media\Models\NullMedia;
use Modules\Profile\Models\ContactElement;
use Modules\Profile\Models\GenderType;
use Modules\Profile\Models\Profile;
use Modules\Profile\Models\SexType;
use phpOMS\Stdlib\Base\Location;

/**
 * @internal
 */
final class ProfileTest extends \PHPUnit\Framework\TestCase
{
    private Profile $profile;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->profile = new Profile();
    }

    /**
     * @covers Modules\Profile\Models\Profile
     * @group module
     */
    public function testDefault() : void
    {
        self::assertEquals(0, $this->profile->getId());
        self::assertEquals(GenderType::OTHER, $this->profile->getGender());
        self::assertEquals(SexType::OTHER, $this->profile->getSex());
        self::assertEquals([], $this->profile->getLocation());
        self::assertEquals([], $this->profile->getContactElements());
        self::assertInstanceOf('\Modules\Media\Models\Media', $this->profile->image);
        self::assertInstanceOf('\Modules\Admin\Models\Account', $this->profile->account);
        self::assertNull($this->profile->birthday);
    }

    /**
     * @covers Modules\Profile\Models\Profile
     * @group module
     */
    public function testGenderInputOutput() : void
    {
        $this->profile->setGender(GenderType::FEMALE);
        self::assertEquals(GenderType::FEMALE, $this->profile->getGender());
    }

    /**
     * @covers Modules\Profile\Models\Profile
     * @group module
     */
    public function testInvalidGender() : void
    {
        $this->expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $this->profile->setGender(9999);
    }

    /**
     * @covers Modules\Profile\Models\Profile
     * @group module
     */
    public function testSexInputOutput() : void
    {
        $this->profile->setSex(SexType::FEMALE);
        self::assertEquals(SexType::FEMALE, $this->profile->getSex());
    }

    /**
     * @covers Modules\Profile\Models\Profile
     * @group module
     */
    public function testInvalidSex() : void
    {
        $this->expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $this->profile->setSex(9999);
    }

    /**
     * @covers Modules\Profile\Models\Profile
     * @group module
     */
    public function testBirthdayInputOutput() : void
    {
        $this->profile->birthday = ($date = new \DateTime('now'));
        self::assertEquals($date, $this->profile->birthday);
    }

    /**
     * @covers Modules\Profile\Models\Profile
     * @group module
     */
    public function testImageInputOutput() : void
    {
        $this->profile->image = new NullMedia(1);
        self::assertEquals(1, $this->profile->image->getId());
    }

    /**
     * @covers Modules\Profile\Models\Profile
     * @group module
     */
    public function testLocationInputOutput() : void
    {
        $this->profile->addLocation(new Location());
        self::assertCount(1, $this->profile->getLocation());
    }

    /**
     * @covers Modules\Profile\Models\Profile
     * @group module
     */
    public function testContactElementInputOutput() : void
    {
        $this->profile->addContactElement(new ContactElement());
        self::assertCount(1, $this->profile->getContactElements());
    }

    /**
     * @covers Modules\Profile\Models\Profile
     * @group module
     */
    public function testAccountInputOutput() : void
    {
        $this->profile->account = new NullAccount(1);
        self::assertEquals(1, $this->profile->account->getId());

        $profile = new Profile(new NullAccount(1));
        self::assertEquals(1, $profile->account->getId());
    }

    /**
     * @covers Modules\Profile\Models\Profile
     * @group module
     */
    public function testSerialize() : void
    {
        $this->profile->setGender(GenderType::FEMALE);
        $this->profile->setSex(SexType::FEMALE);
        $this->profile->birthday = ($date = new \DateTime('now'));
        $this->profile->account  = ($a = new NullAccount(1));
        $this->profile->image    = ($i = new NullMedia(1));

        self::assertEquals(
            [
                'id'              => 0,
                'sex'             => SexType::FEMALE,
                'gender'          => GenderType::FEMALE,
                'account'         => $a,
                'image'           => $i,
                'birthday'        => $date,
                'locations'       => [],
                'contactelements' => [],
            ],
            $this->profile->jsonSerialize()
        );
    }
}
