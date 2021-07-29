<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\User\Communication\Controller;

use App\Component\User\Business\UserBusinessFacade;
use App\Component\User\Business\UserBusinessFacadeInterface;
use App\Component\User\Communication\Controller\UserController;
use App\DataFixtures\UserFixture;
use App\DataTransferObject\UserDataProvider;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class UserControllerTest extends WebTestCase
{
    private UserFixture $userFixture;
    private UserBusinessFacadeInterface $userBusinessFacade;
    private ?ObjectManager $entityManager;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        //$kernel = self::bootKernel();
        $this->client = self::createClient();

        $this->entityManager = $this->client->getContainer()
            ->get('doctrine')
            ->getManager();

        $userFixture = static::getContainer()->get(UserFixture::class);
        $this->userFixture = $userFixture;

        $userBusinessFacade = static::getContainer()->get(UserBusinessFacade::class);
        $this->userBusinessFacade = $userBusinessFacade;

        $this->userFixture->truncateTable($this->entityManager);
        $this->userFixture->load($this->entityManager);
    }

    protected function tearDown(): void
    {
        $this->entityManager->close();
        $this->entityManager = null;
        parent::tearDown();
    }

    public function testUserDetailPage(): void
    {
        $this->client->request(
            'GET',
            '/user/detail/1'
        );
        self::assertResponseStatusCodeSame(200);
        self::assertSelectorTextContains('h1', '1');
        self::assertSelectorTextContains('h2', 'John');
    }

    public function testListPage(): void
    {
        $clawrel = $this->client->request(
            'GET',
            '/user/list'
        );

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
        self::assertSelectorTextContains('h1', 'Users');

        $userNames = [];
        $dataFromDB = $this->userBusinessFacade->getUserList();

        foreach ($dataFromDB as $user) {
            $userNames[] = $user->getUsername();
        }

        $userList = $clawrel->filter('a.user_detail_url');
        self::assertCount(3, $userList);

        $nodeValue = $userList->getNode(1)->nodeValue;
        self::assertContains($nodeValue, $userNames);
    }

    public function testUserNotFound(): void
    {
        $this->client->request(
            'GET',
            '/user/detail/99999'
        );
        self::assertResponseStatusCodeSame(404);
    }

    public function testDelete(): void
    {
        self::assertInstanceOf(UserDataProvider::class,
            $this->userBusinessFacade->getUserById(1));

        $this->client->request(
            'GET',
            '/user/delete/1'
        );
        self::assertNull($this->userBusinessFacade->getUserById(1));
    }

    public function testDeleteUserNotFound(): void
    {
        $this->client->request(
            'GET',
            '/user/delete/99999999999'
        );
        self::assertResponseStatusCodeSame(404);
    }

    public function testUpdate(): void
    {
        $csrfToken = static::getContainer()->get(CsrfTokenManagerInterface::class)->getToken('user_update')->getValue();

        $this->client->request(
            'POST',
            '/user/update/1',
            [
                'user_update' => [
                    '_token' => $csrfToken,
                    'username' => 'Marco',
                    'password' => [
                        'first' => 't',
                        'second' => 't'
                    ],
                    'userrole' => 'ROLE_USER',
                    'update' => true,
                ],
            ]
        );

        $user = $this->userBusinessFacade->getUserById(1);
        self::assertInstanceOf(UserDataProvider::class, $user);
        self::assertSame('Marco', $user->getUsername());
        self::assertSame('t', $user->getPassword());
        self::assertSame('ROLE_USER', $user->getUserrole());
    }


    public function testUpdateUserPage(): void
    {
        $this->client->request(
            'GET',
            '/user/update/1'
        );
        self::assertResponseStatusCodeSame(200);
        self::assertSelectorTextContains('h1', '1');
        self::assertSelectorTextContains('h2', 'John');
    }


    public function testUpdateUserNotFound(): void
    {
        $this->client->request(
            'GET',
            '/user/update/99999999999'
        );
        self::assertResponseStatusCodeSame(404);
    }

    public function testNew(): void
    {
        $csrfToken = static::getContainer()->get(CsrfTokenManagerInterface::class)->getToken('user_add_new')->getValue();

        $this->client->request(
            'POST',
            '/user/new',
            [
                'user_add_new' => [
                    '_token' => $csrfToken,
                    'username' => 'Marco',
                    'password' => [
                        'first' => 't',
                        'second' => 't'
                    ],
                    'userrole' => 'ROLE_USER',
                    'save' => true,
                ],
            ]
        );
        $response = $this->client->getResponse();
        $id = $response->getContent();

        self::assertInstanceOf(UserDataProvider::class,
            $this->userBusinessFacade->getUserById(4));
    }

    public function testNewUserPage(): void
    {
        $this->client->request(
            'GET',
            '/user/new'
        );
        self::assertResponseStatusCodeSame(200);
    }

}
