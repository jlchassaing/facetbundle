<?php
/**
 * Created by PhpStorm.
 * User: jlchassaing
 * Date: 17/01/2018
 * Time: 14:34
 */

namespace Gie\FacetBundle\Pagination\Pagerfanta;

use eZ\Publish\API\Repository\Values\Content\Search\SearchResult;
use eZ\Publish\Core\Pagination\Pagerfanta\ContentSearchHitAdapter as DefaultContentSearchHitAdapter;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\SearchService;

class ContentSearchHitAdapter extends DefaultContentSearchHitAdapter
{

    protected $facets;


    /**
     * @var \eZ\Publish\API\Repository\Values\Content\Query
     */
    protected $query;

    /**
     * @var \eZ\Publish\API\Repository\SearchService
     */
    protected $searchService;

    /**
     * @var int
     */
    protected $nbResults;

    public function __construct(Query $query, SearchService $searchService)
    {
        parent::__construct($query,$searchService);

        $this->query = $query;
        $this->searchService = $searchService;
        $this->facets = null;
    }

    /**
     * Returns the number of results.
     *
     * @return int The number of results.
     */
    public function getNbResults()
    {
        if (isset($this->nbResults)) {
            return $this->nbResults;
        }

        $countQuery = clone $this->query;
        $countQuery->limit = 0;
        
        return $this->nbResults =  $this->searchService->findContent($countQuery)->totalCount;
    }

    /**
     * @param $request
     * set the facets
     */
    public function getFacets()
    {
        if (isset($this->facets)) {
            return $this->facets;
        }

        $facetQuery = clone $this->query;
        $facetQuery->limit = 0;

        return $this->facets = $this->searchService->findContent($facetQuery)->facets;
    }

    /**
     * Returns a slice of the results, as SearchHit objects.
     *
     * @param int $offset The offset.
     * @param int $length The length.
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Search\SearchHit[]
     */
    public function getSlice($offset, $length)
    {
        $query = clone $this->query;
        $query->offset = $offset;
        $query->limit = $length;
        $query->performCount = false;

        $searchResult = $this->searchService->findContent($query);

        // Set count for further use if returned by search engine despite !performCount (Solr, ES)
        if (!isset($this->nbResults) && isset($searchResult->totalCount)) {
            $this->nbResults = $searchResult->totalCount;
        }

        if (!isset($this->facets) && isset($searchResult->facets)) {
            $this->facets = $searchResult->facets;
        }

        return $searchResult->searchHits;
    }

    /**
     * @return \eZ\Publish\API\Repository\Values\Content\Query
     */
    public function getQuery()
    {
        return $this->query;
    }

}