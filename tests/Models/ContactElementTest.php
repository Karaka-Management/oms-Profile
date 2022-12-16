<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package    tests
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
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
        self::assertEquals(0, $this->contact->getId());
        self::assertEquals('', $this->contact->content);
        self::assertEquals(0, $this->contact->order);
    }

    /**
     * @covers Modules\Profile\Models\ContactElement
     * @group module
     */
    public function testTypeInputOutput() : void
    {
        $this->contact->setType(3);
        self::assertEquals(3, $this->contact->getType());
    }

    /**
     * @covers Modules\Profile\Models\ContactElement
     * @group module
     */
    public function testSubtypeInputOutput() : void
    {
        $this->contact->setSubtype(3);
        self::assertEquals(3, $this->contact->getSubtype());
    }
}
