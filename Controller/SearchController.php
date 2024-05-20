<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\Profile
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Profile\Controller;

use Modules\Admin\Models\ContactType;
use Modules\Media\Models\MediaMapper;
use Modules\Profile\Models\ProfileMapper;
use Modules\Profile\Models\SettingsEnum;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\System\MimeType;

/**
 * Search class.
 *
 * @package Modules\Profile
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class SearchController extends Controller
{
    /**
     * Api method to search for tags
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function searchGeneral(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        $names   = \explode(' ', ($request->getDataString('search') ?? ''));
        $names[] = ($request->getDataString('search') ?? '');

        $mapper = ProfileMapper::getAll()
            ->with('account')
            ->with('image')
            ->with('account/addresses')
            ->with('account/contacts')
            ->limit(8);

        foreach ($names as $name) {
            $mapper->where('account/login', '%' . $name . '%', 'LIKE', 'OR')
                ->where('account/name1', '%' . $name . '%', 'LIKE', 'OR')
                ->where('account/name2', '%' . $name . '%', 'LIKE', 'OR')
                ->where('account/name3', '%' . $name . '%', 'LIKE', 'OR');
        }

        /** @var \Modules\Profile\Models\Profile[] $accounts */
        $accounts = $mapper->execute();

        /** @var \Model\Setting $profileImage */
        $profileImage = $this->app->appSettings->get(names: SettingsEnum::DEFAULT_PROFILE_IMAGE, module: 'Profile');

        /** @var \Modules\Media\Models\Media $default */
        $default = MediaMapper::get()
            ->where('id', (int) $profileImage->content)
            ->execute();

        $results = [];
        foreach ($accounts as $account) {
            $address = empty($account->account->addresses) ? null : \reset($account->account->addresses);

            $results[] = [
                'title' => $account->account->name1 . ' ' . $account->account->name2,
                'link'  => '{/base}/profile/view?id=' . $account->id,
                'email' => $account->account->getContactByType(ContactType::EMAIL)->content,
                'phone' => $account->account->getContactByType(ContactType::PHONE)->content,
                'city'  => $address?->city,
                'image' => $account->image->id === 0
                    ? $default->getPath()
                    : $account->image->getPath(),
                'tags'   => [],
                'type'   => 'list_accounts',
                'module' => 'Profile',
            ];
        }

        $response->header->set('Content-Type', MimeType::M_JSON . '; charset=utf-8', true);
        $response->add($request->uri->__toString(), $results);
    }
}
