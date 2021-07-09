<?php declare(strict_types=1);

namespace App\Component\Category\Business;

use App\Component\Category\Persistence\CategoryManagerInterface;
use App\Component\Category\Persistence\CategoryReaderInterface;
use App\DataTransferObject\CategoryDataProvider;

class CategoryBusinessFacade implements CategoryBusinessFacadeInterface
{
    private CategoryManagerInterface $categoryManager;
    private CategoryReaderInterface $categoryReader;

    /**
     * CategoryBusinessFacade constructor.
     * @param CategoryManagerInterface $categoryManager
     * @param CategoryReaderInterface $categoryReader
     */
    public function __construct(
        CategoryManagerInterface $categoryManager,
        CategoryReaderInterface $categoryReader
    )
    {
        $this->categoryManager = $categoryManager;
        $this->categoryReader = $categoryReader;
    }


    public function delete(CategoryDataProvider $categoryDataProvider): void
    {
        $this->categoryManager->delete($categoryDataProvider);
    }

    public function save(CategoryDataProvider $categoryDataProvider): CategoryDataProvider
    {
        return $this->categoryManager->save($categoryDataProvider);
    }

    /**
     * @return CategoryDataProvider[]
     */
    public function getCategoryList(): array
    {
        return $this->categoryReader->getCategoryList();
    }

    public function getCategoryById(int $id): ?CategoryDataProvider
    {
        return $this->categoryReader->getCategoryById($id);
    }

    public function getCategoryByName(string $name): ?CategoryDataProvider
    {
        return $this->categoryReader->getCategoryByName($name);
    }
}