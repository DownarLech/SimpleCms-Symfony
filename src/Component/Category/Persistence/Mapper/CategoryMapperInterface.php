<?php declare(strict_types=1);

namespace App\Component\Category\Persistence\Mapper;

use App\DataTransferObject\CategoryDataProvider;
use App\Entity\Category;

interface CategoryMapperInterface
{
    public function mapEntityToDataProvider(Category $category, CategoryDataProvider $categoryDataProvider): CategoryDataProvider;

    public function mapDataProviderToEntity(CategoryDataProvider $categoryDataProvider, Category $category): Category;

}