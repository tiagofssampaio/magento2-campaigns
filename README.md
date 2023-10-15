# Magento 2 Module TiagoSampaio Campaigns

    ``tiagosampaio/module-campaigns``

 - [Main Functionalities](#main-functionalities)
 - [Installation](#installation)
 - [Configuration](#configuration)
 - [Possible Improvements and Suggestions](#possible-improvements-and-suggestions)
 - [Notes](#notes)

## Main Functionalities

  - Activate and deactivate
  - Add campaign title to product page and category page 
  - Have its own campaign listing page 
  - Campaigns description can render with WYSIWYG or Page builder

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/TiagoSampaio`
 - Enable the module by running `php bin/magento module:enable TiagoSampaio_Campaigns`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Add the composer repository to the configuration by running `composer config repositories.magento2-campaigns git "https://github.com/tiagofssampaio/magento2-campaigns"`
 - Install the module composer by running `composer require tiagosampaio/module-campaigns`
 - enable the module by running `php bin/magento module:enable TiagoSampaio_Campaigns`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

## Configuration

  - Sidebar menu: TIAGOSAMPAIO > Campaigns

## Possible Improvements and Suggestions

  - Add multistore compatibility
  - Instead of using a product selection, use catalog rules to create a listing (the last suggestion would be super important performance wise)
  - Create an index for the campaign product listing page
  - Add field to style the campaign tag
  - Improve cache management

## Notes

  - After editing or adding a campaign, make sure you follow the cache invalidation intructions (clear it)
