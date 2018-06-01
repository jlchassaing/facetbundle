<?php
/**
 * Created by PhpStorm.
 * User: jlchassaing
 * Date: 27/01/2018
 * Time: 08:12
 */

namespace Gie\FacetBundle\Content\Search\Helper;


use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Search\Facet;
use eZ\Publish\API\Repository\Values\Content\Search\Facet\ContentTypeFacet;
use eZ\Publish\API\Repository\ContentTypeService;
use Psr\Log\LoggerInterface;

class ContentTypeFacetSearchHelper extends FacetSearchHelper
{

    /**
     * @var ContentTypeService
     */
    private $contentTypeService;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @var array
     */
    private $refs;

    function __construct(ContentTypeService $contentTypeService, LoggerInterface $logger)
    {
        $this->contentTypeService = $contentTypeService;
        $this->logger = $logger;
    }

    /**
     * @param $id
     *
     * @return mixed|string
     */
    function getFormatedValueFromFacetId( $id )
    {
        try{
            $contentType = $this->contentTypeService->loadContentType($id);
            return ['identifier' => $contentType->identifier,
                    'label' => $contentType->getName($contentType->mainLanguageCode)
            ];
        }
        catch(NotFoundException $e)
        {
            $this->logger->error($e->getMessage());
            return [];
        }

    }

    /**
     * @param array $params
     *
     * @return Query\FacetBuilder|Query\FacetBuilder\ContentTypeFacetBuilder
     */
    function getQueryFacetBuilder( )
    {
        return new Query\FacetBuilder\ContentTypeFacetBuilder($this->params);
    }

    function getFacetIdentifier()
    {
        return "content_type";
    }

    function getFacetFilter()
    {
        return new Query\Criterion\ContentTypeIdentifier($this->selectedEntries);
    }


    function canVisit( Facet $facet )
    {
        if ($facet instanceof ContentTypeFacet)
        {
            return true;
        }
        return false;

    }


}