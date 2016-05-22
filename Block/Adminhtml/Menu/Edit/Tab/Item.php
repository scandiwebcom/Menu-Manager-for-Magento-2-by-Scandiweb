<?php
namespace Scandiweb\Menumanager\Block\Adminhtml\Menu\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * @category Scandiweb
 * @package Scandiweb\Menumanager\Block\Adminhtml\Menu\Edit\Tab
 * @author Dmitrijs Sitovs <info@scandiweb.com / dmitrijssh@scandiweb.com / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Menu create/edit form item tab
 *
 * Class Item
 */
class Item extends Generic implements TabInterface
{
    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Assigned Menu Items');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Assigned Menu Items');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Get current menu model
     *
     * @return \Scandiweb\Menumanager\Model\Menu
     */
    public function getMenu()
    {
        return $this->_coreRegistry->registry('scandiweb_menumanager_menu');
    }

    /**
     * @return bool
     */
    public function canShowItemGrid()
    {
        return $this->getMenu() && $this->getMenu()->getId();
    }

    /**
     * @return string
     */
    public function getNewItemUrl()
    {
        $params = ['menu_id' => $this->getRequest()->getParam('menu_id')];

        return $this->getUrl('*/item/edit', $params);
    }
}
