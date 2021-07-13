<?php declare(strict_types=1);


namespace App\Component\Import\Business\Category;

use App\Component\Import\Business\Category\Mapper\CsvCategoryMapper;
use App\DataTransferObject\CategoryDataProvider;
use App\Service\Csv\CsvImporter;

class CsvCategoryImporter implements CsvCategoryImporterInterface
{
    private CsvImporter $csvImporter;
    private CsvCategoryMapper $csvCategoryMapper;

    /**
     * CsvCategoryImporter constructor.
     * @param CsvImporter $csvImporter
     * @param CsvCategoryMapper $csvCategoryMapper
     */
    public function __construct(CsvImporter $csvImporter, CsvCategoryMapper $csvCategoryMapper)
    {
        $this->csvImporter = $csvImporter;
        $this->csvCategoryMapper = $csvCategoryMapper;
    }

    /**
     * @param string path to file.csv
     * @return CategoryDataProvider[]
     * @throws \League\Csv\Exception
     */
    public function loadDataAsCsvDto(string $path): array
    {
        $csvDtoList = [];
        $products = $this->csvImporter->loadCsvData($path);

        foreach ($products as $product) {
            $csvDtoList[] =
                $this->csvCategoryMapper->mapIteratorToCategoryDataProvider($product, new CategoryDataProvider());
        }
        return $csvDtoList;
    }
}