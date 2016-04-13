<?php
namespace Scandi\Menumanager\Controller\Adminhtml\Menu;

use Magento\Framework\Controller\ResultFactory;

/**
 * @category Scandi
 * @package Scandi\Menumanager\Controller\Adminhtml\Menu
 * @author Dmitrijs Sitovs <dmitrijssh@majaslapa.lv / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Class MassStatus
 */
class MassStatus extends AbstractMassAction
{
    const ADMIN_RESOURCE = 'Scandi_Menumanager::navigation_menu_save';

    /**
     * Item status
     *
     * @var bool
     */
    protected $status;

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $this->_setStatus();
        $selected = $this->getRequest()->getParam('selected');
        $excluded = $this->getRequest()->getParam('excluded');

        try {
            if (isset($excluded) && $excluded == 'false') {
                $this->updateStatusForAll();
            } elseif (!empty($selected)) {
                $this->updateStatusForSelected($selected);
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
     * set status value
     */
    protected function _setStatus()
    {
        $status = $this->getRequest()->getParam('status', 1);

        $this->status = $status;
    }

    /**
     * Set status to all
     *
     * @return void
     * @throws \Exception
     */
    protected function updateStatusForAll()
    {
        /** @var \Scandi\Menumanager\Model\ResourceModel\Menu\Collection $collection */
        $collection = $this->_objectManager->get($this->collection);
        $this->setStatus($collection);
    }

    /**
     * Set status to selected items
     *
     * @param array $selected
     * @return void
     * @throws \Exception
     */
    protected function updateStatusForSelected(array $selected)
    {
        /** @var \Scandi\Menumanager\Model\ResourceModel\Menu\Collection $collection */
        $collection = $this->_objectManager->get($this->collection);
        $collection->addFieldToFilter(static::ID_FIELD, ['in' => $selected]);
        $this->setStatus($collection);
    }

    /**
     * Set status to collection items
     *
     * @param \Scandi\Menumanager\Model\ResourceModel\Menu\Collection $collection
     * @return void
     */
    protected function setStatus(\Scandi\Menumanager\Model\ResourceModel\Menu\Collection $collection)
    {
        $count = 0;

        foreach ($collection as $menu) {
            /** @var \Scandi\Menumanager\Model\Menu $menu */
            $menu->setIsActive($this->status);
            $menu->save();
            ++$count;
        }

        $this->setSuccessMessage($count);
    }
}
