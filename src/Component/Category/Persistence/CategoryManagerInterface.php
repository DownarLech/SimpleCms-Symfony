<?php declare(strict_types=1);


namespace App\Component\Category\Persistence;


use App\DataTransferObject\CategoryDataProvider;

interface CategoryManagerInterface
{
    public function delete(CategoryDataProvider $categoryDataProvider): void;

    public function save(CategoryDataProvider $categoryDataProvider): CategoryDataProvider;

}