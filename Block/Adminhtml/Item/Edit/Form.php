<?php
namespace Scandiweb\Menumanager\Block\Adminhtml\Item\Edit;

use Magento\Backend\Block\Widget\Form\Generic;

/**
 * @category Scandiweb
 * @package Scandiweb\Menumanager\Block\Adminhtml\Menu
 * @author Dmitrijs Sitovs <info@scandiweb.com / dmitrijssh@scandiweb.com / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Adminhtml menu edit form
 *
 * Class Form
 */
class Form extends Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;

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
        \Scandiweb\Menumanager\Helper\Adminhtml\Data $menumanagerHelper,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_menumanagerHelper = $menumanagerHelper;
        $this->jsonEncoder = $jsonEncoder;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $saveUrl = $this->getUrl('*/item/save', ['_current' => true, 'active_tab' => 'item_section']);

        $this->setFormActionUrl($saveUrl);
        $this->setId('scandiweb_menumanager_item_form');
        $this->setDestElementId('item_form');
        $this->setTitle(__('Item Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Scandiweb\Menumanager\Model\Item $item */
        $item = $this->_menumanagerHelper->initItem();
        $menuId = $this->getRequest()->getParam('menu_id', null);

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'item_form',
                    'action' => $this->getData('action'),
                    'method' => 'post',
                ]
            ]
        );

        $form->setHtmlIdPrefix('item_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        $fieldset->addField('menu_id', 'hidden', ['name' => 'menu_id']);

        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Menu Item Title'),
                'title' => __('Menu Item Title'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'url_type',
            'select',
            [
                'label' => __('URL Type'),
                'title' => __('URL Type'),
                'name' => 'url_type',
                'data-action' => 'menu-item-url-type',
                'required' => true,
                'options' => $this->_menumanagerHelper->getUrlTypes(),
            ]
        );

        $fieldset->addField(
            'url',
            'text',
            [
                'name' => 'url',
                'label' => __('Custom URL'),
                'title' => __('Custom URL'),
                'required' => true,
            ]
        );

        $this->_addCategoryField($fieldset);
        $this->_addCmsPageSelect($fieldset);

        $fieldset->addField(
            'parent_id',
            'select',
            [
                'name'      => 'parent_id',
                'label'     => __('Parent'),
                'title'     => __('Parent'),
                'required'  => true,
                'options'   => $item->getCollection()
                    ->addMenuFilter($menuId)
                    ->excludeCurrentItem($item->getId())
                    ->toItemOptionArray(),
            ]
        );

        $fieldset->addField(
            'open_type',
            'select',
            [
                'label' => __('Open Type'),
                'title' => __('Open Type'),
                'name' => 'open_type',
                'required' => true,
                'options' => $this->_menumanagerHelper->getOpenTypes(),
            ]
        );

        $fieldset->addField(
            'is_active',
            'select',
            [
                'label' => __('Menu Item Status'),
                'title' => __('Menu Item Status'),
                'name' => 'is_active',
                'required' => true,
                'options' => $this->_menumanagerHelper->getAvailableStatuses(),
            ]
        );

        $fieldset->addField(
            'position',
            'text',
            [
                'name' => 'position',
                'label' => __('Menu Item Sort Order'),
                'title' => __('Menu Item Sort Order'),
                'required' => false,
            ]
        );

        if (!$item->getId()) {
            $item->setData('menu_id', $menuId);
            $item->setData('is_active', \Scandiweb\Menumanager\Helper\Adminhtml\Data::STATUS_ENABLED);
        }

        $form->setValues($item->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @param $fieldset
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _addCategoryField(\Magento\Framework\Data\Form\Element\Fieldset $fieldset)
    {
        $fieldset->addField(
            'category_id',
            'select',
            [
                'label' => __('Category'),
                'title' => __('Category'),
                'values' => $this->_menumanagerHelper->getCategoriesAsOptions(true),
                'name' => 'category_id',
                'required' => true
            ]
        );
    }

    protected function _addCmsPageSelect(\Magento\Framework\Data\Form\Element\Fieldset $fieldset)
    {
        $fieldset->addField(
            'cms_page_identifier',
            'select',
            [
                'label' => __('CMS Page'),
                'title' => __('CMS Page'),
                'values' => $this->_menumanagerHelper->getCmsPagesAsOptions(true),
                'name' => 'cms_page_identifier',
                'required' => true
            ]
        );
    }
}
