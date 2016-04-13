<?php
namespace Scandi\Menumanager\Block\Adminhtml\Menu\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * @category Scandi
 * @package Scandi\Menumanager\Block\Adminhtml\Menu\Edit\Tab
 * @author Dmitrijs Sitovs <dmitrijssh@majaslapa.lv / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Menu create/edit form main tab
 *
 * Class General
 */
class General extends Generic implements TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Scandi\Menumanager\Helper\Adminhtml\Data $menumanagerHelper,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_menumanagerHelper = $menumanagerHelper;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Scandi\Menumanager\Model\Menu $model */
        $model = $this->_coreRegistry->registry('scandi_menumanager_menu');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getData('action'),
                    'method' => 'post',
                ]
            ]
        );

        $form->setHtmlIdPrefix('menu_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'menu[title]',
                'label' => __('Menu Title'),
                'title' => __('Menu Title'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'identifier',
            'text',
            [
                'name' => 'menu[identifier]',
                'label' => __('Menu Identifier'),
                'title' => __('Menu Identifier'),
                'required' => true,
                'class' => 'validate-xml-identifier'
            ]
        );

        $fieldset->addField(
            'css_class',
            'text',
            [
                'name' => 'menu[css_class]',
                'label' => __('Custom Menu CSS Class'),
                'title' => __('Custom Menu CSS Class'),
                'required' => false,
            ]
        );

        $fieldset->addField(
            'is_active',
            'select',
            [
                'name' => 'menu[is_active]',
                'label' => __('Menu Status'),
                'title' => __('Menu Status'),
                'required' => true,
                'options' => $this->_menumanagerHelper->getAvailableStatuses(),
            ]
        );

        $this->_addStoreRenderer($fieldset);

        if (!$model->getId()) {
            $model->setData('is_active', \Scandi\Menumanager\Helper\Adminhtml\Data::STATUS_ENABLED);
        }

        $this->_eventManager->dispatch('scandi_menumanager_menu_edit_tab_general_prepare_form', ['form' => $form]);

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Add store view multiselect field.
     *
     * @param $fieldset
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _addStoreRenderer(\Magento\Framework\Data\Form\Element\Fieldset $fieldset)
    {
        /** @var \Scandi\Menumanager\Model\Menu $model */
        $model = $this->_coreRegistry->registry('scandi_menumanager_menu');

        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'store_id',
                'multiselect',
                [
                    'label' => __('Store'),
                    'title' => __('Store'),
                    'values' => $this->_systemStore->getStoreValuesForForm(false, true),
                    'name' => 'menu[store_id]',
                    'required' => false
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );

            $field->setRenderer($renderer);

        } else {
            $fieldset->addField(
                'store_id',
                'hidden',
                ['name' => 'menu[store_id]', 'value' => $this->_storeManager->getStore(true)->getId()]
            );
        }

        if (!$model->getId()) {
            $model->setData('store_id', 0);
        }
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('General Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('General Information');
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
}
