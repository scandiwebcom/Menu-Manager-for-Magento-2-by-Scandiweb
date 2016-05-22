<?php
namespace Scandiweb\Menumanager\Model\ResourceModel\Menu;

use Magento\Store\Model\Store;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;


/**
 * @category Scandiweb
 * @package Scandiweb\Menumanager\Model\ResourceModel\Menu
 * @author Dmitrijs Sitovs <info@scandiweb.com / dmitrijssh@scandiweb.com / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Class Collection
 */
class Collection extends AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Scandiweb\Menumanager\Model\Menu', 'Scandiweb\Menumanager\Model\ResourceModel\Menu');
    }

    /**
     * Add store filter to menu collection
     *
     * @param   int | Store $store
     * @param   bool $withAdmin
     *
     * @return  \Scandiweb\Menumanager\Model\ResourceModel\Menu\Collection
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if ($store instanceof Store) {
            $store = [$store->getId()];
        }

        if (!is_array($store)) {
            $store = [$store];
        }

        if ($withAdmin) {
            $store[] = 0;
        }

        $this->addFilter('store_id', ['in' => $store], 'public');

        return $this;
    }

    /**
     * Join store relation table data if store filter is used
     *
     * @return \Scandiweb\Menumanager\Model\ResourceModel\Menu\Collection
     */
    protected function _renderFiltersBefore()
    {
        if ($this->getFilter('store_id')) {
            $this->getSelect()->join(
                ['store_table' => $this->getTable('scandiweb_menumanager_menu_store')],
                'main_table.menu_id = store_table.menu_id',
                []
            )->group('main_table.menu_id');
        }

        parent::_renderFiltersBefore();
    }

    /**
     * Convert items array to array for select options
     *
     * return items array
     * array(
     *      $index => array(
     *          'value' => mixed
     *          'label' => mixed
     *      )
     * )
     *
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     *
     * @return array
     */
    protected function _toOptionArray($valueField = 'menu_id', $labelField = 'title', $additional = [])
    {
        $res = [];

        $additional['value'] = $valueField;
        $additional['label'] = $labelField;

        foreach ($this as $item) {
            $data = [];

            foreach ($additional as $code => $field) {

                if ($code == 'label') {
                    $isActive = (bool) $item->getData('is_active');
                    $data[$code] = $item->getData($field);

                    if (!$isActive) {
                        $data[$code] = $data[$code] . __(' (Inactive)');
                    }

                } else {
                    $data[$code] = $item->getData($field);
                }
            }

            $res[] = $data;
        }

        return $res;
    }
}
