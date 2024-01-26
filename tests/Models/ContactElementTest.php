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

use Modules\Profile\Models\ContactElement;

/**
 * @internal
 */
final class ContactElementTest extends \PHPUnit\Framework\TestCase
{
    private ContactElement $contact;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->contact = new ContactElement();
    }

    /**
     * @covers Modules\Profile\Models\ContactElement
     * @group module
     */
    public function testDefault() : void
    {
        self::assertEquals(0, $this->contact->id);
        self::assertEquals('', $this->contact->content);
        self::assertEquals(0, $this->contact->order);
    }
}
