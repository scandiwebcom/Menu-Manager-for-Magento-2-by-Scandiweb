<?php
namespace Scandi\Menumanager\Block\Adminhtml\Item;

use Magento\Backend\Block\Widget\Form\Container;

/**
 * @category Scandi
 * @package Scandi\Menumanager\Block\Adminhtml\Item
 * @author Dmitrijs Sitovs <dmitrijssh@majaslapa.lv / dsitovs@gmail.com>
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
        $this->_blockGroup = 'Scandi_Menumanager';
        $this->_controller = 'adminhtml_item';

        $saveUrl = $this->getUrl('*/item/save', ['_current' => true, 'active_tab' => 'item_section']);
        $this->setFormActionUrl($saveUrl);

        parent::_construct();
    }

    /**
     * Retrieve text for header element depending on loaded menu
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        $menu = $this->_coreRegistry->registry('scandi_menumanager_item');

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
