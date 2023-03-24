<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Profile\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Profile\Models;

use Modules\Admin\Models\AddressMapper;
use Modules\Media\Models\MediaMapper;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Contact mapper class.
 *
 * @package Modules\Profile\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class ContactMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'profile_contact_id'          => ['name' => 'profile_contact_id', 'type' => 'int', 'internal' => 'id'],
        'profile_contact_name1'       => ['name' => 'profile_contact_name1', 'type' => 'string', 'internal' => 'name1'],
        'profile_contact_name2'       => ['name' => 'profile_contact_name2', 'type' => 'string', 'internal' => 'name2'],
        'profile_contact_name3'       => ['name' => 'profile_contact_name3', 'type' => 'string', 'internal' => 'name3'],
        'profile_contact_description' => ['name' => 'profile_contact_description', 'type' => 'string', 'internal' => 'description'],
        'profile_contact_company'     => ['name' => 'profile_contact_company', 'type' => 'string', 'internal' => 'company'],
        'profile_contact_job'         => ['name' => 'profile_contact_job', 'type' => 'string', 'internal' => 'job'],
        'profile_contact_birthday'    => ['name' => 'profile_contact_birthday', 'type' => 'DateTime', 'internal' => 'birthday'],
        'profile_contact_profile'     => ['name' => 'profile_contact_profile', 'type' => 'int', 'internal' => 'profile'],
    ];

    /**
     * Has one relation.
     *
     * @var array<string, array{mapper:class-string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    public const OWNS_ONE = [
        'image'    => [
            'mapper'     => MediaMapper::class,
            'external'   => 'profile_contact_image',
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'profile_contact';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'profile_contact_id';

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:class-string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'locations' => [
            'mapper'   => AddressMapper::class,
            'table'    => 'profile_contact_addressrel',
            'external' => 'profile_contact_addressrel_address',
            'self'     => 'profile_contact_addressrel_contact',
        ],
        'contacts' => [
            'mapper'   => ContactElementMapper::class,
            'table'    => 'profile_contact_element',
            'self'     => 'profile_contact_element_contact',
            'external' => null,
        ],
    ];
}
