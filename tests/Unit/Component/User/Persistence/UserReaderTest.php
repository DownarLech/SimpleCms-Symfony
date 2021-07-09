<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\User\Persistence;

use App\Component\User\Business\UserBusinessFacade;
use App\Component\User\Business\UserBusinessFacadeInterface;
use App\DataFixtures\UserFixture;
use App\DataTransferObject\UserDataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserReaderTest extends KernelTestCase
{
    private UserBusinessFacadeInterface $userFacade;
    private UserFixture $userFixture;
    private ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $userFacade = static::getContainer()->get(UserBusinessFacade::class);
        $this->userFacade = $userFacade;

        $userFixture = static::getContainer()->get(UserFixture::class);
        $this->userFixture = $userFixture;

        $this->userFixture->load($this->entityManager);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function testGetUserList(): void
    {
        $dataFromDb = $this->userFacade->getUserList();
        $dataGiven = $this->userFixture->getData();

        self::assertCount(3, $dataFromDb);
        self::assertSame(1, $dataFromDb[1]->getId());
        self::assertSame('Mark', $dataFromDb[2]->getUsername());
        self::assertSame('b', $dataFromDb[2]->getPassword());
        self::assertSame('Customer', $dataFromDb[3]->getUserrole());

        foreach ($dataFromDb as $dataDb) {
            $data = $dataGiven[$dataDb->getId() - 1];
            self::assertSame($dataDb->getUserName(), $data['username']);
            self::assertSame($dataDb->getPassword(), $data['password']);
            self::assertSame($dataDb->getUserrole(), $data['userrole']);
        }
    }

    public function testGetUserByNameAndPassword(): void
    {
        $dataFromDb = $this->userFacade->getUserByNameAndPassword('Mark', 'b');

        self::assertInstanceOf(UserDataProvider::class, $dataFromDb);
        self::assertSame(2, $dataFromDb->getId());
        self::assertSame('Mark', $dataFromDb->getUsername());
        self::assertSame('b', $dataFromDb->getPassword());
        self::assertSame('Customer', $dataFromDb->getUserrole());
    }

    public function testGetUserByNameAndPasswordNotFound(): void
    {
        $dataFromDb = $this->userFacade->getUserByNameAndPassword('Marek', 'b');

        self::assertNull($dataFromDb);
    }


    public function testGetUserById(): void
    {
        $dataFromDb = $this->userFacade->getUserById(3);

        self::assertInstanceOf(UserDataProvider::class, $dataFromDb);
        self::assertSame(3, $dataFromDb->getId());
        self::assertSame('Tom', $dataFromDb->getUsername());
        self::assertSame('c', $dataFromDb->getPassword());
        self::assertSame('Customer', $dataFromDb->getUserrole());
    }

    public function testGetUserByIdNotFound(): void
    {
        $dataFromDb = $this->userFacade->getUserById(9);

        self::assertNull($dataFromDb);
    }
}
