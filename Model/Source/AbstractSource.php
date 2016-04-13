<?php

namespace Scandi\Menumanager\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Scandi\Menumanager\Helper\Adminhtml\Data;

/**
 * @category Scandi
 * @package Scandi\Menumanager\Model\Item\Source
 * @author Dmitrijs Sitovs <dmitrijssh@majaslapa.lv / dsitovs@gmail.com>
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
