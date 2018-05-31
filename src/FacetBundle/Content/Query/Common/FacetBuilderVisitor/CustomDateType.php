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

    const DATE_RANGE = '{!ex=dt key=%s facet.range.start="%s" facet.range.end="%s" facet.range.gap="%s"}%s';

    /**
     * {@inheritdoc}.
     */
    public function mapField($field, array $data, FacetBuilder $facetBuilder)
    {
        return new Facet\CustomDateFacet(
            array(
                'name' => $facetBuilder->name,
                'entries' => $this->mapData($data['counts']),
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

    /**
     * {@inheritdoc}.
     */
    public function visitBuilder(FacetBuilder $facetBuilder, $fieldId)
    {
        return [
            "facet.range" => sprintf(self::DATE_RANGE,
                $fieldId,
                $facetBuilder->start,
                $facetBuilder->end,
                $facetBuilder->gap,
                $facetBuilder->field),
            ];
    }
}
