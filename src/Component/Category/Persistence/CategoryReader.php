<?php declare(strict_types=1);

namespace App\Component\Category\Persistence;

use App\Component\Category\Persistence\Mapper\CategoryMapperInterface;
use App\DataTransferObject\CategoryDataProvider;
use App\Repository\CategoryRepository;

class CategoryReader implements CategoryReaderInterface
{
    private CategoryRepository $categoryRepository;
    private CategoryMapperInterface $categoryMapper;

    /**
     * CategoryReader constructor.
     * @param CategoryRepository $categoryRepository
     * @param CategoryMapperInterface $categoryMapper
     */
    public function __construct(CategoryRepository $categoryRepository, CategoryMapperInterface $categoryMapper)
    {
        $this->categoryRepository = $categoryRepository;
        $this->categoryMapper = $categoryMapper;
    }

    /**
     * @return CategoryDataProvider[]
     */
    public function getCategoryList(): array
    {
        $categoryList = [];
        $dataFromDb = $this->categoryRepository->findAll();

        foreach ($dataFromDb as $category) {
            $categoryList[$category->getId()] =
                $this->categoryMapper->mapEntityToDataProvider($category, new CategoryDataProvider());
        }
        return $categoryList;
    }

    public function getCategoryById(int $id): ?CategoryDataProvider
    {
        $categoryFromDb = $this->categoryRepository->findOneById($id);

        if (!$categoryFromDb) {
            return null;
        }
        return $this->categoryMapper->mapEntityToDataProvider($categoryFromDb, new CategoryDataProvider());
    }

    public function getCategoryByName(string $name): ?CategoryDataProvider
    {
        $categoryFromDb = $this->categoryRepository->findOneByName($name);

        if (!$categoryFromDb) {
            return null;
        }

        return $this->categoryMapper->mapEntityToDataProvider($categoryFromDb, new CategoryDataProvider());
    }

}