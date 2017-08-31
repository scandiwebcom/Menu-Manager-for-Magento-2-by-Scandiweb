<?php
/**
 * Scandiweb_Menumanager
 *
 * @category    Scandiweb
 * @package     Scandiweb_Menumanager
 * @author      Raivis Dejus <info@scandiweb.com>
 * @copyright   Copyright (c) 2017 Scandiweb, Ltd (https://scandiweb.com)
 */
namespace Scandiweb\Menumanager\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Scandiweb\Menumanager\Model\ResourceModel\Menu\CollectionFactory;

/**
 * Class Menu
 */
class Menu implements OptionSourceInterface
{

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * Constructor
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $noMenu = [
            "no-menu" => [
                "label" => __("No menu"),
                "value" => "",
            ]
        ];
        $allMenus = $this->collectionFactory->create()->toOptionArray();

        return array_merge($noMenu, $allMenus);
    }
}
