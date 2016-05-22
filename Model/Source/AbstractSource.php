<?php

namespace Scandiweb\Menumanager\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Scandiweb\Menumanager\Helper\Adminhtml\Data;

/**
 * @category Scandiweb
 * @package Scandiweb\Menumanager\Model\Item\Source
 * @author Dmitrijs Sitovs <info@scandiweb.com / dmitrijssh@scandiweb.com / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Class AbstractSource
 */
abstract class AbstractSource implements OptionSourceInterface
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * Constructor
     *
     * @param Data $menumanagerHelper
     */
    public function __construct(Data $menumanagerHelper)
    {
        $this->helper = $menumanagerHelper;
    }
}
