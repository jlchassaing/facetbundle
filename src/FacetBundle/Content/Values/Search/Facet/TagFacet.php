<?php

/**
 * File containing the eZ\Publish\API\Repository\Values\Content\Search\Facet\ContentTypeFacet class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Gie\FacetBundle\Content\Values\Search\Facet;

use eZ\Publish\API\Repository\Values\Content\Search\Facet;

/**
 * This class holds counts of content with content type.
 */
class TagFacet extends Facet
{
    /**
     * An array with contentTypeId as key and count of matching content objects as value.
     *
     * @var array
     */
    public $entries;
}
