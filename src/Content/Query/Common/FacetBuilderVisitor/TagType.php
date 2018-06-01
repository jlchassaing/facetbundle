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
use Gie\FacetBundle\Content\Query\FacetBuilder\TagFacetBuilder;
use Gie\FacetBundle\Content\Values\Search\Facet;

/**
 * Visits the ContentType facet builder.
 */
class TagType extends FacetBuilderVisitor implements FacetFieldVisitor
{
    /**
     * {@inheritdoc}.
     */
    public function mapField($field, array $data, FacetBuilder $facetBuilder)
    {
        return new Facet\TagFacet(
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
        return $facetBuilder instanceof TagFacetBuilder;
    }

    /**
     * {@inheritdoc}.
     */
    public function visitBuilder(FacetBuilder $facetBuilder, $fieldId)
    {
        return array(
            'facet.field' => "{!ex=dt key=${fieldId}}content_tags_ids_mi",
            'f.content_tags_ids_mi.facet.limit' => $facetBuilder->limit,
            'f.content_tags_ids_mi.facet.mincount' => $facetBuilder->minCount,
        );
    }
}
