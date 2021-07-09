<?php
declare(strict_types=1);
namespace App\DataTransferObject;

/**
 * Auto generated data provider
 */
final class UserDataProvider extends \Xervice\DataProvider\Business\Model\DataProvider\AbstractDataProvider implements \Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $username = '';

    /** @var string */
    protected $password = '';

    /** @var string */
    protected $userrole;


    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @param int $id
     * @return UserDataProvider
     */
    public function setId(?int $id = null)
    {
        $this->id = $id;

        return $this;
    }


    /**
     * @return UserDataProvider
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
    public function getUsername(): string
    {
        return $this->username;
    }


    /**
     * @param string $username
     * @return UserDataProvider
     */
    public function setUsername(string $username = '')
    {
        $this->username = $username;

        return $this;
    }


    /**
     * @return UserDataProvider
     */
    public function unsetUsername()
    {
        $this->username = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasUsername()
    {
        return ($this->username !== null && $this->username !== []);
    }


    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }


    /**
     * @param string $password
     * @return UserDataProvider
     */
    public function setPassword(string $password = '')
    {
        $this->password = $password;

        return $this;
    }


    /**
     * @return UserDataProvider
     */
    public function unsetPassword()
    {
        $this->password = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasPassword()
    {
        return ($this->password !== null && $this->password !== []);
    }


    /**
     * @return string
     */
    public function getUserrole(): string
    {
        return $this->userrole;
    }


    /**
     * @param string $userrole
     * @return UserDataProvider
     */
    public function setUserrole(string $userrole)
    {
        $this->userrole = $userrole;

        return $this;
    }


    /**
     * @return UserDataProvider
     */
    public function unsetUserrole()
    {
        $this->userrole = null;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasUserrole()
    {
        return ($this->userrole !== null && $this->userrole !== []);
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
            'allownull' => true,
            'default' => '0',
            'type' => 'int',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'username' =>
          array (
            'name' => 'username',
            'allownull' => false,
            'default' => '\'\'',
            'type' => 'string',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'password' =>
          array (
            'name' => 'password',
            'allownull' => false,
            'default' => '\'\'',
            'type' => 'string',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
          'userrole' =>
          array (
            'name' => 'userrole',
            'allownull' => false,
            'default' => '',
            'type' => 'string',
            'is_collection' => false,
            'is_dataprovider' => false,
            'isCamelCase' => false,
          ),
        );
    }
}
