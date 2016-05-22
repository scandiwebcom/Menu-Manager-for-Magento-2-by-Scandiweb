<?php
namespace Scandiweb\Menumanager\Helper\Adminhtml;

use Magento\Backend\App\ConfigInterface;
use Magento\Captcha\Model\CaptchaFactory;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Filesystem;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManager;

/**
 * @category Scandiweb
 * @package Scandiweb\Menumanager\Helper\Adminhtml
 * @author Dmitrijs Sitovs <info@scandiweb.com / dmitrijssh@scandiweb.com / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Class Data
 */
class Data extends \Magento\Captcha\Helper\Data
{
    /**
     * Menu/Item's Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**
     * Item's open types
     */
    const TYPE_SAME_WINDOW = 0;
    const TYPE_NEW_WINDOW = 1;

    /**
     * Item's URL types
     */
    const TYPE_CUSTOM_URL = 0;
    const TYPE_CMS_PAGE = 1;
    const TYPE_CATEGORY = 2;

    /**
     * @var ConfigInterface
     */
    protected $_backendConfig;

    protected $_categoryCollection;
    protected $_categoryCollectionClass = \Magento\Catalog\Model\ResourceModel\Category\Collection::class;

    protected $_menuCollection;
    protected $_menuCollectionClass = \Scandiweb\Menumanager\Model\ResourceModel\Menu\Collection::class;

    protected $_pageCollection;
    protected $_pageCollectionClass = \Magento\Cms\Model\ResourceModel\Page\Collection::class;

    /**
     * @param Context $context
     * @param StoreManager $storeManager
     * @param Filesystem $filesystem
     * @param CaptchaFactory $factory
     * @param ConfigInterface $backendConfig
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        StoreManager $storeManager,
        Filesystem $filesystem,
        CaptchaFactory $factory,
        ConfigInterface $backendConfig,
        Registry $registry
    ) {
        $this->_coreRegistry = $registry;
        $this->_backendConfig = $backendConfig;
        parent::__construct($context, $storeManager, $filesystem, $factory);
    }

    /**
     * @param bool $addEmptyField
     *
     * @return array
     */
    public function getCategoriesAsOptions($addEmptyField = true)
    {
        $collection = $this->_getCategoryCollection();
        $categories = [];

        if ($addEmptyField) {
            $categories[] = array(
                'value' => '',
                'label' => __('-- Please Select --')
            );
        }

        foreach ($collection as $category) {
            $name = $category->getName();
            $urlPath = $category->getUrlPath();

            if (isset($name) && isset($urlPath)) {
                $prefix = '';
                for ($i = 2; $i < $category->getLevel(); $i++) {
                    $prefix = $prefix . "-";
                }

                $categories[] = [
                    'value' => $category->getId(),
                    'label' => $prefix . ' ' . __($category->getName())
                ];
            }
        }

        return $categories;
    }

    /**
     * @return mixed
     */
    protected function _getCategoryCollection()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        if (!$this->_categoryCollection) {
            $collection = $objectManager->create($this->_categoryCollectionClass);
            $collection->addAttributeToSelect('name');
            $collection->addAttributeToSelect('url_path');
            $collection->addAttributeToSelect('level');

            $this->_categoryCollection = $collection;
        }

        return $this->_categoryCollection;
    }

    /**
     * @param bool|true $addEmptyField
     *
     * @return array
     */
    public function getMenusAsOptions($addEmptyField = true)
    {
        $collection = $this->_getMenuCollection();
        $menus = [];

        if ($addEmptyField) {
            $menus[] = array(
                'value' => '',
                'label' => __('-- Please Select --')
            );
        }

        foreach ($collection as $menu) {
            $name = $menu->getName();
            $urlPath = $menu->getUrlPath();

            if (isset($name) && isset($urlPath)) {
                $suffix = '';

                if (!$menu->getIsActive()) {
                    $suffix = __('(Inactive)');
                }

                $menus[] = [
                    'value' => $menu->getId(),
                    'label' => __($menu->getName()) . ' ' . $suffix
                ];
            }
        }

        return $menus;
    }

    /**
     * @return mixed
     */
    protected function _getMenuCollection()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        if (!$this->_menuCollection) {
            $collection = $objectManager->create($this->_menuCollectionClass);

            $this->_menuCollection = $collection;
        }

        return $this->_categoryCollection;
    }

    /**
     * @param bool|true $addEmptyField
     *
     * @return array
     */
    public function getCmsPagesAsOptions($addEmptyField = true)
    {
        $collection = $this->_getCmsPageCollection();
        $pages = [];

        if ($addEmptyField) {
            $pages[] = array(
                'value' => '',
                'label' => __('-- Please Select --')
            );
        }

        foreach ($collection as $page) {
            $name = $page->getTitle();
            $identifier = $page->getIdentifier();

            if (isset($name) && isset($identifier)) {
                $suffix = '';

                if (!$page->getIsActive()) {
                    $suffix = __('(Inactive)');
                }

                $pages[] = [
                    'value' => $identifier,
                    'label' => __($name) . ' ' . $suffix
                ];
            }
        }

        return $pages;
    }

    /**
     * @return mixed
     */
    protected function _getCmsPageCollection()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        if (!$this->_pageCollection) {
            /**
             * @var $collection \Magento\Cms\Model\ResourceModel\Page\Collection
             */
            $collection = $objectManager->create($this->_pageCollectionClass);
            $collection->addFieldToSelect('title');
            $collection->addFieldToSelect('identifier');
            $collection->addFieldToSelect('is_active');

            $this->_pageCollection = $collection;
        }

        return $this->_pageCollection;
    }

    /**
     * Prepare menu's statuses.
     * Available event scandiweb_menumanager_menu_get_available_statuses to customize statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [
            self::STATUS_ENABLED => __('Enabled'),
            self::STATUS_DISABLED => __('Disabled'),
        ];
    }

    /**
     * Prepare available item open types
     *
     * @return array
     */
    public function getOpenTypes()
    {
        return [
            self::TYPE_SAME_WINDOW => __('Same Window'),
            self::TYPE_NEW_WINDOW => __('New Window'),
        ];
    }

    /**
     * Prepare available item url types
     *
     * @return array
     */
    public function getUrlTypes()
    {
        return [
            self::TYPE_CUSTOM_URL => __('Custom URL'),
            self::TYPE_CMS_PAGE => __('CMS Page'),
            self::TYPE_CATEGORY => __('Category'),
        ];
    }

    /**
     * Load Menu from request
     *
     * @param string $idFieldName
     *
     * @return \Scandiweb\Menumanager\Model\Menu $model
     */
    public function initMenu($idFieldName = 'menu_id')
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $model = $objectManager->create('Scandiweb\Menumanager\Model\Menu');

        if ($menuId = (int)$this->_getRequest()->getParam($idFieldName, false)) {
            $model->load($menuId);
        }

        if (!$this->_coreRegistry->registry('scandiweb_menumanager_menu')) {
            $this->_coreRegistry->register('scandiweb_menumanager_menu', $model);
        }

        return $model;
    }

    /**
     * Init menu item model
     *
     * @param string $idFieldName
     *
     * @return mixed
     */
    public function initItem($idFieldName = 'item_id')
    {
        if ($registryItem = $this->_coreRegistry->registry('scandiweb_menumanager_item')) {
            return $registryItem;
        }

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $model = $objectManager->create('Scandiweb\Menumanager\Model\Item');

        if ($menuId = (int)$this->_getRequest()->getParam($idFieldName)) {
            $model->load($menuId);
        }

        $this->_coreRegistry->register('scandiweb_menumanager_item', $model);

        return $model;
    }
}
