<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Solr
 * @copyright  Copyright (c) 2016 integer_net GmbH (http://www.integer-net.de/)
 * @author     Andreas von Studnitz <avs@integer-net.de>
 */
namespace IntegerNet\SolrCms\Query;

use IntegerNet\Solr\Implementor\EventDispatcher;
use IntegerNet\Solr\Event\Transport;
use IntegerNet\Solr\Query\ParamsBuilder;
use IntegerNet\Solr\Query\Query;
use IntegerNet\Solr\Query\QueryBuilder;
use IntegerNet\Solr\Query\SearchString;
use IntegerNet\Solr\Config\CmsConfig;

class CmsPageQueryBuilder implements QueryBuilder
{
    /**
     * @var $searchString SearchString
     */
    private $searchString;

    /**
     * @var $paramsBuilder ParamsBuilder
     */
    private $paramsBuilder;
    /**
     * @var $eventDispatcher EventDispatcher
     */
    private $eventDispatcher;
    /**
     * @var $storeId int
     */
    private $storeId;
    /**
     * @var $cmsConfig CmsConfig
     */
    private $cmsConfig;

    /**
     * @param SearchString $searchString
     * @param ParamsBuilder $paramsBuilder
     * @param int $storeId
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(SearchString $searchString, ParamsBuilder $paramsBuilder, $storeId, EventDispatcher $eventDispatcher, CmsConfig $cmsConfig)
    {
        $this->searchString = $searchString;
        $this->paramsBuilder = $paramsBuilder;
        $this->storeId = $storeId;
        $this->eventDispatcher = $eventDispatcher;
        $this->cmsConfig = $cmsConfig;
    }

    public function build()
    {
        $limit = $this->cmsConfig->getMaxNumberResults();
        if (!$limit) {
            $limit = 100;
        }
        return new Query(
            $this->storeId,
            $this->getQueryText(),
            0,
            $limit,
            $this->paramsBuilder->buildAsArray()
        );
    }

    /**
     * @return string
     */
    protected function getQueryText()
    {
        $searchString = $this->getSearchString();

        $transportObject = new Transport(array(
            'query_text' => $searchString->getRawString(),
        ));

        $this->getEventDispatcher()->dispatch('integernet_solr_update_query_text', array('transport' => $transportObject));

        $searchString = new SearchString($transportObject->getQueryText());
        $queryText = $searchString->getEscapedString() . ' OR ' . $searchString->getEscapedString();

        $isFuzzyActive = $this->cmsConfig->isFuzzyActive();
        $sensitivity = $this->cmsConfig->getFuzzySensitivity();

        if ($isFuzzyActive) {
            $queryText .= '~' . $sensitivity;
        }

        return $queryText;
    }

    /**
     * @return SearchString
     */
    public function getSearchString()
    {
        return $this->searchString;
    }

    /**
     * @return ParamsBuilder
     */
    public function getParamsBuilder()
    {
        return $this->paramsBuilder;
    }

    /**
     * @return EventDispatcher
     */
    protected function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }
}