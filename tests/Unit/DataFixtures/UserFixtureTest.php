<?php declare(strict_types=1);

namespace App\Tests\Unit\DataFixtures;

use App\DataFixtures\UserFixture;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserFixtureTest extends KernelTestCase
{
    private UserFixture $userFixture;
    private UserRepository $userRepository;
    private ?EntityManagerInterface $entityManager;
    //can I make so ?EntityManagerInterface with null

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

       // $userFixture = self::$container->get(UserFixture::class);
        // deprecated w symfony 5.3 I can change for symfony 5.2.* in composer.json

       // $container = $kernel->getContainer();
       // $userFixture = $container->get(UserFixture::class);

        $userFixture = static::getContainer()->get(UserFixture::class);
        $this->userFixture = $userFixture;

        $userRepository = static::getContainer()->get(UserRepository::class);
        $this->userRepository = $userRepository;

    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function testLoad(): void
    {
        $this->userFixture->truncateTable($this->entityManager);
        self::assertCount(0, $this->userRepository->findAll());

        $this->userFixture->load($this->entityManager);
        self::assertCount(3, $this->userRepository->findAll());

        $this->userFixture->load($this->entityManager);
        self::assertCount(3, $this->userRepository->findAll());
    }

    public function testLoadUpdate(): void
    {
        $this->userFixture->truncateTable($this->entityManager);
        self::assertCount(0, $this->userRepository->findAll());

        $this->userFixture->load($this->entityManager);
        self::assertCount(3, $this->userRepository->findAll());

        $this->userFixture->loadUpdate($this->entityManager);
        self::assertCount(4, $this->userRepository->findAll());
    }


}
