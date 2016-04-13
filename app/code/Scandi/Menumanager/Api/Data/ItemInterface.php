<?php
namespace Scandi\Menumanager\Api\Data;

/**
 * @category Scandi
 * @package Scandi\Menumanager\Api\Data
 * @author Dmitrijs Sitovs <dmitrijssh@majaslapa.lv / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Interface ItemInterface
 */
interface ItemInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ITEM_ID = 'item_id';
    const MENU_ID = 'menu_id';
    const PARENT_ID = 'parent_id';
    const IDENTIFIER = 'identifier';
    const URL = 'url';
    const OPEN_TYPE = 'open_type';
    const URL_TYPE = 'url_type';
    const CMS_PAGE_IDENTIFIER = 'cms_page_identifier';
    const CATEGORY_ID = 'category_id';
    const POSITION = 'position';
    const IS_ACTIVE = 'is_active';

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return mixed
     */
    public function getMenuId();

    /**
     * @return mixed
     */
    public function getParentId();

    /**
     * @return mixed
     */
    public function getIdentifier();

    /**
     * @return mixed
     */
    public function getUrl();

    /**
     * @return mixed
     */
    public function getOpenType();

    /**
     * @return mixed
     */
    public function getUrlType();

    /**
     * @return mixed
     */
    public function getCmsPageIdentifier();

    /**
     * @return mixed
     */
    public function getCategoryId();

    /**
     * @return mixed
     */
    public function getPosition();

    /**
     * @return mixed
     */
    public function getIsActive();

    /**
     * @param $id
     *
     * @return mixed
     */
    public function setId($id);

    /**
     * @param $menuId
     *
     * @return mixed
     */
    public function setMenuId($menuId);

    /**
     * @param $parentId
     *
     * @return mixed
     */
    public function setParentId($parentId);

    /**
     * @param $identifier
     *
     * @return mixed
     */
    public function setIdentifier($identifier);

    /**
     * @param $url
     *
     * @return mixed
     */
    public function setUrl($url);

    /**
     * @param $openType
     *
     * @return mixed
     */
    public function setOpenType($openType);

    /**
     * @param $urlType
     *
     * @return mixed
     */
    public function setUrlType($urlType);

    /**
     * @param $identifier
     *
     * @return mixed
     */
    public function setCmsPageIdentifier($identifier);

    /**
     * @param $categoryId
     *
     * @return mixed
     */
    public function setCategoryId($categoryId);

    /**
     * @param $position
     *
     * @return mixed
     */
    public function setPosition($position);

    /**
     * @param $isActive
     *
     * @return mixed
     */
    public function setIsActive($isActive);
}
