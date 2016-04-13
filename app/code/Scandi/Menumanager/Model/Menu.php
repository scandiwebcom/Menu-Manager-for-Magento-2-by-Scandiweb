<?php

namespace Scandi\Menumanager\Model;

use Scandi\Menumanager\Api\Data\MenuInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * @category Scandi
 * @package Scandi\Menumanager\Model
 * @author Dmitrijs Sitovs <dmitrijssh@majaslapa.lv / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Class Menu
 */
class Menu extends \Magento\Framework\Model\AbstractModel implements MenuInterface, IdentityInterface
{
    /**
     * CMS page cache tag
     */
    const CACHE_TAG = 'scandi_menumanager_menu';

    /**
     * @var string
     */
    protected $_cacheTag = 'scandi_menumanager_menu';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'scandi_menumanager_menu';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Scandi\Menumanager\Model\ResourceModel\Menu');
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->getData(self::MENU_ID);
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->getData(self::IDENTIFIER);
    }

    /**
     * @return string|null
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * @return mixed
     */
    public function getCssClass()
    {
        return $this->getData(self::CSS_CLASS);
    }

    /**
     * @return bool|null
     */
    public function isActive()
    {
        return (bool) $this->getData(self::IS_ACTIVE);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return \Scandi\Menumanager\Api\Data\MenuInterface
     */
    public function setId($id)
    {
        return $this->setData(self::MENU_ID, $id);
    }

    /**
     * Set URL Key
     *
     * @param string $identifier
     * @return \Scandi\Menumanager\Api\Data\MenuInterface
     */
    public function setIdentifier($identifier)
    {
        return $this->setData(self::IDENTIFIER, $identifier);
    }

    /**
     * Set title
     *
     * @param string $title
     * @return \Scandi\Menumanager\Api\Data\MenuInterface
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Set menu type
     *
     * @param string $type
     * @return \Scandi\Menumanager\Api\Data\MenuInterface
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * @param string $class
     * @return \Scandi\Menumanager\Api\Data\MenuInterface
     */
    public function setCssClass($class)
    {
        return $this->setData(self::CSS_CLASS, $class);
    }

    /**
     * Set is active
     *
     * @param int|bool $is_active
     * @return \Scandi\Menumanager\Api\Data\MenuInterface
     */
    public function setIsActive($is_active)
    {
        return $this->setData(self::IS_ACTIVE, $is_active);
    }
}
