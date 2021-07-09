<?php declare(strict_types=1);

namespace App\Tests\Unit\DataFixtures;

use App\DataFixtures\CategoryFixture;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryFixtureTest extends KernelTestCase
{
    private CategoryFixture $categoryFixture;
    private CategoryRepository $categoryRepository;
    private ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $categoryFixture = static::getContainer()->get(CategoryFixture::class);
        $this->categoryFixture = $categoryFixture;

        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
        $this->categoryRepository = $categoryRepository;
    }


    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function testLoad(): void
    {
        $this->categoryFixture->truncateTable($this->entityManager);
        self::assertCount(0, $this->categoryRepository->findAll());

        $this->categoryFixture->load($this->entityManager);
        self::assertCount(3, $this->categoryRepository->findAll());

        $this->categoryFixture->load($this->entityManager);
        self::assertCount(3, $this->categoryRepository->findAll());
    }

    public function testLoadUpdateX(): void
    {
        $this->categoryFixture->truncateTable($this->entityManager);
        self::assertCount(0, $this->categoryRepository->findAll());

        $this->categoryFixture->load($this->entityManager);
        self::assertCount(3, $this->categoryRepository->findAll());

        $this->categoryFixture->loadUpdate($this->entityManager);
        self::assertCount(4, $this->categoryRepository->findAll());
    }
}
