<?php declare(strict_types=1);


namespace App\Component\Import\Category\Business\Model;


use App\Component\Category\Business\CategoryBusinessFacadeInterface;
use App\Component\Import\Category\Business\CsvCategoryImporter;
use App\DataTransferObject\CategoryDataProvider;
use League\Csv\Exception;

class MakeImportCategory implements MakeImportCategoryInterface
{
    private CategoryBusinessFacadeInterface $categoryBusinessFacade;
    private CsvCategoryImporter $csvCategoryImporter;

    /**
     * MakeImportCategory constructor.
     * @param CategoryBusinessFacadeInterface $categoryBusinessFacade
     * @param CsvCategoryImporter $csvCategoryImporter
     */
    public function __construct(
        CategoryBusinessFacadeInterface $categoryBusinessFacade,
        CsvCategoryImporter $csvCategoryImporter
    )
    {
        $this->categoryBusinessFacade = $categoryBusinessFacade;
        $this->csvCategoryImporter = $csvCategoryImporter;
    }


    /**
     * @param string $path
     * @return CategoryDataProvider[]
     * @throws Exception
     */
    public function saveCategoriesFromCsvFile(string $path): array
    {
        $categoryDtoListFromCsv = $this->csvCategoryImporter->loadDataAsCsvDto($path);

        return $this->saveCategories($categoryDtoListFromCsv);
    }

    /**
     * @param array $csvDtoList
     * @return CategoryDataProvider[]
     */
    private function saveCategories(array $csvDtoList): array
    {
        $arraySavedCategories = [];

        foreach ($csvDtoList as $product) {
            $arraySavedCategories[] = $this->categoryBusinessFacade->save($product);
        }
        return $arraySavedCategories;
    }

}