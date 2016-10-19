<?php
namespace Scandiweb\Menumanager\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * @category Scandiweb
 * @package Scandiweb\Menumanager\Model\ResourceModel
 * @author Dmitrijs Sitovs <info@scandiweb.com / dmitrijssh@scandiweb.com / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Class Item
 */
class Item extends AbstractDb
{
    /**
     * Construct
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param string|null $resourcePrefix
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('scandiweb_menumanager_item', 'item_id');
    }

    /**
     * Load an object using 'identifier' field if there's no field specified and value is not numeric
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param mixed $value
     * @param string $field
     *
     * @return $this
     */
    public function load(\Magento\Framework\Model\AbstractModel $object, $value, $field = null)
    {
        if (!is_numeric($value) && is_null($field)) {
            $field = 'identifier';
        }

        return parent::load($object, $value, $field);
    }

    /**
     * Validate data before saving
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if (!$this->_isValidMenu($object)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('This menu does not exists anymore.')
            );
        }

        if (!$this->_isValidParentItem($object)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Menu item can not be parent to itself.')
            );
        }

        if (!$object->getIdentifier()) {
            $object->setIdentifier('menu_' . $object->getMenuId() . '_item_' . date('Y_m_d_H_i_s'));
        }

        $this->_processUrlSave($object);

        return parent::_beforeSave($object);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param \Scandiweb\Menumanager\Model\Menu $object
     *
     * @return \Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        $select->limit(1);

        return $select;
    }

    /**
     * @param $menuId
     *
     * @return \Magento\Framework\DB\Select
     */
    protected function _getMenuSelect($menuId)
    {
        $select = $this->getConnection()->select()->from(
            ['menus' => $this->getTable('scandiweb_menumanager_menu')]
        )->where(
            'menus.menu_id = ?',
            $menuId
        )->limit(1);

        return $select;
    }

    /**
     * @param $object
     */
    protected function _processUrlSave($object)
    {
        switch ($object->getUrlType()) {
            case 0:
            default:
                $object->setData('cms_page_identifier', null);
                $object->setData('category_id', null);
                break;
            case 1:
                $object->setData('url', null);
                $object->setData('category_id', null);
                break;
            case 2:
                $object->setData('url', null);
                $object->setData('cms_page_identifier', null);
                break;
        }
    }

    /**
     * @param $itemId
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getItemSelect($itemId)
    {
        $select = $this->getConnection()->select()->from(
            ['items' => $this->getMainTable()]
        )->where(
            'items.item_id = ?',
            $itemId
        )->limit(1);

        return $select;
    }

    /**
     * @param $object
     *
     * @return bool|string
     */
    protected function _isValidMenu($object)
    {
        if (!$menuId = $object->getMenuId()) {
            return false;
        }

        $select = $this->_getMenuSelect($menuId);
        $menu = $this->getConnection()->fetchOne($select);

        return $menu ? true : false;
    }

    /**
     * @param $object
     *
     * @return bool
     */
    protected function _isValidParentItem($object)
    {
        if ($object->getId() && $object->getId() == $object->getParentId()) {
            return false;
        }

        return true;
    }
}
