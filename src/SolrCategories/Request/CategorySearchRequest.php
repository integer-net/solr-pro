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

use IntegerNet\Solr\Event\Transport;
use IntegerNet\Solr\Implementor\EventDispatcher;
use IntegerNet\Solr\Request\Request;
use IntegerNet\Solr\Resource\LoggerDecorator;
use IntegerNet\Solr\Resource\ResourceFacade;
use IntegerNet\Solr\Resource\SolrResponse;
use IntegerNet\SolrCategories\Query\CategorySearchQueryBuilder;
use Psr\Log\LoggerInterface;

class CategorySearchRequest implements Request
{
    /**
     * @var $resource ResourceFacade
     */
    private $resource;
    /**
     * @var $queryBuilder CategorySearchQueryBuilder
     */
    private $queryBuilder;
    /**
     * @var $eventDispatcher EventDispatcher
     */
    private $eventDispatcher;
    /**
     * @var $logger LoggerDecorator
     */
    private $logger;

    /**
     * SearchTermSearchRequest constructor.
     * @param ResourceFacade $resource
     * @param CategorySearchQueryBuilder $queryBuilder
     * @param EventDispatcher $eventDispatcher
     * @param LoggerInterface $logger
     */
    public function __construct(ResourceFacade $resource, CategorySearchQueryBuilder $queryBuilder, EventDispatcher $eventDispatcher, LoggerInterface $logger)
    {
        $this->resource = $resource;
        $this->queryBuilder = $queryBuilder;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = new LoggerDecorator($logger);
    }


    /**
     * @param string[] $activeFilterAttributeCodes
     * @return SolrResponse
     */
    public function doRequest($activeFilterAttributeCodes = array())
    {
        $query = $this->queryBuilder->build();

        $transportObject = new Transport(array(
            'store_id' => $this->queryBuilder->getParamsBuilder()->getStoreId(),
            'query_text' => $query->getQueryText(),
            'start_item' => $query->getOffset(),
            'page_size' => $query->getLimit(),
            'params' => $query->getParams()
        ));

        $this->eventDispatcher->dispatch('integernet_solr_before_category_search_request', array('transport' => $transportObject));

        $startTime = microtime(true);
        $result = $this->resource->search(
            $transportObject->getStoreId(),
            $transportObject->getQueryText(),
            $transportObject->getStartItem(), // Start item
            $transportObject->getPageSize(), // Items per page
            $transportObject->getParams()
        );
        $this->logger->logResult($result, microtime(true) - $startTime);

        $this->eventDispatcher->dispatch('integernet_solr_after_category_search_request', array('result' => $result));

        return $result;
    }

}