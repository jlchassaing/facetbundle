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
        $this->facetHelpers[md5(get_class($helper))] = $helper;
    }

    /**
     * @param $alias
     *
     * @return FacetSearchHelperInterface
     */
    public function getFacetHelper($alias)
    {
        $key = md5($alias);
        if (array_key_exists($key, $this->facetHelpers))
        {
            return clone $this->facetHelpers[$key];
        }
    }

}