<?php
/**
 * Created by PhpStorm.
 * User: jlchassaing
 * Date: 07/02/2018
 * Time: 08:43
 */

namespace Gie\FacetBundle\Content\Search\Helper;


class FacetLoader
{
    /**
     * @var array
     */
    private $facetHelpers;

    public function __construct()
    {
        $this->facetHelpers = [];
    }

    public function addFacetHelper(FacetSearchHelperInterface $helper, $alias)
    {
        $this->facetHelpers[$alias] = $helper;
    }

    /**
     * @param $alias
     *
     * @return FacetSearchHelperInterface
     */
    public function getFacetHelper($alias)
    {
        if (array_key_exists($alias, $this->facetHelpers))
        {
            return clone $this->facetHelpers[$alias];
        }
    }

}