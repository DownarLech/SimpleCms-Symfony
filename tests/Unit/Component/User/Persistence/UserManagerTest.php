<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\User\Persistence;

use App\Component\User\Business\UserBusinessFacade;
use App\Component\User\Business\UserBusinessFacadeInterface;
use App\DataFixtures\UserFixture;
use App\DataTransferObject\UserDataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserManagerTest extends KernelTestCase
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

    public function testDelete(): void
    {
        $dataFromDb = $this->userFacade->getUserById(2);
        self::assertInstanceOf(UserDataProvider::class, $dataFromDb);

        $this->userFacade->delete($dataFromDb);
        self::assertNull($this->userFacade->getUserById(2));
    }

    public function testSave(): void
    {
        $userDataProvider = new UserDataProvider();
        $userDataProvider->setUsername('Filip');
        $userDataProvider->setPassword('e');
        $userDataProvider->setUserrole('Customer');

        $actualData = $this->userFacade->save($userDataProvider);
        $dataFromDb = $this->userFacade->getUserByNameAndPassword('Filip', 'e');

        self::assertSame($actualData->getId(), $dataFromDb->getId());
        self::assertSame($actualData->getUsername(), $dataFromDb->getUsername());
        self::assertSame($actualData->getPassword(), $dataFromDb->getPassword());
        self::assertSame($actualData->getUserrole(), $dataFromDb->getUserrole());

        $dataFromDb = $this->userFacade->getUserById($actualData->getId());
        self::assertSame($actualData->getId(), $dataFromDb->getId());
        self::assertSame('Filip', $dataFromDb->getUsername());
        self::assertSame('e', $dataFromDb->getPassword());
        self::assertSame('Customer', $dataFromDb->getUserrole());
    }

    public function testSaveUpdate(): void
    {
        $userDataProvider = new UserDataProvider();
        $userDataProvider->setId(1);
        $userDataProvider->setUsername('Filip');
        $userDataProvider->setPassword('e');
        $userDataProvider->setUserrole('Customer');

        $actualData = $this->userFacade->save($userDataProvider);
        $dataFromDb = $this->userFacade->getUserByNameAndPassword('Filip', 'e');

        self::assertSame($actualData->getId(), $dataFromDb->getId());
        self::assertSame($actualData->getUsername(), $dataFromDb->getUsername());
        self::assertSame($actualData->getPassword(), $dataFromDb->getPassword());
        self::assertSame($actualData->getUserrole(), $dataFromDb->getUserrole());

        $dataFromDb = $this->userFacade->getUserById($actualData->getId());
        self::assertSame($actualData->getId(), $dataFromDb->getId());
        self::assertSame('Filip', $dataFromDb->getUsername());
        self::assertSame('e', $dataFromDb->getPassword());
        self::assertSame('Customer', $dataFromDb->getUserrole());
    }
}
