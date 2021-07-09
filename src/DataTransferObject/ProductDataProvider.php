<?php
declare(strict_types=1);
namespace App\DataTransferObject;

/**
 * Auto generated data provider
 */
final class ProductDataProvider extends \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider implements \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $productName = '';

    /** @var string */
    protected $description = '';

    /** @var \App\DataTransferObject\CategoryDataProvider */
    protected $category_id;


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @param int $id
     * @return ProductDataProvider
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }


    /**
     * @return ProductDataProvider
     */
    public function unsetId()
    {
        $this->id = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasId()
    {
        return ($this->id !== null && $this->id !== []);
    }


    /**
     * @return string
     */
    public function getProductName(): string
    {
        return $this->productName;
    }


    /**
     * @param string $productName
     * @return ProductDataProvider
     */
    public function setProductName(string $productName = '')
    {
        $this->productName = $productName;

        return $this;
    }


    /**
     * @return ProductDataProvider
     */
    public function unsetProductName()
    {
        $this->productName = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasProductName()
    {
        return ($this->productName !== null && $this->productName !== []);
    }


    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }


    /**
     * @param string $description
     * @return ProductDataProvider
     */
    public function setDescription(string $description = '')
    {
        $this->description = $description;

        return $this;
    }


    /**
     * @return ProductDataProvider
     */
    public function unsetDescription()
    {
        $this->description = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasDescription()
    {
        return ($this->description !== null && $this->description !== []);
    }


    /**
     * @return \App\DataTransferObject\CategoryDataProvider
     */
    public function getCategory_id(): ?\App\DataTransferObject\CategoryDataProvider
    {
        return $this->category_id;
    }


    /**
     * @param \App\DataTransferObject\CategoryDataProvider $category_id
     * @return ProductDataProvider
     */
    public function setCategory_id(?\App\DataTransferObject\CategoryDataProvider $category_id = null)
    {
        $this->category_id = $category_id;

        return $this;
    }


    /**
     * @return ProductDataProvider
     */
    public function unsetCategory_id()
    {
        $this->category_id = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasCategory_id()
    {
        return ($this->category_id !== null && $this->category_id !== []);
    }


    /**
     * @param \App\DataTransferObject\CategoryDataProvider $Category
     * @return ProductDataProvider
     */
    public function addCategory(CategoryDataProvider $Category)
    {
        $this->category_id[] = $Category; return $this;
    }


    /**
     * @return array
     */
    protected function getElements(): array
    {
        return array (
          'id' =>
          array (
            'name' => 'id',
            'allownull' => false,
            'default' => '0',
            'type' => 'int',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'productName' =>
          array (
            'name' => 'productName',
            'allownull' => false,
            'default' => '\'\'',
            'type' => 'string',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'description' =>
          array (
            'name' => 'description',
            'allownull' => false,
            'default' => '\'\'',
            'type' => 'string',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'category_id' =>
          array (
            'name' => 'category_id',
            'allownull' => true,
            'default' => '',
            'type' => '\\App\\DataTransferObject\\CategoryDataProvider',
            'is_collection' => false,
            'is_dataprovider' => true,
            'isCamelCase' => false,
            'singleton' => 'Category',
            'singleton_type' => '\\App\\DataTransferObject\\CategoryDataProvider',
          ),
        );
    }
}
