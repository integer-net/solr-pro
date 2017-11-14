<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Solr
 * @copyright  Copyright (c) 2016 integer_net GmbH (http://www.integer-net.de/)
 * @author     Andreas von Studnitz <avs@integer-net.de>
 */
namespace IntegerNet\SolrCategories\Query;

use IntegerNet\Solr\Config\CategoryConfig;
use IntegerNet\Solr\Config\ResultsConfig;
use IntegerNet\Solr\Query\ParamsBuilder;
use IntegerNet\Solr\Query\SearchString;

class CategorySearchParamsBuilder implements ParamsBuilder
{
    /**
     * @var CategoryConfig
     */
    private $categoryConfig;
    /**
     * @var ResultsConfig
     */
    private $resultsConfig;
    /**
     * @var int
     */
    private $storeId;
    /**
     * @var SearchString
     */
    private $searchString;

    /**
     * CategorySearchParamsBuilder constructor.
     * @param SearchString $searchString
     * @param CategoryConfig $categoryConfig
     * @param ResultsConfig $resultsConfig
     * @param int $storeId
     */
    public function __construct(SearchString $searchString, CategoryConfig $categoryConfig, ResultsConfig $resultsConfig, $storeId)
    {
        $this->resultsConfig = $resultsConfig;
        $this->categoryConfig = $categoryConfig;
        $this->storeId = $storeId;
        $this->searchString = $searchString;
    }

    /**
     * Return parameters as array as expected by solr service
     *
     * @return mixed[]
     */
    public function buildAsArray()
    {
        $params = array(
            'q.op' => $this->resultsConfig->getSearchOperator(),
            'fq' => 'content_type:category AND store_id:' . $this->storeId,
            'fl' => 'name_t, url_s_nonindex, image_url_s_nonindex, abstract_t_nonindex, path_s_nonindex',
            'sort' => 'score desc',
            'defType' => 'edismax',
        );

        return $params;
    }

    /**
     * Return store id
     *
     * @return int
     */
    public function getStoreId()
    {
        return $this->storeId;
    }

    /**
     * @param string $attributeToReset
     * @return $this
     */
    public function setAttributeToReset($attributeToReset)
    {
        return $this;
    }
}