DrupalCommerceConnectorBundle
=============================

Drupal Commerce Connector for Akeneo PIM OpenSource Project.

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/actualys/DrupalCommerceConnectorBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/actualys/DrupalCommerceConnectorBundle/?branch=master) [![Latest Stable Version](https://poser.pugx.org/actualys/drupal-commerce-connector-bundle/v/stable.svg)](https://packagist.org/packages/actualys/drupal-commerce-connector-bundle) [![Total Downloads](https://poser.pugx.org/actualys/drupal-commerce-connector-bundle/downloads.svg)](https://packagist.org/packages/actualys/drupal-commerce-connector-bundle) [![Latest Unstable Version](https://poser.pugx.org/actualys/drupal-commerce-connector-bundle/v/unstable.svg)](https://packagist.org/packages/actualys/drupal-commerce-connector-bundle) [![License](https://poser.pugx.org/actualys/drupal-commerce-connector-bundle/license.svg)](https://packagist.org/packages/actualys/drupal-commerce-connector-bundle)

This bundle has been developped to export product settings and products to Drupal Commerce platform.
A tailor made module for Drupal is required to integrates correctly all data.

# Connector usage

## Export profiles available

### Family

**code:** drupal_commerce_family_export

This export is the more complexe. It contains all information required to create a product type with all it's fields on Drupal side.
It will create too taxonomy vocabularies (empty) if necessary.
The first time a field instance is created, default widget is used with default settings. The second time this export profile is used, field instance settings are not overriden. So you are able to fine tune on Drupal side just after field are created by export profile.

### Attribute option

**code:** drupal_commerce_attribute_option_export

Attribute option concerns only `single` and `multi select` field values. On Drupal side, it result in `taxonomy vocabulary` and `taxonomy terms` creation.
Terms are translated using `i18n_string` mecanism.

### Category

**code:** drupal_commerce_category_export

Using the same mecanism as for `attribute option`, a taxonomy vocabulary is created to hold `Catalog` tree.

### Product

**code:** drupal_commerce_product_full_export / drupal_commerce_product_delta_export

Products are exported to Drupal using 2 mecanisms :
- full: All products are exported each time
- delta: Only new products or updated since last export

Product are exported one by one with all their attributes, associations and group details.

**Note:** Images (or files) are not exported directly with product other fields. They are provided/downloaded through a webservice (callback) used in the Drupal Migrate process.
To make it possible, you need to setup the Akeneo url in your parameter.yml file, and setup login/password into Drupal Akeneo setup page.

## Configure a profile

- drupal base url (ex: "http://drupal.local")
- endpoint (provide by Drupal Services module, ex: "json")
- resource path (provide by Drupal Services module, ex: "akeneo")
- username (provide by Drupal, ex: "admin")
- password (provide by Drupal, ex: "password")

# To do

- implement security layer for the webservice media access

# Bug and issues

This bundle is still under active development. Expect bugs and instabilities.
Feel free to report them on this repository's [issue section](https://github.com/actualys/DrupalCommerceConnectorBundle/issues).

