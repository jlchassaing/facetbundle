<?php
/**
 * Created by PhpStorm.
 * User: jlchassaing
 * Date: 27/01/2018
 * Time: 08:12
 */

namespace Gie\FacetBundle\Content\Search\Helper;


use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Search\Facet;
use Gie\FacetBundle\Content\Query\Criterion\Tag;
use Gie\FacetBundle\Content\Query\FacetBuilder\TagFacetBuilder;
use Gie\FacetBundle\Content\Values\Search\Facet\TagFacet;
use Netgen\TagsBundle\API\Repository\TagsService;
use Psr\Log\LoggerInterface;

class TagFacetSearchHelper extends FacetSearchHelper
{

    /**
     * @var TagsService
     */
    private $tagsService;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @var array
     */
    private $refs;

    function __construct(TagsService $tagsService, LoggerInterface $logger)
    {
        $this->tagsService = $tagsService;
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
            $tag = $this->tagsService->loadTag($id);
            return ['identifier' => $tag->remoteId,
                    'label' => $tag->keyword
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
     * @return TagFacetBuilder
     */
    function getQueryFacetBuilder( )
    {
        return new TagFacetBuilder($this->params);
    }

    function getFacetIdentifier()
    {
        return "tag";
    }

    function getFacetFilter()
    {
        return new Tag($this->selectedEntries);
    }



    function canVisit( Facet $facet )
    {
        if ($facet instanceof TagFacet)
        {
            return true;
        }
        return false;

    }


}