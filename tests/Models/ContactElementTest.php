<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package    tests
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 2.2
 * @version    1.0.0
 * @link       https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Profile\tests\Models;

use Modules\Profile\Models\ContactElement;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Modules\Profile\Models\ContactElement::class)]
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

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testDefault() : void
    {
        self::assertEquals(0, $this->contact->id);
        self::assertEquals('', $this->contact->content);
        self::assertEquals(0, $this->contact->order);
    }
}
