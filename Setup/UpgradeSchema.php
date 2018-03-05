<?php

namespace Scandiweb\Menumanager\Setup;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * @category Scandiweb
 * @package Scandiweb\Menumanager\Setup
 * @author Dmitrijs Sitovs <info@scandiweb.com / dmitrijssh@scandiweb.com / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Class InstallSchema
 */
class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * @var \Scandiweb\Menumanager\Model\Menu
     */
    protected $menu;

    /**
     * @var \Scandiweb\Menumanager\Model\Item
     */
    protected $item;

    /**
     * @var \Scandiweb\MenuManager\Model\MenuFactory
     */
    protected $menuFactory;

    /**
     * UpgradeSchema constructor.
     * @param \Scandiweb\Menumanager\Model\Menu $menu
     * @param \Scandiweb\Menumanager\Model\Item $item
     * @param \Scandiweb\MenuManager\Model\MenuFactory $menuFactory
     */
    public function __construct(
        \Scandiweb\Menumanager\Model\Menu $menu,
        \Scandiweb\Menumanager\Model\Item $item,
        \Scandiweb\MenuManager\Model\MenuFactory $menuFactory,
        \Scandiweb\Menumanager\Model\ItemFactory $itemFactory
    ) {
        $this->menu = $menu;
        $this->item = $item;
        $this->menuFactory = $menuFactory;
        $this->itemFactory = $itemFactory;
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();


        if(!$context->getVersion()) {
        }

        if (version_compare($context->getVersion(), '0.1.8') < 0) {

            $identifier = 'cool-menu';
            $this->createMenu('Cool menu', $identifier);
            $collection = $this->menuFactory->create()->getCollection()
                ->addFieldToFilter('identifier', $identifier);
            foreach($collection as $menu) {
                $id = $menu->getId();
            }
            $this->createMenuItem('menu-item-1', 'Menu item 1', $id);
            $this->createMenuItem('menu-item-2', 'Menu item 2', $id);
            $this->createMenuItem('scandiweb', 'Scandiweb', $id);
            $this->activateMenu($setup, $id);
        }

        $setup->endSetup();
    }

    /**
     * @param $menuTitle
     * @param $identifier
     */
    public function createMenu($menuTitle, $identifier) {
        $this->menu ->setIdentifier($identifier)
                    ->setTitle($menuTitle)
                    ->setIsActive('1')
                    ->save();
    }

    /**
     * @param $identifier
     * @param $title
     * @param $menuId
     */
    public function createMenuItem($identifier, $title, $menuId) {
        $item = $this->itemFactory->create();
        $item
            ->setIdentifier($identifier)
            ->setMenuId($menuId)
            ->setTitle($title)
            ->setOpenType(0)
            ->setUrlType(0)
            ->setIsActive(1)
            ->setParentId(0);
        switch ($identifier) {
            case 'menu-item-1':
                $item ->setUrl('#')
                            ->setPosition(0);
                break;
            case 'menu-item-2':
                $item ->setUrl('#')
                            ->setPosition(1);
                break;
            case 'scandiweb':
                $item ->setUrl('https://scandiweb.com/')
                            ->setPosition(2);
                break;
        }
        $item->save();
    }

    /**
     * @param $id
     * @param $setup
     */
    public function activateMenu($setup, $id) {
        $tableName = $setup->getTable('scandiweb_menumanager_menu_store');
        $mainTable = $setup->getTable('scandiweb_menumanager_menu');
        $connection = ObjectManager::getInstance()->get('Magento\Framework\App\ResourceConnection')->getConnection();
        $sql = "INSERT INTO " . $tableName . " (menu_id, store_id) values 
                ((select menu_id from " . $mainTable . " where " . $mainTable .".menu_id = " . $id . "), 0)";
        $connection->query($sql);
    }
}
