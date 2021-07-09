<?php

namespace App\Tests\Unit\Component\Product\Business\Csv;

use App\Component\Product\Business\Csv\CsvCategoryImporter;
use App\Component\Product\Business\Csv\CsvProductImporter;
use App\Component\Product\Business\ProductBusinessFacade;
use App\Component\Product\Business\ProductBusinessFacadeInterface;
use App\DataFixtures\CategoryFixture;
use App\DataFixtures\ProductFixture;
use App\DataTransferObject\CsvProductDataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CsvProductImporterTest extends KernelTestCase
{
    private CsvProductImporter $csvProductImporter;
    private CsvCategoryImporter $csvCategoryImporter;
    private ProductBusinessFacadeInterface $productBusinessFacade;
    private CategoryFixture $categoryFixture;
    private ProductFixture $productFixture;
    private ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $csvProductImporter = static::getContainer()->get(CsvProductImporter::class);
        $this->csvProductImporter = $csvProductImporter;

        $csvCategoryImporter = static::getContainer()->get(CsvCategoryImporter::class);
        $this->csvCategoryImporter = $csvCategoryImporter;

        $productBusinessFacade = static::getContainer()->get(ProductBusinessFacade::class);
        $this->productBusinessFacade = $productBusinessFacade;

        $productFixture = static::getContainer()->get(ProductFixture::class);
        $this->productFixture = $productFixture;

        $categoryFixture = static::getContainer()->get(CategoryFixture::class);
        $this->categoryFixture = $categoryFixture;

        $this->productFixture->truncateTable($this->entityManager);
        $this->categoryFixture->truncateTable($this->entityManager);

    }

    protected function tearDown(): void
    {
        $this->entityManager->close();
        $this->entityManager = null;

        parent::tearDown();
    }

    /**
     * @throws \League\Csv\Exception
     */
    public function testSaveAsCsvDto(): void
    {
        $path = __DIR__ . '/../../../../CsvFile/dataInsert.csv';

        $categoryDtoListFromCsv = $this->csvCategoryImporter->loadDataAsCsvDto($path);
        $this->categoryFixture->saveInDbReturnDto($categoryDtoListFromCsv);

        $productDtoListFromCsv = $this->csvProductImporter->loadDataAsCsvDto($path);
        $arraySavedProducts = $this->productFixture->saveInDbReturnDto($productDtoListFromCsv);

        $productListFromDb = $this->productBusinessFacade->getProductList();

        self::assertCount(6, $productListFromDb);

        foreach ($arraySavedProducts as $csvDto) {
            $productFromDb = $this->productBusinessFacade->getProductById($csvDto->getId());
            self::assertSame($csvDto->getId(), $productFromDb->getId());
            self::assertSame($csvDto->getName(), $productFromDb->getName());
            self::assertSame($csvDto->getDescription(), $productFromDb->getDescription());
            self::assertSame($csvDto->getCategoryName(), $productFromDb->getCategoryName());
        }
    }

    /**
     * @throws \League\Csv\Exception
     */
    public function testUpdateSaveAsCsvDto(): void
    {
        $path = __DIR__ . '/../../../../CsvFile/dataInsert.csv';

        $categoryDtoListFromCsv = $this->csvCategoryImporter->loadDataAsCsvDto($path);
        $this->categoryFixture->saveInDbReturnDto($categoryDtoListFromCsv);

        $productDtoListFromCsv = $this->csvProductImporter->loadDataAsCsvDto($path);
        $this->productFixture->saveInDbReturnDto($productDtoListFromCsv);

        $path = __DIR__ . '/../../../../CsvFile/dataUpdate.csv';

        $categoryDtoListFromCsv = $this->csvCategoryImporter->loadDataAsCsvDto($path);
        $this->categoryFixture->saveInDbReturnDto($categoryDtoListFromCsv);

        $productDtoListFromCsv = $this->csvProductImporter->loadDataAsCsvDto($path);
        $arraySavedProducts = $this->productFixture->saveInDbReturnDto($productDtoListFromCsv);

        $productListFromDb = $this->productBusinessFacade->getProductList();

        self::assertCount(7, $productListFromDb);

        foreach ($arraySavedProducts as $csvDto) {
            $productFromDb = $this->productBusinessFacade->getProductById($csvDto->getId());
            self::assertSame($csvDto->getId(), $productFromDb->getId());
            self::assertSame($csvDto->getName(), $productFromDb->getName());
            self::assertSame($csvDto->getDescription(), $productFromDb->getDescription());
            self::assertSame($csvDto->getCategoryName(), $productFromDb->getCategoryName());
        }

    }


}
