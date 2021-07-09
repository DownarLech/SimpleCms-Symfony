<?php declare(strict_types=1);

namespace App\Tests\Unit\DataFixtures;

use App\DataFixtures\ProductFixture;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductFixtureTest extends KernelTestCase
{
    private ProductFixture $productFixture;
    private ProductRepository $productRepository;
    private ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $productFixture = static::getContainer()->get(ProductFixture::class);
        $this->productFixture = $productFixture;

        $productRepository = static::getContainer()->get(ProductRepository::class);
        $this->productRepository = $productRepository;
    }


    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function testLoad(): void
    {
        $this->productFixture->truncateTable($this->entityManager);
        self::assertCount(0, $this->productRepository->findAll());

        $this->productFixture->load($this->entityManager);
        self::assertCount(4, $this->productRepository->findAll());

        $this->productFixture->load($this->entityManager);
        self::assertCount(4, $this->productRepository->findAll());
    }

    public function testLoadUpdate(): void
    {
        $this->productFixture->truncateTable($this->entityManager);
        self::assertCount(0, $this->productRepository->findAll());

        $this->productFixture->load($this->entityManager);
        self::assertCount(4, $this->productRepository->findAll());

        $this->productFixture->loadUpdate($this->entityManager);
        self::assertCount(5, $this->productRepository->findAll());
    }


}
