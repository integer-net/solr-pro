# solr-pro
Additional libraries for IntegerNet_Solr commercial version

## Build status (master)

[![wercker status](https://app.wercker.com/status/ce45581f4b3421cd9ec5938dc8e6a48f/m/master "wercker status")](https://app.wercker.com/project/byKey/ce45581f4b3421cd9ec5938dc8e6a48f)
## Tests

### Unit Test Suite

The unit test suite does not need additional resources

#### Run Tests:

In the root directory:

    phpunit --testsuite unit
    
### Integration Test Suite

The SolrSuggest integration test suite requires a Magento installation to test writing of the custom cache.
By default it looks in `../../htdocs`, but you can specify the Magento root dir with

    export MAGENTO_ROOT=/path/to/magento
    
Nothing will be written to filesystem and Magento database.

Fixtures have been automatically generated with Magento 1.9 sample data.

#### Run Tests:

In the root directory:

    phpunit --testsuite integration