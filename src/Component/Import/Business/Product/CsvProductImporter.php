<?php declare(strict_types=1);

namespace App\Component\Import\Business\Product;

use App\Component\Import\Business\Product\Mapper\CsvProductMapperInterface;
use App\DataTransferObject\CsvProductDataProvider;
use App\Service\Csv\CsvImporter;

class CsvProductImporter implements CsvProductImporterInterface
{
    private CsvImporter $csvImporter;
    private CsvProductMapperInterface $csvProductMapper;

    /**
     * CsvProductImporter constructor.
     * @param CsvImporter $csvImporter
     * @param CsvProductMapperInterface $csvProductMapper
     */
    public function __construct(CsvImporter $csvImporter, CsvProductMapperInterface $csvProductMapper)
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