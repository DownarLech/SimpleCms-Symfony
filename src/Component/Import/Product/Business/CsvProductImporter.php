<?php declare(strict_types=1);

namespace App\Component\Import\Product\Business;

use App\Component\Import\Product\Business\Mapper\CsvProductMapper;
use App\DataTransferObject\CsvProductDataProvider;
use App\Service\Csv\CsvImporter;

class CsvProductImporter
{
    private CsvImporter $csvImporter;
    private CsvProductMapper $csvProductMapper;

    /**
     * CsvProductImporter constructor.
     * @param CsvImporter $csvImporter
     * @param CsvProductMapper $csvProductMapper
     */
    public function __construct(CsvImporter $csvImporter, CsvProductMapper $csvProductMapper)
    {
        $this->csvImporter = $csvImporter;
        $this->csvProductMapper = $csvProductMapper;
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
            $csvDtoList[] = $this->csvProductMapper->mapIteratorToCsvProductDataProvider($product, new CsvProductDataProvider());
        }
        return $csvDtoList;
    }

}