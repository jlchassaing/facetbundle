<?php
/**
 * Created by PhpStorm.
 * User: jlchassaing
 * Date: 17/01/2018
 * Time: 14:30
 */

namespace Gie\FacetBundle\Pagination\Pagerfanta;

use Gie\FacetBundle\Pagination\Pagerfanta\LocationSearchHitAdapter as DefaultLocationSearchAdapter;

/**
 * Class ContentSearchAdapter
 * @package Gie\FacetBuilder\Pagination\Pagerfanta
 *
 * Extends the defautl ContentSearchAdapter to be able to access facet result
 */
class LocationSearchAdapter extends DefaultLocationSearchAdapter
{
    /**
     * Returns a slice of the results, as SearchHit objects.
     *
     * @param int $offset The offset.
     * @param int $length The length.
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Search\SearchHit[]
     */
    public function getSlice($offset, $length)
    {
        $list = [];
        foreach (parent::getSlice($offset, $length) as $hit) {
            $list[] = $hit->valueObject;
        }

        return $list;
    }
}