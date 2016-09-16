<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Solr
 * @copyright  Copyright (c) 2016 integer_net GmbH (http://www.integer-net.de/)
 * @author     Andreas von Studnitz <avs@integer-net.de>
 */
namespace IntegerNet\SolrCms\Request;

use IntegerNet\Solr\Implementor\HasUserQuery;
use IntegerNet\Solr\Query\SearchString;
use IntegerNet\Solr\Request\ApplicationContext;
use IntegerNet\Solr\Request\RequestFactory;
use IntegerNet\Solr\Resource\ResourceFacade;
use IntegerNet\SolrCms\Query\CmsPageParamsBuilder;
use IntegerNet\SolrCms\Query\CmsPageQueryBuilder;
use IntegerNet\SolrCms\Request\CmsPageRequest;

class CmsPageRequestFactory extends RequestFactory
{
    /**
     * @var HasUserQuery
     */
    private $query;
    /**
     * @var \IntegerNet\Solr\Config\CmsConfig
     */
    private $cmsConfig;
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
        $this->cmsConfig = $applicationContext->getCmsConfig();
        $this->resultsConfig = $applicationContext->getResultsConfig();
    }

    protected function createQueryBuilder()
    {
        return new CmsPageQueryBuilder(
            new SearchString($this->getQuery()->getUserQueryText()),
            $this->createParamsBuilder(),
            $this->getStoreId(),
            $this->getEventDispatcher(),
            $this->cmsConfig
        );
    }

    protected function createParamsBuilder()
    {
        return new CmsPageParamsBuilder(
            new SearchString($this->query->getUserQueryText()), $this->cmsConfig, $this->resultsConfig, $this->getStoreId());
    }

    /**
     * @return \IntegerNet\Solr\Request\Request
     */
    public function createRequest()
    {
        return new CmsPageRequest(
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