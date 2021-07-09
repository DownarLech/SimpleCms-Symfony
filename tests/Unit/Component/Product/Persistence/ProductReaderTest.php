<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\Product\Persistence;

use App\Component\Product\Business\ProductBusinessFacade;
use App\Component\Product\Business\ProductBusinessFacadeInterface;
use App\DataFixtures\ProductFixture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class ProductReaderTest extends KernelTestCase
{
    private ProductBusinessFacadeInterface $productBusinessFacade;
    private ProductFixture $productFixture;
    private ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $productBusinessFacade  = static::getContainer()->get(ProductBusinessFacade::class);
        $this->productBusinessFacade = $productBusinessFacade ;

        $productFixture = static::getContainer()->get(ProductFixture::class);
        $this->productFixture = $productFixture;

        $this->productFixture->load($this->entityManager);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function testGetProductList(): void
    {
        $dataFromDb = $this->productBusinessFacade->getProductList();
        $dataGiven = $this->productFixture->getData();

        self::assertCount(4, $dataFromDb);
        self::assertSame(1, $dataFromDb[1]->getId());
        self::assertSame('Samsung', $dataFromDb[2]->getName());
        self::assertSame('lorem Dell X4', $dataFromDb[3]->getDescription());
        self::assertSame('004', $dataFromDb[1]->getProductCsvNumber());
        self::assertSame('laptop', $dataFromDb[3]->getCategoryName());


        foreach ($dataFromDb as $dataDb) {
            $data = $dataGiven[$dataDb->getId()-1];
            self::assertSame($dataDb->getName(), $data['name']);
            self::assertSame($dataDb->getDescription(), $data['description']);
            self::assertSame($dataDb->getProductCsvNumber(), $data['productCsvNumber']);
            self::assertSame($dataDb->getCategoryName(), $data['category']);
        }
    }

    public function testGetProductById(): void
    {
        $dataFromDb = $this->productBusinessFacade->getProductById(2);

        self::assertSame(2, $dataFromDb->getId());
        self::assertSame('Samsung', $dataFromDb->getName());
        self::assertSame('lorem Samsung A1', $dataFromDb->getDescription());
        self::assertSame('008', $dataFromDb->getProductCsvNumber());
        self::assertSame('smartphone', $dataFromDb->getCategoryName());
    }

    public function testGetProductByIdNotFound(): void
    {
        $dataFromDb = $this->productBusinessFacade->getProductById(0);

        self::assertNull($dataFromDb);
    }

    public function testGetProductByCsvNumber(): void
    {
        $dataFromDb = $this->productBusinessFacade->getProductByCsvNumber('077');

        self::assertSame(4, $dataFromDb->getId());
        self::assertSame('Lenovo', $dataFromDb->getName());
        self::assertSame('lorem Lenovo', $dataFromDb->getDescription());
        self::assertSame('077', $dataFromDb->getProductCsvNumber());
        self::assertSame('tablet', $dataFromDb->getCategoryName());
    }
}
