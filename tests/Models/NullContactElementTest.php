<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Profile\tests\Models;

use Modules\Profile\Models\NullContactElement;

/**
 * @internal
 */
final class NullContactElementTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\Profile\Models\NullContactElement
     * @group module
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\Profile\Models\ContactElement', new NullContactElement());
    }

    /**
     * @covers Modules\Profile\Models\NullContactElement
     * @group module
     */
    public function testId() : void
    {
        $null = new NullContactElement(2);
        self::assertEquals(2, $null->id);
    }

    /**
     * @covers Modules\Profile\Models\NullContactElement
     * @group module
     */
    public function testJsonSerialize() : void
    {
        $null = new NullContactElement(2);
        self::assertEquals(['id' => 2], $null);
    }
}
