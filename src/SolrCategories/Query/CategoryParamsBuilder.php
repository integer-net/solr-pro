<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Solr
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */
namespace IntegerNet\SolrCategories\Query;
use IntegerNet\Solr\Config\CategoryConfig;
use IntegerNet\Solr\Config\FuzzyConfig;
use IntegerNet\Solr\Config\ResultsConfig;
use IntegerNet\Solr\Query\AbstractParamsBuilder;
use IntegerNet\Solr\Query\Params\FilterQueryBuilder;
use IntegerNet\Solr\Implementor\AttributeRepository;
use IntegerNet\Solr\Implementor\Pagination;
use IntegerNet\Solr\Implementor\EventDispatcher;

final class CategoryParamsBuilder extends AbstractParamsBuilder
{
    private $categoryId;
    /**
     * @var CategoryConfig
     */
    private $categoryConfig;

    /**
     * @param AttributeRepository $attributeRepository
     * @param FilterQueryBuilder $filterQueryBuilder
     * @param Pagination $pagination
     * @param ResultsConfig $resultsConfig
     * @param CategoryConfig $categoryConfig
     * @param FuzzyConfig $fuzzyConfig
     * @param $storeId
     * @param int $categoryId
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(AttributeRepository $attributeRepository, FilterQueryBuilder $filterQueryBuilder,
                                Pagination $pagination, ResultsConfig $resultsConfig,
                                CategoryConfig $categoryConfig, FuzzyConfig $fuzzyConfig,
                                $storeId, $categoryId, EventDispatcher $eventDispatcher)
    {
        parent::__construct($attributeRepository, $filterQueryBuilder, $pagination, $resultsConfig, $fuzzyConfig, $storeId, $eventDispatcher);
        $this->categoryId = $categoryId;
        $this->categoryConfig = $categoryConfig;
    }

    /**
     * @return string
     */
    protected function getCurrentSortField()
    {
        $sortField = $this->pagination->getCurrentOrder();
        if ($sortField === 'position') {
            return 'category_' . $this->categoryId . '_position_i';
        }
        return parent::getCurrentSortField();
    }

    /**
     * @return array
     */
    protected function getFacetFieldCodes()
    {
        $codes = array('category');

        foreach($this->attributeRespository->getFilterableInCatalogAttributes($this->getStoreId()) as $attribute) {
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

        if (!$this->categoryConfig->isShowOutOfStock()) {
            $filterQuery .= ' AND -is_in_stock_i:0';
        }

        return $filterQuery;
    }
}