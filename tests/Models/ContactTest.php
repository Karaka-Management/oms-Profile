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

use Modules\Profile\Models\Contact;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Modules\Profile\Models\Contact::class)]
final class ContactTest extends \PHPUnit\Framework\TestCase
{
    private Contact $contact;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->contact = new Contact();
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testDefault() : void
    {
        self::assertEquals(0, $this->contact->id);
        self::assertEquals('', $this->contact->name1);
        self::assertEquals('', $this->contact->name2);
        self::assertEquals('', $this->contact->name3);
        self::assertEquals('', $this->contact->description);
        self::assertEquals('', $this->contact->company);
        self::assertEquals('', $this->contact->job);
        self::assertNull($this->contact->birthday);
        self::assertInstanceOf('\Modules\Media\Models\Media', $this->contact->image);
    }
}
