<?php
/**
 * Jingga
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
        $this->app->unitId         = 1;
        $this->app->accountManager = new AccountManager($GLOBALS['session']);
        $this->app->appSettings    = new CoreSettings();
        $this->app->moduleManager  = new ModuleManager($this->app, __DIR__ . '/../../../Modules/');
        $this->app->dispatcher     = new Dispatcher($this->app);
        $this->app->eventManager   = new EventManager($this->app->dispatcher);
        $this->app->l11nManager    = new L11nManager();
        $this->app->eventManager->importFromFile(__DIR__ . '/../../../Web/Api/Hooks.php');

        $account = new Account();
        TestUtils::setMember($account, 'id', 1);

        $permission       = new AccountPermission();
        $permission->unit = 1;
        $permission->app  = 2;
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
     * @covers \Modules\Profile\Controller\ApiController
     * @group module
     */
    public function testApiProfileCreate() : void
    {
        \Modules\Admin\tests\Helper::createAccounts(1);

        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;
        $request->setData('iaccount-idlist', '2');

        $this->module->apiProfileCreate($request, $response);

        self::assertGreaterThan(0, $response->getDataArray('')['response'][0]->id);
    }

    /**
     * @covers \Modules\Profile\Controller\ApiController
     * @group module
     */
    public function testApiProfileTempLoginCreate() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;

        $this->module->apiProfileTempLoginCreate($request, $response);
        self::assertGreaterThan(31, \strlen($response->getDataArray('')['response']));
    }

    /**
     * @covers \Modules\Profile\Controller\ApiController
     * @group module
     */
    public function testApiProfileImageSet() : void
    {
        \copy(__DIR__ . '/icon.png', __DIR__ . '/temp_icon.png');

        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 2;
        $request->setData('names', 'Profile Logo');

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

        $image = ProfileMapper::get()->with('image')->where('id', $response->getDataArray('')['response']->id)->execute()->image;
        self::assertEquals('Profile Logo', $image->name);
    }

    /**
     * @covers \Modules\Profile\Controller\ApiController
     * @group module
     */
    public function testApiProfileImageSetInvalid() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $this->module->apiSettingsAccountImageSet($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }

    /**
     * @covers \Modules\Profile\Controller\ApiController
     * @group module
     */
    /*
    public function testApiContactElementCreate() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;
        $request->setData('account', '1');
        $request->setData('type', ContactType::PHONE);
        $request->setData('content', '+0123-456-789');
        $request->setData('contact', '1');

        $this->module->apiContactElementCreate($request, $response);
        self::assertGreaterThan(0, $response->getDataArray('')['response']->id);
    }
    */

    /**
     * @covers \Modules\Profile\Controller\ApiController
     * @group module
     */
    public function testApiContactElementCreateInvalidData() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;
        $request->setData('invalid', '1');

        $this->module->apiContactElementCreate($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }
}
