DrupalCommerceConnectorBundle
=============================

Drupal Commerce Connector for Akeneo PIM OpenSource Project.


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
rm -r
This bundle is still under active development. Expect bugs and instabilities. Feel free to report them on this repository's [issue section](https://github.com/akeneo/MagentoConnectorBundle/issues).

