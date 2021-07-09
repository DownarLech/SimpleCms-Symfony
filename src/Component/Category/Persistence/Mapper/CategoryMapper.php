<?php declare(strict_types=1);

namespace App\Component\Category\Persistence\Mapper;


use App\DataTransferObject\CategoryDataProvider;
use App\Entity\Category;

class CategoryMapper implements CategoryMapperInterface
{
    public function mapArrayToDataProvider(array $categoryList, CategoryDataProvider $categoryDataProvider): CategoryDataProvider
    {
        $categoryDataProvider->setId((int)$categoryList['id']);
        $categoryDataProvider->setName($categoryList['name']);

        return $categoryDataProvider;
    }

    public function mapEntityToDataProvider(Category $category, CategoryDataProvider $categoryDataProvider): CategoryDataProvider
    {
        $categoryDataProvider->setId($category->getId());
        $categoryDataProvider->setName($category->getName());

        return $categoryDataProvider;
    }

    public function mapDataProviderToEntity(CategoryDataProvider $categoryDataProvider, Category $category): Category
    {
        $category->setName($categoryDataProvider->getName());

        return $category;
    }

}