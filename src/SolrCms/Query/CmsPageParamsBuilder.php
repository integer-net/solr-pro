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

use IntegerNet\Solr\Config\CmsConfig;
use IntegerNet\Solr\Config\ResultsConfig;
use IntegerNet\Solr\Query\ParamsBuilder;
use IntegerNet\Solr\Query\SearchString;

class CmsPageParamsBuilder implements ParamsBuilder
{
    /**
     * @var CmsConfig
     */
    private $cmsConfig;
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
     * CmsPageParamsBuilder constructor.
     * @param SearchString $searchString
     * @param CmsConfig $cmsConfig
     * @param ResultsConfig $resultsConfig
     * @param int $storeId
     */
    public function __construct(SearchString $searchString, CmsConfig $cmsConfig, ResultsConfig $resultsConfig, $storeId)
    {
        $this->resultsConfig = $resultsConfig;
        $this->cmsConfig = $cmsConfig;
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
            'fq' => 'content_type:page AND store_id:' . $this->storeId,
            'fl' => 'title_t, url_s_nonindex, image_url_s_nonindex, abstract_t_nonindex',
            'sort' => 'score desc',
            'defType' => 'edismax',
            'mm' => '1',
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

}