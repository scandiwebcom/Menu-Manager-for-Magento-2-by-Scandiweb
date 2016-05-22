<?php
namespace Scandiweb\Menumanager\Controller\Adminhtml\Item;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;

/**
 * @category Scandiweb
 * @package Scandiweb\Menumanager\Controller\Adminhtml\Item
 * @author Dmitrijs Sitovs <info@scandiweb.com / dmitrijssh@scandiweb.com / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Class Delete
 */
class Delete extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Scandiweb_Menumanager::navigation_menu_item_delete';

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('item_id', false);
        $params = [];

        if ($id) {
            /** @var \Scandiweb\Menumanager\Model\Item $model */
            $model = $this->_objectManager->create('Scandiweb\Menumanager\Model\Item');

            $model->load($id);

            $this->_eventManager->dispatch(
                'scandiweb_menumanager_item_prepare_delete',
                ['menu' => $model, 'request' => $this->getRequest()]
            );

            try {
                $model->delete();
                $this->messageManager->addSuccess(__('Menu item was successfully deleted.'));

            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while deleting menu item.'));
            }
        } else {
            $this->messageManager->addError(__('Something went wrong while deleting menu item.'));
        }

        if ($menuId = $this->getRequest()->getParam('menu_id')) {
            $params['menu_id'] = $menuId;
            $params['active_tab'] = 'item_section';
        }

        /**
         * set different active tab if it's set
         */
        if ($activeTab = $this->getRequest()->getParam('active_tab')) {
            $params['active_tab'] = $activeTab;
        }

        return $resultRedirect->setPath('*/menu/edit', $params);
    }
}
