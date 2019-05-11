<?php
/**
 * Created by PhpStorm.
 * User: jlchassaing
 * Date: 27/01/2018
 * Time: 08:09
 */

namespace Gie\FacetBundle\Content\Search\Helper;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Search\Facet;
use eZ\Publish\API\Repository\Values\Content\Search\Facet\CriterionFacet;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class FacetSearchHelperInterface
 * @package Gie\FacetBundle\Content\Search\Helper
 */
interface FacetSearchHelperInterface
{



    /**
     * get FacetBuilder
     * @param array $params
     *
     * @return Query\FacetBuilder
     */
    function getQueryFacetBuilder();


    /**
     * @param array $params
     * @param string $title
     *
     * @return FacetSearchHelperInterface
     */
    function setQueryFacetBuilderParams($title, array $params);

    /**
     * return the queryString facet identifier
     * @return string
     */
    function getFacetIdentifier();

    /**
     * return the full name from params
     * @return mixed
     */
    function getName();

    /**
     *
     * @return CriterionFacet
     *
     */
    function getFacetFilter();

    /**
     * @return boolean
     */
    function hasFacetFilter();

    /**
     * @param Facet $facet
     *
     * @return boolean
     */
    function canVisit(Facet $facet);


    /**
     * @param Facet $facet
     *
     * @return array
     */
    function formatFacet(Facet $facet, Facet $facetsAfterFilter);

    /**
     * get the selected entries for this given facet
     * @param $entries
     *
     * @return mixed
     */
    function setSelectedFacetEntries($entries);


    /**
     *
     * each facet is used for a type of data
     * but the key used in the facet is an id
     * so this function returns the real value from the id
     * @param $id
     *
     * @return mixed
     */
    function getFormatedValueFromFacetId($id);


}