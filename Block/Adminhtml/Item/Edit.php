<?php
namespace Scandiweb\Menumanager\Block\Adminhtml\Item;

use Magento\Backend\Block\Widget\Form\Container;

/**
 * @category Scandiweb
 * @package Scandiweb\Menumanager\Block\Adminhtml\Item
 * @author Dmitrijs Sitovs <info@scandiweb.com / dmitrijssh@scandiweb.com / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Class Edit
 */
class Edit extends Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize menu edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'item_id';
        $this->_blockGroup = 'Scandiweb_Menumanager';
        $this->_controller = 'adminhtml_item';

        $saveUrl = $this->getUrl('*/item/save', ['_current' => true]);
        $this->setFormActionUrl($saveUrl);

        parent::_construct();

        if ($menuId = $this->_coreRegistry->registry('scandiweb_menumanager_menuId')) {
            $this->buttonList->update('back', 'onclick',
                'setLocation(\'' . $this->getUrl('*/menu/edit', ['menu_id' => $menuId]) . '\')');
        } else {
            $this->buttonList->update('back', 'onclick',
                'setLocation(\'' . $this->getUrl('*/*') . '\')');
        }
    }

    /**
     * Retrieve text for header element depending on loaded menu
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        $menu = $this->_coreRegistry->registry('scandiweb_menumanager_item');

        if ($menu->getId()) {
            return __("Edit Menu Item '%1'", $this->escapeHtml($menu->getTitle()));
        } else {
            return __('Add Menu Item');
        }
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
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('menu/*/save', ['_current' => true]);
    }
}
