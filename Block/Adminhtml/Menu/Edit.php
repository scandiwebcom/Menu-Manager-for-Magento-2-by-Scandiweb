<?php
namespace Scandiweb\Menumanager\Block\Adminhtml\Menu;

use Magento\Backend\Block\Widget\Form\Container;

/**
 * @category Scandiweb
 * @package Scandiweb\Menumanager\Block\Adminhtml\Menu
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
        $this->_objectId = 'menu_id';
        $this->_blockGroup = 'Scandiweb_Menumanager';
        $this->_controller = 'adminhtml_menu';

        $saveUrl = $this->getUrl('*/*/save', ['_current' => true, 'active_tab' => 'general_section']);

        $this->setFormActionUrl($saveUrl);

        $this->buttonList->remove('reset');

        parent::_construct();

        if ($this->_isAllowedAction('Scandiweb_Menumanager::navigation_menu_save')) {
            $this->buttonList->update('save', 'label', __('Save Menu'));
            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => [
                                'event' => 'saveAndContinueEdit',
                                'target' => '#edit_form'
                            ],
                        ],
                    ]
                ],
                -100
            );
        } else {
            $this->buttonList->remove('save');
        }

        if ($this->_isAllowedAction('Scandiweb_Menumanager::navigation_menu_delete')) {
            $this->buttonList->update('delete', 'label', __('Delete Menu'));
        } else {
            $this->buttonList->remove('delete');
        }
    }

    /**
     * Retrieve text for header element depending on loaded menu
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        $menu = $this->_coreRegistry->registry('scandiweb_menumanager_menu');

        if ($menu->getId()) {
            return __("Edit Menu '%1'", $this->escapeHtml($menu->getTitle()));
        } else {
            return __('New Menu');
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
        return $this->getUrl('menu/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
    }
}
