<?php declare(strict_types=1);


namespace App\Component\Product\Business\Csv;


use App\Component\Product\Business\Csv\Mapper\CsvMapper;
use App\DataTransferObject\CategoryDataProvider;
use App\Service\Csv\CsvImporter;

class CsvCategoryImporter
{
    private CsvImporter $csvImporter;
    private CsvMapper $csvMapper;

    /**
     * CsvProductImporter constructor.
     * @param CsvImporter $csvImporter
     * @param CsvMapper $csvMapper
     */
    public function __construct(CsvImporter $csvImporter, CsvMapper $csvMapper)
    {
        $this->csvImporter = $csvImporter;
        $this->csvMapper = $csvMapper;
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
            $csvDtoList[] = $this->csvMapper->mapIteratorToCategoryDataProvider($product, new CategoryDataProvider());
        }
        return $csvDtoList;
    }


}