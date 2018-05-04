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

        $request = $this->searchService->findContent($countQuery);

        $this->setFacets($request);

        return $this->nbResults = $request->totalCount;
    }

    /**
     * @param $request
     * set the facets
     */
    protected function setFacets(SearchResult $request)
    {
        if ($this->facets === null)
        {
            $this->facets = $request->facets;
        }
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

        $this->setFacets($searchResult);
        return $searchResult->searchHits;
    }

}