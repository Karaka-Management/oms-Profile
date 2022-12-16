<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Profile\tests\Models;

use Modules\Profile\Models\NullProfile;

/**
 * @internal
 */
final class NullProfileTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\Profile\Models\NullProfile
     * @group module
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\Profile\Models\Profile', new NullProfile());
    }

    /**
     * @covers Modules\Profile\Models\NullProfile
     * @group module
     */
    public function testId() : void
    {
        $null = new NullProfile(2);
        self::assertEquals(2, $null->getId());
    }
}
