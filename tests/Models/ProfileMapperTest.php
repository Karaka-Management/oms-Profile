<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
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
class ProfileMapperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\Profile\Models\ProfileMapper
     * @group module
     */
    public function testCRUD() : void
    {
        $media              = new Media();
        $media->createdBy   = new NullAccount(1);
        $media->description = 'desc';
        $media->setPath('Web/Backend/img/default-user.jpg');
        $media->size      = 11;
        $media->extension = 'png';
        $media->name      = 'Image';

        if (($profile = ProfileMapper::getFor(1, 'account'))->getId() === 0) {
            $profile = new Profile();

            $profile->account  = AccountMapper::get(1);
            $profile->image    = $media;
            $profile->birthday =  new \DateTime('now');

            $id = ProfileMapper::create($profile);
            self::assertGreaterThan(0, $profile->getId());
            self::assertEquals($id, $profile->getId());
        } else {
            $profile->image    = $media;
            $profile->birthday =  new \DateTime('now');

            ProfileMapper::update($profile);
        }

        $profileR = ProfileMapper::get($profile->getId());
        self::assertEquals($profile->birthday->format('Y-m-d'), $profileR->birthday->format('Y-m-d'));
        self::assertEquals($profile->image->name, $profileR->image->name);
        self::assertEquals($profile->account->name1, $profileR->account->name1);
    }
}
