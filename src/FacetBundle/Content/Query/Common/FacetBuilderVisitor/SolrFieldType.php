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
use Gie\FacetBundle\Content\Query\FacetBuilder\SolrFieldFacetBuilder;
use Gie\FacetBundle\Content\Values\Search\Facet;

/**
 * Visits the ContentType facet builder.
 */
class SolrFieldType extends FacetBuilderVisitor implements FacetFieldVisitor
{
    /**
     * {@inheritdoc}.
     */
    public function mapField($field, array $data, FacetBuilder $facetBuilder)
    {
        return new Facet\SolrFieldFacet(
            array(
                'name' => $facetBuilder->name,
                'entries' => $this->mapData($data),
                'field' => $facetBuilder->field,
            )
        );
    }

    /**
     * {@inheritdoc}.
     */
    public function canVisit(FacetBuilder $facetBuilder)
    {
        return $facetBuilder instanceof SolrFieldFacetBuilder;
    }


    /**
     * {@inheritdoc}.
     */
    public function visitBuilder(FacetBuilder $facetBuilder, $fieldId)
    {
        return [
            'facet.field' => "{!key=$fieldId}".$facetBuilder->field,
            'f.'.$facetBuilder->field.'.facet.limit' => $facetBuilder->limit,
            'f.'.$facetBuilder->field.'.facet.mincount' => $facetBuilder->minCount,
            'f.'.$facetBuilder->field.'.facet.sort' => 'index desc',
        ];


    }



}
