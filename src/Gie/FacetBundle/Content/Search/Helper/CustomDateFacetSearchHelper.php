<?php
/**
 * Created by PhpStorm.
 * User: jlchassaing
 * Date: 27/01/2018
 * Time: 08:12
 */

namespace Gie\FacetBundle\Content\Search\Helper;

use Gie\FacetBundle\Content\Query\Criterion\CustomDate;
use Gie\FacetBundle\Content\Query\FacetBuilder\CustomDateFacetBuilder;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Search\Facet;

use Gie\FacetBundle\Content\Values\Search\Facet\CustomDateFacet;
use Psr\Log\LoggerInterface;

class CustomDateFacetSearchHelper extends FacetSearchHelper
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
    function getFormatedValueFromFacetId( $date )
    {
        return ['identifier' => $date,
                'label' => $this->formatDate($date)];

    }

    /**
     * Format date for display
     *
     * @param $date
     *
     * @return mixed
     */
    function formatDate($date)
    {
        return $date;
    }

    /**
     * @param array $params
     *
     * @return Query\FacetBuilder|CustomDateFacetBuilder
     */
    function getQueryFacetBuilder( )
    {
        return new CustomDateFacetBuilder($this->params);
    }

    function getFacetIdentifier()
    {
        return "custom_date";
    }

    function getFacetFilter()
    {
        return new CustomDate($this->selectedEntries);
    }


    function canVisit( Facet $facet )
    {
        if ($facet instanceof CustomDateFacet)
        {
            return true;
        }
        return false;

    }


}