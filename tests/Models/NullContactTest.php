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

namespace Modules\Profile\tests\Models;

use Modules\Profile\Models\NullContact;

/**
 * @internal
 */
final class NullContactTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\Profile\Models\NullContact
     * @group framework
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\Profile\Models\Contact', new NullContact());
    }

    /**
     * @covers Modules\Profile\Models\NullContact
     * @group framework
     */
    public function testId() : void
    {
        $null = new NullContact(2);
        self::assertEquals(2, $null->getId());
    }
}
