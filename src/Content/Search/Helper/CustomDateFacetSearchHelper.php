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
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

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
        return ['identifier' => urlencode('['.$this->params['start']. ' TO '. $this->params['end'].']') ,
                'label' => $this->getName(),
            ];
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
     * Map value to a proper Solr date representation.
     *
     * @param mixed $value
     *
     * @return string
     */
    protected function getSolrTime($value)
    {
        if (is_numeric($value)) {
            $date = new \DateTime("@{$value}");
        } else {
            try {
                $date = new \DateTime($value);
            } catch (Exception $e) {
                throw new \InvalidArgumentException('Invalid date provided: ' . $value);
            }
        }

        return $date->format('Y-m-d\\TH:i:s\\Z');
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
        $id = preg_replace('/\s+/',
            '_',
            strtolower(iconv('UTF8',
                'ASCII//TRANSLIT//IGNORE',
                $this->params['name'])
            )
        );
        return $id;
    }

    function getFacetFilter()
    {

        return new CustomDate($this->params['field'], $this->selectedEntries);
    }


    function canVisit( Facet $facet )
    {
        if ($facet instanceof CustomDateFacet && $facet->name === $this->getName())
        {
            return true;
        }
        return false;

    }

    function formatFacet( Facet $facet, Facet $facetAfterFilter )
    {
        $name = $this->getFacetIdentifier();

        $conf = [];

        foreach ( $facet->entries as $id => $count )
        {

            $formatedValue = $this->getFormatedValueFromFacetId($id);
            $selected = in_array(urldecode($formatedValue['identifier']), $this->selectedEntries);

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