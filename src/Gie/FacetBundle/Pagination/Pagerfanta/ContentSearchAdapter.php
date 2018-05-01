<?php
/**
 * Created by PhpStorm.
 * User: jlchassaing
 * Date: 17/01/2018
 * Time: 14:30
 */

namespace Gie\FacetBuilder\Pagination\Pagerfanta;

use Gie\FacetBuilder\Pagination\Pagerfanta\ContentSearchHitAdapter as DefaultContentSearchAdapter;

/**
 * Class ContentSearchAdapter
 * @package Gie\FacetBuilder\Pagination\Pagerfanta
 *
 * Extends the defautl ContentSearchAdapter to be able to access facet result
 */
class ContentSearchAdapter extends DefaultContentSearchAdapter
{

    /**
     * @return mixed
     */
    public function getFacets()
    {
        return $this->facets;

    }

}