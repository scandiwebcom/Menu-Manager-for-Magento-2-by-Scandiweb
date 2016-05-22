<?php
namespace Scandiweb\Menumanager\Controller\Adminhtml\Menu;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;

/**
 * @category Scandiweb
 * @package Scandiweb\Menumanager\Controller\Adminhtml\Menu
 * @author Dmitrijs Sitovs <info@scandiweb.com / dmitrijssh@scandiweb.com / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Class Save
 */
class Save extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Scandiweb_Menumanager::navigation_menu_save';

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data && isset($data['menu'])) {
            /** @var \Scandiweb\Menumanager\Model\Menu $model */
            $model = $this->_objectManager->create('Scandiweb\Menumanager\Model\Menu');

            if ($id = $this->getRequest()->getParam('menu_id')) {
                $model->load($id);
            }

            $model->addData($data['menu']);

            $this->_eventManager->dispatch(
                'scandiweb_menumanager_menu_prepare_save',
                ['menu' => $model, 'request' => $this->getRequest()]
            );

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved this menu.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['menu_id' => $model->getId(), '_current' => true]);
                }

                return $resultRedirect->setPath('*/*/');

            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving menu.'));
            }

            $this->_getSession()->setFormData($data);

            return $resultRedirect->setPath('*/*/edit', ['menu_id' => $this->getRequest()->getParam('menu_id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
