<?php
namespace Scandiweb\Menumanager\Controller\Adminhtml\Menu;

use Magento\Framework\Controller\ResultFactory;

/**
 * @category Scandiweb
 * @package Scandiweb\Menumanager\Controller\Adminhtml\Menu
 * @author Dmitrijs Sitovs <info@scandiweb.com / dmitrijssh@scandiweb.com / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Class MassDelete
 */
class MassDelete extends AbstractMassAction
{
    const ADMIN_RESOURCE = 'Scandiweb_Menumanager::navigation_menu_delete';

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $selected = $this->getRequest()->getParam('selected');
        $excluded = $this->getRequest()->getParam('excluded');

        try {
            if (isset($excluded) && $excluded == 'false') {
                $this->deleteAll();
            } elseif (!empty($selected)) {
                $this->deleteSelected($selected);
            } else {
                $this->messageManager->addError(__('Please select item(s).'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath(static::REDIRECT_URL);
    }

    /**
     * Delete all
     *
     * @return void
     * @throws \Exception
     */
    protected function deleteAll()
    {
        /** @var \Scandiweb\Menumanager\Model\ResourceModel\Menu\Collection $collection */
        $collection = $this->_objectManager->get($this->collection);

        $this->setSuccessMessage($this->delete($collection));
    }

    /**
     * Delete selected items
     *
     * @param array $selected
     * @return void
     * @throws \Exception
     */
    protected function deleteSelected(array $selected)
    {
        /** @var \Scandiweb\Menumanager\Model\ResourceModel\Menu\Collection $collection */
        $collection = $this->_objectManager->get($this->collection);
        $collection->addFieldToFilter(static::ID_FIELD, ['in' => $selected]);

        $this->setSuccessMessage($this->delete($collection));
    }

    /**
     * Delete collection items
     *
     * @param \Scandiweb\Menumanager\Model\ResourceModel\Menu\Collection $collection
     * @return int
     */
    protected function delete(\Scandiweb\Menumanager\Model\ResourceModel\Menu\Collection $collection)
    {
        $count = 0;
        foreach ($collection as $menu) {
            /** @var \Scandiweb\Menumanager\Model\Menu $menu */
            $menu->delete();
            ++$count;
        }

        return $count;
    }
}
