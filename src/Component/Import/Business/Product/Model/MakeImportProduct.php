<?php declare(strict_types=1);

namespace App\Component\Import\Business\Product\Model;

use App\Component\Import\Business\Product\CsvProductImporter;
use App\Component\Product\Business\ProductBusinessFacadeInterface;
use App\DataTransferObject\CsvProductDataProvider;

class MakeImportProduct implements MakeImportProductInterface
{
    private ProductBusinessFacadeInterface $productBusinessFacade;
    private CsvProductImporter $csvProductImporter;

    /**
     * MakeImportProduct constructor.
     * @param ProductBusinessFacadeInterface $productBusinessFacade
     * @param CsvProductImporter $csvProductImporter
     */
    public function __construct(
        ProductBusinessFacadeInterface $productBusinessFacade,
        CsvProductImporter $csvProductImporter
    )
    {
        $this->productBusinessFacade = $productBusinessFacade;
        $this->csvProductImporter = $csvProductImporter;
    }


    /**
     * @param string $path
     * @return CsvProductDataProvider[]
     * @throws \League\Csv\Exception
     */
    public function saveProductsFromCsvFile(string $path): array
    {
        $productDtoListFromCsv = $this->csvProductImporter->loadDataAsCsvDto($path);
        return $this->saveProducts($productDtoListFromCsv);
    }

    /**
     * @param array $csvDtoList
     * @return CsvProductDataProvider[]
     */
    private function saveProducts(array $csvDtoList): array
    {
        $productsDtoList = [];

        foreach ($csvDtoList as $product) {
            $productsDtoList[] = $this->productBusinessFacade->save($product);
        }
        return $productsDtoList;
    }

}