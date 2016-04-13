<?php
namespace Scandi\Menumanager\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * @category Scandi
 * @package Scandi\Menumanager\Model\ResourceModel
 * @author Dmitrijs Sitovs <dmitrijssh@majaslapa.lv / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Class Menu
 */
class Menu extends AbstractDb
{

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

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
        $this->_date = $date;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('scandi_menumanager_menu', 'menu_id');
    }

    /**
     * Validate data before saving
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->_setStoreIds($object);

        if (!$this->isValidMenuIdentifier($object)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Menu identifier contains capital letters or disallowed symbols.')
            );
        }

        if ($this->isNumericMenuIdentifier($object)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Menu identifier cannot be made of only numbers.')
            );
        }

        if (!$this->getIsUniqueMenuToStores($object)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('A menu identifier with the same properties already exists in one of the selected stores.')
            );
        }

        return parent::_beforeSave($object);
    }

    /**
     * Check if menu identifier is unique in store(s).
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return bool
     */
    public function getIsUniqueMenuToStores(\Magento\Framework\Model\AbstractModel $object)
    {
        $stores = (array)$object->getData('stores');

        if (!$stores) {
            return true;
        }

        $select = $this->getConnection()->select()
            ->from(['menu' => $this->getMainTable()])
            ->join(
                ['menu_stores' => $this->getTable('scandi_menumanager_menu_store')],
                'menu.menu_id = menu_stores.menu_id',
                []
            )
            ->where('menu.identifier = ?', $object->getData('identifier'));

        if ($stores && !in_array(0, $stores)) {
            $select->where('menu_stores.store_id IN (?)', $stores);
        }

        if ($object->getId()) {
            $select->where('menu.menu_id <> ?', $object->getId());
        }

        if ($this->getConnection()->fetchOne($select)) {
            return false;
        }

        return true;
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     */
    protected function _setStoreIds(\Magento\Framework\Model\AbstractModel $object)
    {
        $om = \Magento\Framework\App\ObjectManager::getInstance();
        $request = $om->get('Magento\Framework\App\RequestInterface');
        $menuData = $request->getParam('menu');

        if ($menuData && isset($menuData['store_id'])) {
            if (in_array(0, $menuData['store_id'])) {
                $object->setData('stores', [0]);
            } else {
                $object->setData('stores', $menuData['store_id']);
            }
        }
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();

        $table  = $this->getTable('scandi_menumanager_menu_store');

        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);

        if ($delete) {
            $where = [
                'menu_id = ?'     => (int) $object->getId(),
                'store_id IN (?)' => $delete
            ];

            $this->getConnection()->delete($table, $where);
        }

        if ($insert) {
            $data = [];

            foreach ($insert as $storeId) {
                $data[] = [
                    'menu_id'  => (int) $object->getId(),
                    'store_id' => (int) $storeId
                ];
            }

            $this->getConnection()->insertMultiple($table, $data);
        }
    }

    /**
     * Get store IDs to which menu is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupStoreIds($id)
    {
        $adapter = $this->getConnection();

        $select  = $adapter->select()
            ->from($this->getTable('scandi_menumanager_menu_store'), 'store_id')
            ->where('menu_id = :menu_id');

        $binds = [
            ':menu_id' => (int) $id
        ];

        return $adapter->fetchCol($select, $binds);
    }

    /**
     * Load an object using 'identifier' field if there's no field specified and value is not numeric
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param mixed $value
     * @param string $field
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
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param \Scandi\Menumanager\Model\Menu $object
     * @return \Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $stores = [
                (int) $object->getStoreId(),
                0
            ];

            $select->join(
                ['menu_store' => $this->getTable('scandi_menumanager_menu_store')],
                $this->getMainTable() . '.menu_id = menu_store.menu_id',
                ['store_id']
            )
                ->where('menu_store.store_id in (?) ', $stores)
                ->where('is_active = ?', 1)
                ->order('store_id DESC')
                ->limit(1);
        }

        return $select;
    }

    /**
     * Perform operations after object load - add stores data
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());

            $object->setData('store_id', $stores);
            $object->setData('stores', $stores);
        }

        return parent::_afterLoad($object);
    }

    /**
     * Retrieve load select with filter by identifier and activity
     *
     * @param string $identifier
     * @param int $isActive
     * @return \Magento\Framework\DB\Select
     */
    protected function _getLoadByUrlKeySelect($identifier, $isActive = null)
    {
        $select = $this->getConnection()->select()->from(
            ['menus' => $this->getMainTable()]
        )->where(
            'menus.identifier = ?',
            $identifier
        );

        if (!is_null($isActive)) {
            $select->where('menus.is_active = ?', $isActive);
        }

        return $select;
    }

    /**
     *  Check whether menu identifier is numeric
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return bool
     */
    protected function isNumericMenuIdentifier(\Magento\Framework\Model\AbstractModel $object)
    {
        return preg_match('/^[0-9]+$/', $object->getData('identifier'));
    }

    /**
     *  Check whether menu identifier is valid
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return bool
     */
    protected function isValidMenuIdentifier(\Magento\Framework\Model\AbstractModel $object)
    {
        return preg_match('/^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)?$/', $object->getData('identifier'));
    }
}
