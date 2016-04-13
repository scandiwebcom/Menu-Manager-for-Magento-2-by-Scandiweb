<?php
namespace Scandi\Menumanager\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * @category Scandi
 * @package Scandi\Menumanager\Setup
 * @author Dmitrijs Sitovs <dmitrijssh@majaslapa.lv / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Class InstallSchema
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $this->_installMenuTable($setup);
        $this->_installItemTable($setup);
        $this->_installMenuStoreTable($setup);

        $this->_addForeignKeys($setup);

        $setup->endSetup();
    }

    /**
     * Create table "scandi_menumanager_menu_store"
     *
     * @param SchemaSetupInterface $setup
     *
     * @throws \Zend_Db_Exception
     */
    protected function _installMenuTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()
            ->newTable($setup->getTable('scandi_menumanager_menu'))
            ->addColumn(
                'menu_id',
                Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Menu ID'
            )
            ->addColumn(
                'identifier',
                Table::TYPE_TEXT,
                100,
                ['nullable' => false]
            )
            ->addColumn(
                'title',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Menu Title'
            )
            ->addColumn(
                'css_class',
                Table::TYPE_TEXT,
                100,
                ['nullable' => true],
                'Custom CSS Class'
            )
            ->addColumn(
                'is_active',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Is Menu Active?'
            )
            ->addIndex(
                $setup->getIdxName('scandi_menumanager_menu', ['identifier']),
                ['identifier']
            )
            ->addIndex(
                $setup->getIdxName('scandi_menumanager_menu', ['menu_id']),
                ['menu_id']
            )
            ->setComment('Scandi Menumanager Menus');

        $setup->getConnection()->createTable($table);
    }

    /**
     * Create table "scandi_menumanager_item"
     *
     * @param SchemaSetupInterface $setup
     */
    protected function _installItemTable(SchemaSetupInterface $setup)
    {
        $installer = $setup;

        $table = $installer->getConnection()
            ->newTable($installer->getTable('scandi_menumanager_item'))
            ->addColumn(
                'item_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Item ID'
            )
            ->addColumn(
                'menu_id',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'unsigned' => true, 'default' => '0'],
                'Menu ID'
            )
            ->addColumn(
                'parent_id',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '0'],
                'Parent Item ID'
            )
            ->addColumn(
                'title',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Menu Title'
            )
            ->addColumn(
                'identifier',
                Table::TYPE_TEXT,
                100,
                ['nullable' => false],
                'Item identifier'
            )
            ->addColumn(
                'url',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true, 'default' => null],
                'Item URL'
            )
            ->addColumn(
                'open_type',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '0'],
                'Item Open Type'
            )
            ->addColumn(
                'url_type',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '0'],
                'Item URL type'
            )
            ->addColumn(
                'cms_page_identifier',
                Table::TYPE_TEXT,
                100,
                ['nullable' => true, 'default' => null],
                'Page String Identifier'
            )
            ->addColumn(
                'category_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => true, 'unsigned' => true, 'default' => null],
                'Category ID'
            )
            ->addColumn(
                'position',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '0'],
                'Item Position'
            )
            ->addColumn(
                'is_active',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Is Menu Active?'
            )
            ->addIndex(
                $installer->getIdxName('scandi_menumanager_item', ['identifier']),
                ['identifier']
            )
            ->setComment('Scandi Menumanager Menu Items');

        $installer->getConnection()->createTable($table);
    }

    /**
     * Create table "scandi_menumanager_menu_store"
     *
     * @param SchemaSetupInterface $setup
     */
    protected function _installMenuStoreTable(SchemaSetupInterface $setup)
    {
        $installer = $setup;
        /** @var $connection \Magento\Framework\DB\Adapter\Pdo\Mysql */
        $connection = $setup->getConnection();

        $table = $connection
            ->newTable(
                $installer->getTable('scandi_menumanager_menu_store')
            )
            ->addColumn(
                'menu_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Menu ID'
            )
            ->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Store ID'
            )
            ->addIndex(
                $installer->getIdxName('scandi_menumanager_menu_store', ['store_id']),
                ['store_id']
            )
            ->addIndex(
                $installer->getIdxName('scandi_menumanager_menu_store', ['menu_id']),
                ['menu_id']
            )
            ->setComment('MenuManager Menu To Store Linkage Table');

        $installer->getConnection()->createTable($table);
    }

    /**
     * apply all the necessary foreign keys
     *
     * @param SchemaSetupInterface $setup
     */
    protected function _addForeignKeys(SchemaSetupInterface $setup)
    {
        $installer = $setup;
        /** @var $connection \Magento\Framework\DB\Adapter\Pdo\Mysql */
        $connection = $setup->getConnection();

        /**
         * apply foreign key for menu
         */
        $connection->addForeignKey(
            $installer->getFkName(
                'scandi_menumanager_item',
                'menu_id',
                'scandi_menumanager_menu',
                'menu_id'
            ),
            $installer->getTable('scandi_menumanager_item'),
            'menu_id',
            $installer->getTable('scandi_menumanager_menu'),
            'menu_id',
            Table::ACTION_CASCADE
        );

        /**
         * apply foreign key for category selection
         */
        $connection->addForeignKey(
            $installer->getFkName(
                'scandi_menumanager_item',
                'category_id',
                'catalog_category_entity',
                'entity_id'
            ),
            $installer->getTable('scandi_menumanager_item'),
            'category_id',
            $installer->getTable('catalog_category_entity'),
            'entity_id',
            Table::ACTION_SET_NULL
        );

        /**
         * apply foreign key for CMS page selection
         */
        $connection->addForeignKey(
            $installer->getFkName(
                'scandi_menumanager_item',
                'cms_page_identifier',
                'cms_page',
                'identifier'
            ),
            $installer->getTable('scandi_menumanager_item'),
            'cms_page_identifier',
            $installer->getTable('cms_page'),
            'identifier',
            Table::ACTION_SET_NULL
        );

        /**
         * apply foreign keys for Menu-Store mapping
         */
        $connection->addForeignKey(
            $installer->getFkName(
                'scandi_menumanager_menu_store',
                'menu_id',
                'scandi_menumanager_menu',
                'menu_id'
            ),
            $installer->getTable('scandi_menumanager_menu_store'),
            'menu_id',
            $installer->getTable('scandi_menumanager_menu'),
            'menu_id',
            Table::ACTION_CASCADE
        );

        $connection->addForeignKey(
            $installer->getFkName(
                'scandi_menumanager_menu_store',
                'store_id',
                'store',
                'store_id'
            ),
            $installer->getTable('scandi_menumanager_menu_store'),
            'store_id',
            $installer->getTable('store'),
            'store_id',
            Table::ACTION_CASCADE
        );
    }
}
