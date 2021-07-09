<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\Product\Business\Csv;

use App\Component\Category\Business\CategoryBusinessFacade;
use App\Component\Category\Business\CategoryBusinessFacadeInterface;
use App\Component\Product\Business\Csv\CsvCategoryImporter;
use App\Component\Product\Business\Csv\CsvProductImporter;
use App\Component\Product\Business\ProductBusinessFacade;
use App\Component\Product\Business\ProductBusinessFacadeInterface;
use App\DataFixtures\CategoryFixture;
use App\DataFixtures\ProductFixture;
use App\DataTransferObject\CategoryDataProvider;
use App\DataTransferObject\CsvProductDataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CsvCategoryImporterTest extends KernelTestCase
{
    private CsvCategoryImporter $csvCategoryImporter;
    private ProductBusinessFacadeInterface $productBusinessFacade;
    private CategoryBusinessFacadeInterface $categoryBusinessFacade;
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

        $csvCategoryImporter = static::getContainer()->get(CsvCategoryImporter::class);
        $this->csvCategoryImporter = $csvCategoryImporter;

        $productBusinessFacade = static::getContainer()->get(ProductBusinessFacade::class);
        $this->productBusinessFacade = $productBusinessFacade;

        $categoryBusinessFacade = static::getContainer()->get(CategoryBusinessFacade::class);
        $this->categoryBusinessFacade = $categoryBusinessFacade;

        $categoryFixture = static::getContainer()->get(CategoryFixture::class);
        $this->categoryFixture = $categoryFixture;

        $productFixture = static::getContainer()->get(ProductFixture::class);
        $this->productFixture = $productFixture;

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
        $csvDtoList = $this->csvCategoryImporter->loadDataAsCsvDto($path);

        $arraySavedCategories = $this->categoryFixture->saveInDbReturnDto($csvDtoList);

        $categoriesFromDB = $this->categoryBusinessFacade->getCategoryList();

        self::assertCount(4, $categoriesFromDB);

        foreach ($arraySavedCategories as $categoryCsvDto) {
            $categoryFromDb = $this->categoryBusinessFacade->getCategoryById($categoryCsvDto->getId());
            self::assertSame($categoryCsvDto->getId(), $categoryFromDb->getId());
            self::assertSame($categoryCsvDto->getName(), $categoryFromDb->getName());
        }
    }

    /**
     * @throws \League\Csv\Exception
     */
    public function testUpdateSaveAsCsvDto(): void
    {
        $path = __DIR__ . '/../../../../CsvFile/dataInsert.csv';
        $csvDtoList = $this->csvCategoryImporter->loadDataAsCsvDto($path);

        $this->categoryFixture->saveInDbReturnDto($csvDtoList);

        $path = __DIR__ . '/../../../../CsvFile/dataUpdate.csv';
        $csvDtoList = $this->csvCategoryImporter->loadDataAsCsvDto($path);

        $arrayUpdatedCategories = $this->categoryFixture->saveInDbReturnDto($csvDtoList);

        $categoriesFromDB = $this->categoryBusinessFacade->getCategoryList();

        self::assertCount(5, $categoriesFromDB);

        foreach ($arrayUpdatedCategories as $categoryCsvDto) {
            $categoryFromDb = $this->categoryBusinessFacade->getCategoryById($categoryCsvDto->getId());
            self::assertSame($categoryCsvDto->getId(), $categoryFromDb->getId());
            self::assertSame($categoryCsvDto->getName(), $categoryFromDb->getName());
        }
    }


}
