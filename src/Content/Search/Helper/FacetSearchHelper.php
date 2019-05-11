<?php
/**
 * Created by PhpStorm.
 * User: jlchassaing
 * Date: 27/01/2018
 * Time: 08:07
 */

namespace Gie\FacetBundle\Content\Search\Helper;


use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Search\Facet;

abstract class FacetSearchHelper implements FacetSearchHelperInterface
{
    protected static $queryStringIdentifier = "facet_item";

    protected $params = [];

    protected $selectedEntries = [];


    function hasFacetFilter()
    {
        return (is_array($this->selectedEntries) and count($this->selectedEntries));
    }


    function setQueryFacetBuilderParams($title, array $params )
    {
        $this->params = $params;
        $this->params['name'] = $title;
        return $this;

    }


    /**
     * @return mixed
     */
    function getName()
    {
        return $this->params['name'];
    }


    function addQueryFacetBuilder( Query $query )
    {
        $query->facetBuilders[] = $this->getQueryFacetBuilder();
    }

    function setSelectedFacetEntries( $entries )
    {
        $this->selectedEntries = $entries;
    }

    function resetSelectedFacetEnties()
    {
        $this->selectedEntries = [];
    }

    function formatFacet( Facet $facet, Facet $facetAfterFilter )
    {
        $name = $this->getFacetIdentifier();

        $conf = [];

        foreach ( $facet->entries as $id => $count )
        {
            $formatedValue = $this->getFormatedValueFromFacetId($id);
            $selected = in_array($formatedValue['identifier'], $this->selectedEntries);

            $conf[]  = ['name' => $formatedValue['label'],
                        'key'  => $name."_".$id,
                        'count' => isset($facetAfterFilter->entries[$id]) ?$facetAfterFilter->entries[$id] : $facet->entries[$id],
                        'facetkey' => $name.":".$formatedValue['identifier'],
                        'selected' => $selected,
                        ];

        }
        return $conf;

    }


}