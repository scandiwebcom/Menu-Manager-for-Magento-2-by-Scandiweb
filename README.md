# Scandiweb_Menumanager

This module is used to create customized navigation for Magento 2.

## Installation

Run the following:

*Optional*, run only if "Core" module is not installed yet:
```
composer config repositories.module-core git git@bitbucket.org:scandiwebassets/core.git
composer require scandiweb/module-core:"dev-master as 0.1.0"
```

```
composer config repositories.module-menumanager git git@bitbucket.org:scandiwebassets/menu-manager-2.git
composer require scandiweb/module-menumanager:0.1.4
php -f bin/magento setup:upgrade
```

## Configuration

For configuration and more details you can visit [wiki](https://scandiweb.atlassian.net/wiki/display/MAG2/Scandi+Menu+Manager+2.0).

## Example on how-to add menu to the Magento store

```
<?xml version="1.0"?>
<page xmlns:xsi="http://www.w3.org/2001/XMLSchemainstance" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">
    <body>
        <move element="custom.topnav" destination="store.menu" after=""/>
        <referenceContainer name="page.top">
            <block class="Scandiweb\Menumanager\Block\Menu" name="custom.navigation" template="html/menu.phtml" ttl="3600">
                <arguments>
                    <argument name="identifier" xsi:type="string">main_navigation</argument>
                </arguments>
            </block>
        </referenceContainer>
    </body>
</page>
```
