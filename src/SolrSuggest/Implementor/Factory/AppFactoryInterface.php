<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_SolrSuggest
 * @copyright  Copyright (c) 2017 integer_net GmbH (http://www.integer-net.de/)
 * @author     Andreas von Studnitz <avs@integer-net.de>
 */
namespace IntegerNet\SolrSuggest\Implementor\Factory
{
    class_alias(
        AppFactory::class,
        AppFactoryInterface::class);

    if (! \interface_exists(AppFactoryInterface::class)) {
        /** @deprecated this is an alias for AppFactory due to limitations of the Magento 2 object manager */
        interface AppFactoryInterface extends AppFactory {}
    }
}