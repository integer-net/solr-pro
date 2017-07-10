<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Solr
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */
namespace IntegerNet\SolrSuggest\Query;

use IntegerNet\Solr\Query\AbstractParamsBuilder;
use IntegerNet\Solr\Config\FuzzyConfig;
use IntegerNet\Solr\Config\ResultsConfig;
use IntegerNet\Solr\Config\AutosuggestConfig;
use IntegerNet\Solr\Query\Params\FilterQueryBuilder;
use IntegerNet\Solr\Implementor\AttributeRepository;
use IntegerNet\Solr\Implementor\Pagination;

final class AutosuggestParamsBuilder extends AbstractParamsBuilder
{
    /**
     * @var AutosuggestConfig
     */
    private $autosuggestConfig;

    public function __construct(
        AttributeRepository $attributeRepository,
        FilterQueryBuilder $filterQueryBuilder,
        Pagination $pagination,
        ResultsConfig $resultsConfig,
        FuzzyConfig $fuzzyConfig,
        AutosuggestConfig $autosuggestConfig,
        $storeId,
        $eventDispatcher
    ) {
        parent::__construct(
            $attributeRepository,
            $filterQueryBuilder,
            $pagination,
            $resultsConfig,
            $fuzzyConfig,
            $storeId,
            $eventDispatcher
        );
        $this->autosuggestConfig = $autosuggestConfig;
    }

    public function buildAsArray($attributeToReset = '')
    {
        $params = parent::buildAsArray($attributeToReset);
        $params['rows'] = $this->pagination->getPageSize();

        return $params;
    }

    /**
     * Leave out facet parameters
     *
     * @param $params
     * @return mixed
     */
    protected function addFacetParams($params)
    {
        return $params;
    }

    /**
     * @return array
     */
    protected function getFacetFieldCodes()
    {
        $codes = array('category');

        foreach($this->attributeRespository->getFilterableInSearchAttributes($this->getStoreId()) as $attribute) {
            $codes[] = $attribute->getAttributeCode() . '_facet';
        }
        return $codes;
    }

    /**
     * @param string $attributeToReset
     * @return string
     */
    protected function getFilterQuery($attributeToReset = '')
    {
        $filterQuery = $this->filterQueryBuilder->buildFilterQuery($this->getStoreId(), $attributeToReset);

        if (!$this->autosuggestConfig->isShowOutOfStock()) {
            $filterQuery .= ' AND -is_in_stock_i:0';
        }

        return $filterQuery;
    }
}