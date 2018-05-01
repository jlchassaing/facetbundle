<?php

/**
 * This file is part of the eZ Platform Solr Search Engine package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace Gie\FacetBundle\Content\Query\Common\FacetBuilderVisitor;

use EzSystems\EzPlatformSolrSearchEngine\Query\FacetBuilderVisitor;
use EzSystems\EzPlatformSolrSearchEngine\Query\FacetFieldVisitor;
use eZ\Publish\API\Repository\Values\Content\Query\FacetBuilder;
use Gie\FacetBundle\Content\Query\FacetBuilder\CustomDateFacetBuilder;
use Gie\FacetBundle\Content\Query\FacetBuilder\TagFacetBuilder;
use Gie\FacetBundle\Content\Values\Search\Facet;

/**
 * Visits the ContentType facet builder.
 */
class CustomDateType extends FacetBuilderVisitor implements FacetFieldVisitor
{
    /**
     * {@inheritdoc}.
     */
    public function mapField($field, array $data, FacetBuilder $facetBuilder)
    {
        return new Facet\CustomDateFacet(
            array(
                'name' => $facetBuilder->name,
                'entries' => $this->mapData($data),
            )
        );
    }

    /**
     * {@inheritdoc}.
     */
    public function canVisit(FacetBuilder $facetBuilder)
    {
        return $facetBuilder instanceof CustomDateFacetBuilder;
    }


    /*
     *
     * facet=on&fl=custom_date_dt&facet.range=custom_date_dt&facet.range.gap=%2BMONTH&indent=on&wt=json
     *
     */

    /**
     * {@inheritdoc}.
     */
    public function visitBuilder(FacetBuilder $facetBuilder, $fieldId)
    {
        return array(
            'facet.field' => "{!ex=dt key=${fieldId}}custom_date_dt",
            'f.custom_date_dt.facet.limit' => $facetBuilder->limit,
            'f.custom_date_dt.facet.mincount' => $facetBuilder->minCount,
            'f.custom_date_dt.facet.range.start' => 'NOW/YEAR-3YEARS',
            'f.custom.date_dt.facet.range.end' => 'NOW/MONTH+1YEAR',
            'f.custom.date_dt.facet.range.gap' => '+1YEAR',
            'f.custom.date_dt.facet.range.other' => 'all',

        );
    }
}
