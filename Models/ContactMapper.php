<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   Modules\Profile\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\Profile\Models;

use phpOMS\DataStorage\Database\DataMapperAbstract;

/**
 * Contact mapper class.
 *
 * @package Modules\Profile\Models
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class ContactElementMapper extends DataMapperAbstract
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    protected static array $columns = [
        'profile_contact_id' => ['name' => 'profile_contact_id', 'type' => 'int', 'internal' => 'id'],
        'profile_contact_name1' => ['name' => 'profile_contact_name1', 'type' => 'string', 'internal' => 'name1'],
        'profile_contact_name2' => ['name' => 'profile_contact_name2', 'type' => 'string', 'internal' => 'name2'],
        'profile_contact_name3' => ['name' => 'profile_contact_name3', 'type' => 'string', 'internal' => 'name3'],
        'profile_contact_description' => ['name' => 'profile_contact_description', 'type' => 'string', 'internal' => 'description'],
        'profile_contact_company' => ['name' => 'profile_contact_company', 'type' => 'string', 'internal' => 'company'],
        'profile_contact_job' => ['name' => 'profile_contact_job', 'type' => 'string', 'internal' => 'job'],
        'profile_contact_birthday' => ['name' => 'profile_contact_birthday', 'type' => 'DateTime', 'internal' => 'birthday'],
        'profile_contact_profile' => ['name' => 'profile_contact_profile', 'type' => 'int', 'internal' => 'profile'],
    ];

    /**
     * Has one relation.
     *
     * @var array<string, array{mapper:string, self:string, by?:string, column?:string}>
     * @since 1.0.0
     */
    protected static array $ownsOne = [
        'image'    => [
            'mapper' => MediaMapper::class,
            'external'   => 'profile_contact_image',
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $table = 'profile_contact';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $primaryField = 'profile_contact_id';
}
