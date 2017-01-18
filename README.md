# Scandiweb_Menumanager

This module is used to create customized navigation for Magento 2.

## Installation

Run the following:

*Optional*, run only if "Core" module is not installed yet:
```
composer config repositories.module-core git https://github.com/scandiwebcom/Scandiweb-Assets-Core.git
composer require scandiweb/module-core:"dev-master as 0.1.0"
```

```
composer config repositories.module-menumanager git https://github.com/scandiwebcom/Menu-Manager-for-Magento-2-by-Scandiweb.git
composer require scandiweb/module-menumanager:0.1.5
php -f bin/magento setup:upgrade
```

## Configuration

For configuration and more details you can visit [wiki](https://github.com/scandiwebcom/Menu-Manager-for-Magento-2-by-Scandiweb/wiki).

## Example on how-to add menu to the Magento store

You will need to update your theme’s default.xml in order to replace the main navigation with the newly created menu. In  default.xml please add the following (replace “identifier” parameter with your Menu Identifier):

So for example, if your Menu Identifier is 'cool-menu':

```
<?xml version="1.0"?>
<page xmlns:xsi="http://www.w3.org/2001/XMLSchemainstance" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">
    <body>
        <move element="custom.topnav" destination="store.menu" after=""/>
        <referenceContainer name="page.top">
            <block class="Scandiweb\Menumanager\Block\Menu" name="custom.navigation" template="html/menu.phtml" ttl="3600">
                <arguments>
                    <argument name="identifier" xsi:type="string">cool-menu</argument>
                </arguments>
            </block>
        </referenceContainer>
    </body>
</page>
```

"edit-item-page" branch was created to change functionality how menu items can be changed.
With opening edit form with ajax popup there are 2 issues:
1. Sometimes on first page load (clean cache and cookies) ajax is not trigerred and when you click
on edit link nothing happens, you need to reload page once more.
2. When you change in pager items qty to show per page, for example, from 20 to 50, for new 30 items 
 ajax is not triggered and you need to reload page. 
