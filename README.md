# Scandi_Menumanager

This module is used to create customized navigation for Magento 2.

## Installation

Run
```
composer config repositories.module-menumanager git git@bitbucket.org:scandiwebassets/menu-manager-2.git
composer require scandiwebassets/module-menumanager:0.1.1
php -f bin/magento setup:upgrade

Optional, if "Core" module is not installed yet:
composer config repositories.module-core git git@bitbucket.org:scandiwebassets/core.git
composer require scandiwebassets/module-core:dev-master
```

## Configuration

For configuration and more details you can visit [wiki](https://scandiweb.atlassian.net/wiki/display/MAG2/Scandi+Menu+Manager+2.0).
