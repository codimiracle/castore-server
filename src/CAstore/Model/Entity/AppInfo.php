<?php
namespace CAstore\Model\Entity;

use Deline\Model\Entity\Entity;

class AppInfo implements Entity
{

    /**
     * App Id
     *
     * @var int
     */
    public $id;

    /**
     * App Content Id
     * @var int
     */
    public $contentId;
    
    /**
     * App 显示名称
     *
     * @var string
     */
    public $title;

    /**
     * App 名称
     *
     * @var string
     */
    public $name;

    /**
     * App 描述
     *
     * @var string
     */
    public $description;

    /**
     * App 包名
     *
     * @var string
     */
    public $package;

    /**
     * App 平台
     * 不要产生 Windows 平台以及其他平台。
     * <ul>
     * <li>android-x86</li>
     * <li>android-x86_64</li>
     * <li>android-arm</li>
     * <li>android-arm64</li>
     * <li>android-aarch</li>
     * <li>android-aarch64</li>
     * </ul>
     *
     * @var string
     */
    public $platform;

    /**
     * App 开发者
     *
     * @var string;
     */
    public $developer;

    /**
     * App 版本
     *
     * @var string
     */
    public $version;

    /** @var string */
    public $createdTime;
    
    /** @var string */
    public $updatedTime;
    /**
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }


    
    
    /**
     * @return number
     */
    public function getContentId()
    {
        return $this->contentId;
    }

    /**
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     *
     * @return string
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     *
     * @param string $package
     */
    public function setPackage($package)
    {
        $this->package = $package;
    }

    /**
     *
     * @return string
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     *
     * @param string $platform
     */
    public function setPlatform($platform)
    {
        $this->platform = $platform;
    }

    /**
     *
     * @return string
     */
    public function getDeveloper()
    {
        return $this->developer;
    }

    /**
     *
     * @param string $developer
     */
    public function setDeveloper($developer)
    {
        $this->developer = $developer;
    }

    /**
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     *
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }
    /**
     * @return string
     */
    public function getCreatedtTime()
    {
        return $this->createdtTime;
    }

    /**
     * @return string
     */
    public function getUpdatedTime()
    {
        return $this->updatedTime;
    }

    /**
     * @param string $createdtTime
     */
    public function setCreatedtTime($createdtTime)
    {
        $this->createdtTime = $createdtTime;
    }

    /**
     * @param string $updatedTime
     */
    public function setUpdatedTime($updatedTime)
    {
        $this->updatedTime = $updatedTime;
    }

}