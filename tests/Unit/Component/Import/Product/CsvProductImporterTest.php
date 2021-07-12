<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\Import\Product;

use App\Component\Import\Product\Business\CsvProductImporter;
use App\Component\Import\Product\Business\CsvProductImporterInterface;
use App\DataFixtures\CategoryFixture;
use App\DataFixtures\ProductFixture;
use App\DataTransferObject\CsvProductDataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class CsvProductImporterTest extends KernelTestCase
{
    private CsvProductImporterInterface $csvProductImporter;
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
    public function testLoadDataAsCsvDto(): void
    {
        $path = __DIR__ . '/../../../CsvFile/dataInsert.csv';

        $productDtoListFromCsv = $this->csvProductImporter->loadDataAsCsvDto($path);

        self::assertCount(6, $productDtoListFromCsv);

        foreach ($productDtoListFromCsv as $csvDto) {
            self::assertInstanceOf(CsvProductDataProvider::class, $csvDto);
        }
    }
}
