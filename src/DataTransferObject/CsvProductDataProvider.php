<?php
declare(strict_types=1);
namespace App\DataTransferObject;

/**
 * Auto generated data provider
 */
final class CsvProductDataProvider extends \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider implements \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $name = '';

    /** @var string */
    protected $description = '';

    /** @var string */
    protected $productCsvNumber = '';

    /** @var string */
    protected $categoryName = '';


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @param int $id
     * @return CsvProductDataProvider
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }


    /**
     * @return CsvProductDataProvider
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
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * @param string $name
     * @return CsvProductDataProvider
     */
    public function setName(string $name = '')
    {
        $this->name = $name;

        return $this;
    }


    /**
     * @return CsvProductDataProvider
     */
    public function unsetName()
    {
        $this->name = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasName()
    {
        return ($this->name !== null && $this->name !== []);
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
     * @return CsvProductDataProvider
     */
    public function setDescription(string $description = '')
    {
        $this->description = $description;

        return $this;
    }


    /**
     * @return CsvProductDataProvider
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
     * @return string
     */
    public function getProductCsvNumber(): ?string
    {
        return $this->productCsvNumber;
    }


    /**
     * @param string $productCsvNumber
     * @return CsvProductDataProvider
     */
    public function setProductCsvNumber(?string $productCsvNumber = '')
    {
        $this->productCsvNumber = $productCsvNumber;

        return $this;
    }


    /**
     * @return CsvProductDataProvider
     */
    public function unsetProductCsvNumber()
    {
        $this->productCsvNumber = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasProductCsvNumber()
    {
        return ($this->productCsvNumber !== null && $this->productCsvNumber !== []);
    }


    /**
     * @return string
     */
    public function getCategoryName(): ?string
    {
        return $this->categoryName;
    }


    /**
     * @param string $categoryName
     * @return CsvProductDataProvider
     */
    public function setCategoryName(?string $categoryName = '')
    {
        $this->categoryName = $categoryName;

        return $this;
    }


    /**
     * @return CsvProductDataProvider
     */
    public function unsetCategoryName()
    {
        $this->categoryName = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasCategoryName()
    {
        return ($this->categoryName !== null && $this->categoryName !== []);
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
          'name' =>
          array (
            'name' => 'name',
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
          'productCsvNumber' =>
          array (
            'name' => 'productCsvNumber',
            'allownull' => true,
            'default' => '\'\'',
            'type' => 'string',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'categoryName' =>
          array (
            'name' => 'categoryName',
            'allownull' => true,
            'default' => '\'\'',
            'type' => 'string',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
        );
    }
}
