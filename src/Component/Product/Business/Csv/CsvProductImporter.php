<?php declare(strict_types=1);

namespace App\Component\Product\Business\Csv;

use App\Component\Product\Business\Csv\Mapper\CsvMapper;
use App\DataTransferObject\CsvProductDataProvider;
use App\DataTransferObject\ProductDataProvider;
use App\Service\Csv\CsvImporter;

class CsvProductImporter
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
     * @return CsvProductDataProvider[]
     * @throws \League\Csv\Exception
     */
    public function loadDataAsCsvDto(string $path): array
    {
        $csvDtoList = [];
        $products = $this->csvImporter->loadCsvData($path);

        foreach ($products as $product) {
            $csvDtoList[] = $this->csvMapper->mapIteratorToCsvProductDataProvider($product, new CsvProductDataProvider());
        }
        return $csvDtoList;
    }

}