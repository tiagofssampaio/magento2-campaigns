# Magento 2 Module TiagoSampaio Campaigns

    ``tiagosampaio/module-campaigns``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)

## Main Functionalities

1. Campaigns
   1. Activate and deactivate
   2. Add campaign title to product page and category page
   3. Have its own campaign listing page
   4. Campaigns description can render WYSIWYG or Page builder

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/TiagoSampaio`
 - Enable the module by running `php bin/magento module:enable TiagoSampaio_Campaigns`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Add the composer repository to the configuration by running `composer config repositories.magento2-campaigns git "https://github.com/tiagofssampaio/magento2-campaigns.git"`
 - Install the module composer by running `composer require tiagosampaio/module-campaigns`
 - enable the module by running `php bin/magento module:enable TiagoSampaio_Campaigns`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

## Configuration
Sidebar menu: TIAGOSAMPAIO > Campaigns


