<?php

/**
 * File containing the eZ\Publish\API\Repository\Values\Content\Query\FacetBuilder\ContentTypeFacetBuilder class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace Gie\FacetBundle\Content\Query\FacetBuilder;

use eZ\Publish\API\Repository\Values\Content\Query\FacetBuilder;

/**
 * Building a content type facet.
 *
 * If provided the search service returns a ContentTypeFacet
 */
class CustomDateFacetBuilder extends FacetBuilder
{
    public $field;

    public $start;

    public $end;

    public $gap;
}
