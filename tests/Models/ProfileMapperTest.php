<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\ClientMapper\tests\Models;

use Modules\Admin\Models\AccountMapper;
use Modules\Admin\Models\NullAccount;
use Modules\Media\Models\Media;
use Modules\Profile\Models\Profile;
use Modules\Profile\Models\ProfileMapper;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Modules\Profile\Models\ProfileMapper::class)]
final class ProfileMapperTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testCRUD() : void
    {
        $media              = new Media();
        $media->createdBy   = new NullAccount(1);
        $media->description = 'desc';
        $media->setPath('Web/Backend/img/default-user.jpg');
        $media->size      = 11;
        $media->extension = 'png';
        $media->name      = 'Image';

        if (($profile = ProfileMapper::get()->where('account', 1)->execute())->id === 0) {
            $profile = new Profile();

            $profile->account  = AccountMapper::get()->where('id', 1)->execute();
            $profile->image    = $media;
            $profile->birthday = new \DateTime('now');

            $id = ProfileMapper::create()->execute($profile);
            self::assertGreaterThan(0, $profile->id);
            self::assertEquals($id, $profile->id);
        } else {
            $profile->image    = $media;
            $profile->birthday = new \DateTime('now');

            ProfileMapper::update()->with('image')->execute($profile);
        }

        $profileR = ProfileMapper::get()->with('image')->with('account')->where('id', $profile->id)->execute();
        self::assertEquals($profile->birthday->format('Y-m-d'), $profileR->birthday->format('Y-m-d'));
        self::assertEquals($profile->image->name, $profileR->image->name);
    }
}
