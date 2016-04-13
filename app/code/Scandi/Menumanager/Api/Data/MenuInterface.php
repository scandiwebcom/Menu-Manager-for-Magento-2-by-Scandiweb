<?php
namespace Scandi\Menumanager\Api\Data;

/**
 * @category Scandi
 * @package Scandi\Menumanager\Api\Data
 * @author Dmitrijs Sitovs <dmitrijssh@majaslapa.lv / dsitovs@gmail.com>
 * @copyright Copyright (c) 2015 Scandiweb, Ltd (http://scandiweb.com)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *
 * Interface MenuInterface
 */
interface MenuInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const MENU_ID = 'menu_id';
    const IDENTIFIER = 'identifier';
    const TITLE = 'title';
    const TYPE = 'type';
    const CSS_CLASS = 'css_class';
    const IS_ACTIVE = 'is_active';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get Identifier
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Get menu type
     *
     * @return string|null
     */
    public function getType();

    /**
     * Get menu css class
     *
     * @return mixed
     */
    public function getCssClass();

    /**
     * Is active
     *
     * @return bool|null
     */
    public function isActive();

    /**
     * Set ID
     *
     * @param int $id
     * @return \Scandi\Menumanager\Api\Data\MenuInterface
     */
    public function setId($id);

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return \Scandi\Menumanager\Api\Data\MenuInterface
     */
    public function setIdentifier($identifier);

    /**
     * Set title
     *
     * @param string $title
     * @return \Scandi\Menumanager\Api\Data\MenuInterface
     */
    public function setTitle($title);

    /**
     * Set menu type
     *
     * @param string $type
     * @return \Scandi\Menumanager\Api\Data\MenuInterface
     */
    public function setType($type);

    /**
     * Set menu css class
     *
     * @param string $class
     * @return \Scandi\Menumanager\Api\Data\MenuInterface
     */
    public function setCssClass($class);

    /**
     * Set is active
     *
     * @param int|bool $isActive
     * @return \Scandi\Menumanager\Api\Data\MenuInterface
     */
    public function setIsActive($isActive);
}
