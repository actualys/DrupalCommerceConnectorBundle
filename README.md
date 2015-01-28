DrupalCommerceConnectorBundle
=============================

Drupal Commerce Connector for Akeneo PIM OpenSource Project.

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/actualys/DrupalCommerceConnectorBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/actualys/DrupalCommerceConnectorBundle/?branch=master) [![Latest Stable Version](https://poser.pugx.org/actualys/drupal-commerce-connector-bundle/v/stable.svg)](https://packagist.org/packages/actualys/drupal-commerce-connector-bundle) [![Total Downloads](https://poser.pugx.org/actualys/drupal-commerce-connector-bundle/downloads.svg)](https://packagist.org/packages/actualys/drupal-commerce-connector-bundle) [![Latest Unstable Version](https://poser.pugx.org/actualys/drupal-commerce-connector-bundle/v/unstable.svg)](https://packagist.org/packages/actualys/drupal-commerce-connector-bundle) [![License](https://poser.pugx.org/actualys/drupal-commerce-connector-bundle/license.svg)](https://packagist.org/packages/actualys/drupal-commerce-connector-bundle)

# Configure Rest Webservice for medias

Add this line in your parameters file:

webservice_servername: # ex:  http://localhost/api/rest/media

# Connector usage :

- add an export profile among this list: 
    - drupal_commerce_attribute_option_export
    - drupal_commerce_category_export
    - drupal_commerce_families_export
    - drupal_commerce_product_delta_export
    - drupal_commerce_product_full_export

- configure each profile you want to use:
    - set drupal base url
    - set endpoint (provide by Drupal Services module)
    - set resource path (provide by Drupal Services module)
    - set username (provide by Drupal Services module)
    - set password (provide by Drupal Services module)
    

# To do

- implement security layer for the webservice media access

# Bug and issues

This bundle is still under active development. Expect bugs and instabilities. Feel free to report them on this repository's [issue section](https://github.com/akeneo/MagentoConnectorBundle/issues).

