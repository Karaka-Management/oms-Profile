<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Profile\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Profile\Models;

use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Contact mapper class.
 *
 * @package Modules\Profile\Models
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class ContactElementMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'profile_contact_element_id'      => ['name' => 'profile_contact_element_id', 'type' => 'int', 'internal' => 'id'],
        'profile_contact_element_type'    => ['name' => 'profile_contact_element_type', 'type' => 'int', 'internal' => 'type'],
        'profile_contact_element_subtype' => ['name' => 'profile_contact_element_subtype', 'type' => 'int', 'internal' => 'subtype'],
        'profile_contact_element_order'   => ['name' => 'profile_contact_element_order', 'type' => 'int', 'internal' => 'order'],
        'profile_contact_element_content' => ['name' => 'profile_contact_element_content', 'type' => 'string', 'internal' => 'content'],
        'profile_contact_element_contact' => ['name' => 'profile_contact_element_contact', 'type' => 'int', 'internal' => 'contact'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'profile_contact_element';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD ='profile_contact_element_id';
}
