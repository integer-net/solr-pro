<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Solr
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */
namespace IntegerNet\SolrSuggest\Request;

use IntegerNet\Solr\Request\ApplicationContext;
use IntegerNet\Solr\Request\SearchRequestFactory;
use IntegerNet\Solr\Resource\ResourceFacade;
use IntegerNet\SolrSuggest\Query\AutosuggestParamsBuilder;
use IntegerNet\Solr\Config\AutosuggestConfig;

class AutosuggestRequestFactory extends SearchRequestFactory
{
    /**
     * @var AutosuggestConfig
     */
    private $autosuggestConfig;

    /**
     * @param ApplicationContext $applicationContext
     * @param ResourceFacade $resource
     * @param int $storeId
     */
    public function __construct(ApplicationContext $applicationContext, ResourceFacade $resource, $storeId)
    {
        parent::__construct($applicationContext, $resource, $storeId);
        $this->autosuggestConfig = $applicationContext->getAutosuggestConfig();
    }

    public function createParamsBuilder()
    {
        return new AutosuggestParamsBuilder(
            $this->getAttributeRepository(),
            $this->getFilterQueryBuilder(),
            $this->getPagination(),
            $this->getResultsConfig(),
            $this->getFuzzyConfig(),
            $this->getAutosuggestConfig(),
            $this->getStoreId(),
            $this->getEventDispatcher()
        );
    }

    /**
     * @return AutosuggestConfig
     */
    protected function getAutosuggestConfig()
    {
        return $this->autosuggestConfig;
    }
}