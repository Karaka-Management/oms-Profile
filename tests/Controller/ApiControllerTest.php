<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\Profile\tests\Controller;

use Model\CoreSettings;
use Modules\Admin\Models\AccountPermission;
use Modules\Profile\Models\Profile;
use Modules\Profile\Models\ProfileMapper;
use phpOMS\Account\Account;
use phpOMS\Account\AccountManager;
use phpOMS\Account\PermissionType;
use phpOMS\Application\ApplicationAbstract;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Event\EventManager;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Module\ModuleManager;
use phpOMS\Router\WebRouter;
use phpOMS\System\MimeType;
use phpOMS\Uri\HttpUri;
use phpOMS\Utils\TestUtils;

/**
 * @internal
 */
class ApiControllerTest extends \PHPUnit\Framework\TestCase
{
    protected $app    = null;

    protected $module = null;

    protected function setUp() : void
    {
        $this->app = new class() extends ApplicationAbstract
        {
            protected string $appName = 'Api';
        };

        $this->app->dbPool         = $GLOBALS['dbpool'];
        $this->app->orgId          = 1;
        $this->app->accountManager = new AccountManager($GLOBALS['session']);
        $this->app->appSettings    = new CoreSettings($this->app->dbPool->get());
        $this->app->moduleManager  = new ModuleManager($this->app, __DIR__ . '/../../../Modules/');
        $this->app->dispatcher     = new Dispatcher($this->app);
        $this->app->eventManager   = new EventManager($this->app->dispatcher);
        $this->app->eventManager->importFromFile(__DIR__ . '/../../../Web/Api/Hooks.php');

        $account = new Account();
        TestUtils::setMember($account, 'id', 1);

        $permission = new AccountPermission();
        $permission->setUnit(1);
        $permission->setApp('backend');
        $permission->setPermission(
            PermissionType::READ
            | PermissionType::CREATE
            | PermissionType::MODIFY
            | PermissionType::DELETE
            | PermissionType::PERMISSION
        );

        $account->addPermission($permission);

        $this->app->accountManager->add($account);
        $this->app->router = new WebRouter();

        $this->module = $this->app->moduleManager->get('Profile');

        TestUtils::setMember($this->module, 'app', $this->app);
    }

    /**
     * @covers Modules\Profile\Controller\ApiController
     * @group module
     */
    public function testApiProfileCreate() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('iaccount-idlist', '1');

        $this->module->apiProfileCreate($request, $response);

        self::assertEquals('admin', $response->get('')['response'][0]->account->login);
        self::assertGreaterThan(0, $response->get('')['response'][0]->getId());
    }

    /**
     * @covers Modules\Profile\Controller\ApiController
     * @group module
     */
    public function testApiProfileCreateDbEntry() : void
    {
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;

        $profile                 = new Profile(new \Modules\Admin\Models\Account());
        $profile->account->login = 'ProfileCreateDb';
        $profile->account->setEmail('profile_create_db@email.com');

        $this->module->apiProfileCreateDbEntry($profile, $request);
    }

    /**
     * @covers Modules\Profile\Controller\ApiController
     * @group module
     */
    public function testApiProfileImageSet() : void
    {
        \copy(__DIR__ . '/icon.png', __DIR__ . '/temp_icon.png');

        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('name', 'Profile Logo');
        $request->setData('id', 1);

        TestUtils::setMember($request, 'files', [
            'file1' => [
                'name'     => 'icon.png',
                'type'     => MimeType::M_PNG,
                'tmp_name' => __DIR__ . '/temp_icon.png',
                'error'    => \UPLOAD_ERR_OK,
                'size'     => \filesize(__DIR__ . '/icon.png'),
            ],
        ]);
        $this->module->apiSettingsAccountImageSet($request, $response);

        $image = ProfileMapper::get($response->get('')['response']->getId())->image;
        self::assertEquals('Profile Logo', $image->name);
    }

    /**
     * @covers Modules\Profile\Controller\ApiController
     * @group module
     */
    public function testApiProfileImageSetInvalid() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $this->module->apiSettingsAccountImageSet($request, $response);

        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }
}
