<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package    tests
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 2.0
 * @version    1.0.0
 * @link       https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Profile\tests\Models;

use Modules\Admin\Models\NullAccount;
use Modules\Media\Models\NullMedia;
use Modules\Profile\Models\GenderType;
use Modules\Profile\Models\Profile;
use Modules\Profile\Models\SexType;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Modules\Profile\Models\Profile::class)]
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

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testDefault() : void
    {
        self::assertEquals(0, $this->profile->id);
        self::assertEquals(GenderType::OTHER, $this->profile->getGender());
        self::assertEquals(SexType::OTHER, $this->profile->getSex());
        self::assertInstanceOf('\Modules\Media\Models\Media', $this->profile->image);
        self::assertInstanceOf('\Modules\Admin\Models\Account', $this->profile->account);
        self::assertNull($this->profile->birthday);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testGenderInputOutput() : void
    {
        $this->profile->setGender(GenderType::FEMALE);
        self::assertEquals(GenderType::FEMALE, $this->profile->getGender());
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testInvalidGender() : void
    {
        $this->expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $this->profile->setGender(9999);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testSexInputOutput() : void
    {
        $this->profile->setSex(SexType::FEMALE);
        self::assertEquals(SexType::FEMALE, $this->profile->getSex());
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testInvalidSex() : void
    {
        $this->expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $this->profile->setSex(9999);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testBirthdayInputOutput() : void
    {
        $this->profile->birthday = ($date = new \DateTime('now'));
        self::assertEquals($date, $this->profile->birthday);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testImageInputOutput() : void
    {
        $this->profile->image = new NullMedia(1);
        self::assertEquals(1, $this->profile->image->id);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testAccountInputOutput() : void
    {
        $this->profile->account = new NullAccount(1);
        self::assertEquals(1, $this->profile->account->id);

        $profile = new Profile(new NullAccount(1));
        self::assertEquals(1, $profile->account->id);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testSerialize() : void
    {
        $this->profile->setGender(GenderType::FEMALE);
        $this->profile->setSex(SexType::FEMALE);
        $this->profile->birthday = ($date = new \DateTime('now'));
        $this->profile->account  = ($a = new NullAccount(1));
        $this->profile->image    = ($i = new NullMedia(1));

        self::assertEquals(
            [
                'id'       => 0,
                'sex'      => SexType::FEMALE,
                'gender'   => GenderType::FEMALE,
                'account'  => $a,
                'image'    => $i,
                'birthday' => $date,
            ],
            $this->profile->jsonSerialize()
        );
    }
}
