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
composer require scandiweb/module-menumanager:0.1.9
php -f bin/magento setup:upgrade
```

## Configuration

For configuration and more details you can visit [wiki](https://github.com/scandiwebcom/Menu-Manager-for-Magento-2-by-Scandiweb/wiki).

## Example on how-to add menu to the Magento store

By default layout update is done in the module's view/frontend/layout/default.xml.

If you want to customize it in the theme you are using:

 - you will need to update your theme’s default.xml in order to replace the main navigation with the newly created menu; 
 - in  default.xml please add the following (replace “identifier” parameter with your Menu Identifier):

So for example, if your Menu Identifier is 'cool-menu':

```
<?xml version="1.0"?>
<page xmlns:xsi="http://www.w3.org/2001/XMLSchemainstance" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">
    <body>
        <referenceBlock name="catalog.topnav" remove="true"/>
        <referenceBlock name="store.menu">
            <block class="Scandiweb\Menumanager\Block\Menu" name="custom.navigation" template="html/menu.phtml" before="-" ttl="3600">
                <arguments>
                    <argument name="identifier" xsi:type="string">cool-menu</argument>
                </arguments>
            </block>
        </referenceBlock>
    </body>
</page>
```

- if default.xml in the theme is using - please remove dafult.xml inside the module.
