<?php
namespace Scandi\Menumanager\Controller\Adminhtml\Item;

/**
 * @category Scandi
 * @package Scandi\Menumanager\Controller\Adminhtml\Item
 * @author Dmitrijs Sitovs <dmitrijssh@majaslapa.lv / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Class AbstractMassAction
 */
class ItemGrid extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Scandi_Menumanager::navigation_menu';

    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $resultLayoutFactory;

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
        \Scandi\Menumanager\Helper\Adminhtml\Data $menumanagerHelper,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
    )
    {
        parent::__construct($context);
        $this->_menumanagerHelper = $menumanagerHelper;
        $this->_registry = $registry;
        $this->resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * Menu items grid action on 'Menu Items' tab
     * Load menu by ID from post data
     *
     * @return void
     */
    public function execute()
    {
        if (!$this->getRequest()->isAjax()) {
            return $this->_redirect('*/menu/index');
        }

        $menuId = $this->getRequest()->getParam('menu_id');
        $model = $this->_menumanagerHelper->initMenu();

        if (!$model->getId() && $menuId) {
            $this->messageManager->addError(__('This menu does not exist.'));
            $this->_redirect('adminhtml/menu/index');
            return;
        }

        $resultLayout = $this->resultLayoutFactory->create();
        $resultLayout->getLayout()->getBlock('scandi_menumanager_item_grid');

        return $resultLayout;
    }
}
