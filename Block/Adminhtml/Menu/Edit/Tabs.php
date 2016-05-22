<?php
namespace Scandiweb\Menumanager\Block\Adminhtml\Menu\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

/**
 * @category Scandiweb
 * @package Scandiweb\Menumanager\Block\Adminhtml\Menu\Edit\Tab
 * @author Dmitrijs Sitovs <info@scandiweb.com / dmitrijssh@scandiweb.com / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Menu create/edit page left navigation
 *
 * Class Tabs
 */
class Tabs extends WidgetTabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('scandiweb_menumanager_menu_form');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Menu Information'));
    }
}
