<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Profile\tests\Controller;

use Model\CoreSettings;
use Modules\Admin\Models\AccountPermission;
use Modules\Profile\Models\ContactType;
use Modules\Profile\Models\Profile;
use Modules\Profile\Models\ProfileMapper;
use phpOMS\Account\Account;
use phpOMS\Account\AccountManager;
use phpOMS\Account\PermissionType;
use phpOMS\Application\ApplicationAbstract;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Event\EventManager;
use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Localization\L11nManager;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Module\ModuleAbstract;
use phpOMS\Module\ModuleManager;
use phpOMS\Router\WebRouter;
use phpOMS\Stdlib\Base\AddressType;
use phpOMS\System\MimeType;
use phpOMS\Uri\HttpUri;
use phpOMS\Utils\TestUtils;

/**
 * @internal
 */
final class ApiControllerTest extends \PHPUnit\Framework\TestCase
{
    protected ApplicationAbstract $app;

    /**
     * @var \Modules\Profile\Controller\ApiController
     */
    protected ModuleAbstract $module;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->app = new class() extends ApplicationAbstract
        {
            protected string $appName = 'Api';
        };

        $this->app->dbPool         = $GLOBALS['dbpool'];
        $this->app->unitId          = 1;
        $this->app->accountManager = new AccountManager($GLOBALS['session']);
        $this->app->appSettings    = new CoreSettings();
        $this->app->moduleManager  = new ModuleManager($this->app, __DIR__ . '/../../../Modules/');
        $this->app->dispatcher     = new Dispatcher($this->app);
        $this->app->eventManager   = new EventManager($this->app->dispatcher);
        $this->app->l11nManager    = new L11nManager();
        $this->app->eventManager->importFromFile(__DIR__ . '/../../../Web/Api/Hooks.php');

        $account = new Account();
        TestUtils::setMember($account, 'id', 1);

        $permission = new AccountPermission();
        $permission->setUnit(1);
        $permission->setApp(2);
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

        self::assertGreaterThan(0, $response->get('')['response'][0]->id);
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

        self::assertTrue($this->module->apiProfileCreateDbEntry($profile, $request));
    }

    /**
     * @covers Modules\Profile\Controller\ApiController
     * @group module
     */
    public function testApiProfileTempLoginCreate() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;

        $this->module->apiProfileTempLoginCreate($request, $response);
        self::assertGreaterThan(31, \strlen($response->get('')['response']));
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
        $request->setData('names', 'Profile Logo');
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

        $image = ProfileMapper::get()->with('image')->where('id', $response->get('')['response']->id)->execute()->image;
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

    /**
     * @covers Modules\Profile\Controller\ApiController
     * @group module
     */
    /*
    public function testApiContactElementCreate() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('account', '1');
        $request->setData('type', ContactType::PHONE);
        $request->setData('content', '+0123-456-789');
        $request->setData('contact', '1');

        $this->module->apiContactElementCreate($request, $response);
        self::assertGreaterThan(0, $response->get('')['response']->id);
    }
    */

    /**
     * @covers Modules\Profile\Controller\ApiController
     * @group module
     */
    public function testApiContactElementCreateInvalidData() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('invalid', '1');

        $this->module->apiContactElementCreate($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }

    /**
     * @covers Modules\Profile\Controller\ApiController
     * @group module
     */
    public function testApiAddressCreate() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('account', '1');
        $request->setData('type', AddressType::BUSINESS);
        $request->setData('name', 'Test Addr.');
        $request->setData('address', 'Address here');
        $request->setData('postal', '123456');
        $request->setData('City', 'TestCity');
        $request->setData('Country', ISO3166TwoEnum::_USA);

        $this->module->apiAddressCreate($request, $response);
        self::assertGreaterThan(0, $response->get('')['response']->id);
    }

    /**
     * @covers Modules\Profile\Controller\ApiController
     * @group module
     */
    public function testApiAddressCreateInvalidData() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('invalid', '1');

        $this->module->apiAddressCreate($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }
}
