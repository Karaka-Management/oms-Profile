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

use Modules\Admin\Models\AccountMapper;
use Modules\Media\Models\MediaMapper;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Profile mapper.
 *
 * @package Modules\Profile\Models
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class ProfileMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
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
     * @var array<string, array{mapper:class-string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    public const OWNS_ONE = [
        'image'    => [
            'mapper'     => MediaMapper::class,
            'external'   => 'profile_account_image',
        ],
    ];

    /**
     * Belongs to.
     *
     * @var array<string, array{mapper:class-string, external:string, column?:string, by?:string}>
     * @since 1.0.0
     */
    public const BELONGS_TO = [
        'account'  => [
            'mapper'     => AccountMapper::class,
            'external'   => 'profile_account_account',
        ],
    ];

    /**
     * Model to use by the mapper.
     *
     * @var class-string
     * @since 1.0.0
     */
    public const MODEL = Profile::class;

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'profile_account';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'profile_account_id';
}
