<?php
namespace Scandi\Menumanager\Controller\Adminhtml\Menu;

use Magento\Backend\App\Action;

/**
 * @category Scandi
 * @package Scandi\Menumanager\Controller\Adminhtml\Menu
 * @author Dmitrijs Sitovs <dmitrijssh@majaslapa.lv / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Class Edit
 */
class Edit extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Scandi_Menumanager::navigation_menu_save';

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Scandi_Menumanager::navigation_menu')
            ->addBreadcrumb(__('Menu Manager'), __('Menu Manager'))
            ->addBreadcrumb(__('Manage Menus'), __('Manage Menus'));

        return $resultPage;
    }

    /**
     * Edit Menu
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('menu_id');
        $model = $this->_objectManager->create('Scandi\Menumanager\Model\Menu');

        if ($id) {
            $model->load($id);

            if (!$model->getId()) {
                $this->messageManager->addError(__('This menu no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);

        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('scandi_menumanager_menu', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Menu') : __('New Menu'),
            $id ? __('Edit Menu') : __('New Menu')
        );

        $resultPage->getConfig()->getTitle()->prepend(__('Menumanager Menus'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('New Menu'));

        return $resultPage;
    }
}
