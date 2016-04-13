<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Scandi\Menumanager\Block\Adminhtml\Item\Renderer;

/**
 * Adminhtml parent item renderer
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Parentitem extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * @param \Magento\Backend\Block\Context $context
     * @param \Scandi\Menumanager\Model\ItemFactory $itemFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Scandi\Menumanager\Model\ItemFactory $itemFactory,
        array $data = []
    ) {
        $this->_itemFactory = $itemFactory;
        parent::__construct($context, $data);
    }

    /**
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        return $row->getParentTitle() ? $row->getParentTitle() : __('Root');
    }
}
