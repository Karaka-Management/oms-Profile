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
        'profile_contact_element_id'      => ['name' => 'profile_contact_element_id', 'type' => 'int', 'internal' => 'id'],
        'profile_contact_element_type'    => ['name' => 'profile_contact_element_type', 'type' => 'int', 'internal' => 'type'],
        'profile_contact_element_subtype' => ['name' => 'profile_contact_element_subtype', 'type' => 'int', 'internal' => 'subtype'],
        'profile_contact_element_order'   => ['name' => 'profile_contact_element_order', 'type' => 'int', 'internal' => 'order'],
        'profile_contact_element_content' => ['name' => 'profile_contact_element_content', 'type' => 'string', 'internal' => 'content'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $table = 'profile_contact_element';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $primaryField = 'profile_contact_element_id';
}
