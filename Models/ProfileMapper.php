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

use Modules\Admin\Models\AccountMapper;
use Modules\Admin\Models\AddressMapper;
use Modules\Media\Models\MediaMapper;
use phpOMS\DataStorage\Database\DataMapperAbstract;

/**
 * Profile mapper.
 *
 * @package Modules\Profile\Models
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class ProfileMapper extends DataMapperAbstract
{
    /**
     * Columns.
     *
     * @var array<string, array<string, bool|string|array>>
     * @since 1.0.0
     */
    protected static array $columns = [
        'profile_account_id'       => ['name' => 'profile_account_id',       'type' => 'int',      'internal' => 'id'],
        'profile_account_image'    => ['name' => 'profile_account_image',    'type' => 'int',      'internal' => 'image',    'annotations' => ['gdpr' => true]],
        'profile_account_birthday' => ['name' => 'profile_account_birthday', 'type' => 'DateTime', 'internal' => 'birthday', 'annotations' => ['gdpr' => true]],
        'profile_account_gender'   => ['name' => 'profile_account_gender', 'type' => 'int', 'internal' => 'gender', 'annotations' => ['gdpr' => true]],
        'profile_account_sex'      => ['name' => 'profile_account_sex', 'type' => 'int', 'internal' => 'sex', 'annotations' => ['gdpr' => true]],
        'profile_account_account'  => ['name' => 'profile_account_account',  'type' => 'int',      'internal' => 'account'],
    ];

    /**
     * Has one relation.
     *
     * @var array<string, array{mapper:string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    protected static array $ownsOne = [
        'account'  => [
            'mapper'     => AccountMapper::class,
            'external'   => 'profile_account_account',
        ],
        'image'    => [
            'mapper'     => MediaMapper::class,
            'external'   => 'profile_account_image',
        ],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    protected static array $hasMany = [
        'location' => [
            'mapper'   => AddressMapper::class,
            'table'    => 'profile_addressrel',
            'external' => 'profile_addressrel_address',
            'self'     => 'profile_addressrel_profile',
        ],
        'contactElements' => [
            'mapper'   => ContactElementMapper::class,
            'table'    => 'profile_contactelementrel',
            'self'     => 'profile_contactelementrel_profile',
            'external' => 'profile_contactelementrel_element',
        ],
    ];

    /**
     * Model to use by the mapper.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $model = Profile::class;

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $table = 'profile_account';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $primaryField = 'profile_account_id';
}
