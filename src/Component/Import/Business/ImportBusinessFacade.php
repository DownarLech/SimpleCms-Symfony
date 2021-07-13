<?php declare(strict_types=1);

namespace App\Component\Import\Business;


use App\Component\Import\Business\Category\Model\MakeImportCategoryInterface;
use App\Component\Import\Business\Product\Model\MakeImportProductInterface;
use App\DataTransferObject\CategoryDataProvider;
use App\DataTransferObject\CsvProductDataProvider;
use League\Csv\Exception;

class ImportBusinessFacade implements ImportBusinessFacadeInterface
{
    private MakeImportProductInterface $makeImportProduct;
    private MakeImportCategoryInterface $makeImportCategory;

    /**
     * ImportBusinessFacade constructor.
     * @param MakeImportProductInterface $makeImportProduct
     * @param MakeImportCategoryInterface $makeImportCategory
     */
    public function __construct(
        MakeImportProductInterface $makeImportProduct,
        MakeImportCategoryInterface $makeImportCategory
    )
    {
        $this->makeImportProduct = $makeImportProduct;
        $this->makeImportCategory = $makeImportCategory;
    }

    /**
     * @param string $path
     * @return CsvProductDataProvider[]
     * @throws \League\Csv\Exception
     */
    public function saveProductsFromCsvFile(string $path): array
    {
        return $this->makeImportProduct->saveProductsFromCsvFile($path);
    }

    /**
     * @param string $path
     * @return CategoryDataProvider[]
     * @throws Exception
     */
    public function saveCategoriesFromCsvFile(string $path): array
    {
        return $this->makeImportCategory->saveCategoriesFromCsvFile($path);
    }

}