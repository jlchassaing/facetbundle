<?php
/**
 * Created by PhpStorm.
 * User: jlchassaing
 * Date: 27/01/2018
 * Time: 08:12
 */

namespace Gie\FacetBundle\Content\Search\Helper;

use eZ\Publish\API\Repository\Values\Content\Search\Facet;
use Gie\FacetBundle\Content\Query\Criterion\SolrField;
use Gie\FacetBundle\Content\Query\FacetBuilder\SolrFieldFacetBuilder;
use Gie\FacetBundle\Content\Values\Search\Facet\SolrFieldFacet;
use Psr\Log\LoggerInterface;

class SolrFieldSearchHelper extends FacetSearchHelper
{
    /** @var LoggerInterface */
    private $logger;

    function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param $id
     *
     * @return mixed|string
     */
    function getFormatedValueFromFacetId( $id )
    {

        $fomatedLabel = array_key_exists('format',$this->params) ? $this->params['format']($id) : $id;
        try{
            return ['identifier' => $id,
                    'label' => $fomatedLabel,
            ];
        }
        catch(\Exception $e)
        {
            $this->logger->error($e->getMessage());
            return [];
        }

    }

    /**
     *
     * @return SolrFieldFacetBuilder
     */
    function getQueryFacetBuilder( )
    {
        return new SolrFieldFacetBuilder($this->params);
    }

    function getFacetIdentifier()
    {
        //return "solr_field";
        return $this->params['field'];
    }

    function getFacetFilter()
    {
        return new SolrField($this->selectedEntries, $this->params['field']);
    }

    function canVisit( Facet $facet )
    {
        if ($facet instanceof SolrFieldFacet && $facet->field == $this->params['field'])
        {
            return true;
        }
        return false;
    }

}