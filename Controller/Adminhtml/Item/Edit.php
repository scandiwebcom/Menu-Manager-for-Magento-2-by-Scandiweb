<?php
namespace Scandiweb\Menumanager\Controller\Adminhtml\Item;

use Magento\Backend\App\Action;

/**
 * @category Scandiweb
 * @package Scandiweb\Menumanager\Controller\Adminhtml\Item
 * @author Dmitrijs Sitovs <info@scandiweb.com / dmitrijssh@scandiweb.com / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Class Edit
 */
class Edit extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Scandiweb_Menumanager::navigation_menu_item_save';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_registry = null;

    protected $_menumanagerHelper;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->_registry = $registry;
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Load item by ID from get parameter
     *
     * @return void
     */
    public function execute()
    {

        $itemId = $this->getRequest()->getParam('item_id');
        $menuId = $this->getRequest()->getParam('menu_id');
        $item = $this->_objectManager->create('Scandiweb\Menumanager\Model\Item');

        if ($itemId) {
            $item->load($itemId);

            if (!$this->_validateItem($item) || !$this->_validateMenu($item)) {
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/menu/index/');
            }
        }

        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);

        if (!empty($data)) {
            $item->setData($data);
        }

        $this->_registry->register('scandiweb_menumanager_item', $item);
        $this->_registry->register('scandiweb_menumanager_menuId', $menuId);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Scandiweb_Menumanager::navigation_menu')
            ->addBreadcrumb(__('Menu Manager'), __('Menu Manager'))
            ->addBreadcrumb(__('Edit Item'), __('Edit Item'));

        $resultPage->getConfig()->getTitle()->prepend(__('Menumanager Items'));
        $resultPage->getConfig()->getTitle()
            ->prepend(__('Edit Item'));

        return $resultPage;
    }

    /**
     * @param \Scandiweb\Menumanager\Model\Item $item
     *
     * @return bool
     */
    protected function _validateMenu(\Scandiweb\Menumanager\Model\Item $item)
    {
        $menuId = $this->getRequest()->getParam('menu_id');

        if (!$item->getMenuId() || $item->getMenuId() !== $menuId) {
            $this->messageManager->addError(__('There was an error during menu validation.'));

            return false;
        }

        return true;
    }

    /**
     * @param \Scandiweb\Menumanager\Model\Item $item
     *
     * @return bool
     */
    protected function _validateItem(\Scandiweb\Menumanager\Model\Item $item)
    {
        if (!$item->getId()) {
            $this->messageManager->addError(__('This item no longer exists.'));
            /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */

            return false;
        }

        return true;
    }
}
