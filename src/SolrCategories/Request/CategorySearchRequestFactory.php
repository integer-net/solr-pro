<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Solr
 * @copyright  Copyright (c) 2016 integer_net GmbH (http://www.integer-net.de/)
 * @author     Andreas von Studnitz <avs@integer-net.de>
 */
namespace IntegerNet\SolrCategories\Request;

use IntegerNet\Solr\Implementor\HasUserQuery;
use IntegerNet\Solr\Query\SearchString;
use IntegerNet\Solr\Request\ApplicationContext;
use IntegerNet\Solr\Request\BaseRequestFactory;
use IntegerNet\Solr\Resource\ResourceFacade;
use IntegerNet\SolrCategories\Query\CategorySearchParamsBuilder;
use IntegerNet\SolrCategories\Query\CategorySearchQueryBuilder;

class CategorySearchRequestFactory extends BaseRequestFactory
{
    /**
     * @var HasUserQuery
     */
    private $query;
    /**
     * @var \IntegerNet\Solr\Config\CategoryConfig
     */
    private $categoryConfig;
    /**
     * @var \IntegerNet\Solr\Config\ResultsConfig
     */
    private $resultsConfig;

    /**
     * @param ApplicationContext $applicationContext
     * @param ResourceFacade $resource
     * @param int $storeId
     */
    public function __construct(ApplicationContext $applicationContext, ResourceFacade $resource, $storeId)
    {
        parent::__construct($applicationContext, $resource, $storeId);
        $this->query = $applicationContext->getQuery();
        $this->categoryConfig = $applicationContext->getCategoryConfig();
        $this->resultsConfig = $applicationContext->getResultsConfig();
    }

    protected function createQueryBuilder()
    {
        return new CategorySearchQueryBuilder(
            new SearchString($this->getQuery()->getUserQueryText()),
            $this->createParamsBuilder(),
            $this->getStoreId(),
            $this->getEventDispatcher(),
            $this->categoryConfig
        );
    }

    protected function createParamsBuilder()
    {
        return new CategorySearchParamsBuilder(
            new SearchString($this->query->getUserQueryText()), $this->categoryConfig, $this->resultsConfig, $this->getStoreId());
    }

    /**
     * @return \IntegerNet\Solr\Request\Request
     */
    public function createRequest()
    {
        return new CategorySearchRequest(
            $this->getResource(),
            $this->createQueryBuilder(),
            $this->getEventDispatcher(),
            $this->getLogger()
        );
    }

    /**
     * @return HasUserQuery
     */
    protected function getQuery()
    {
        return $this->query;
    }

}