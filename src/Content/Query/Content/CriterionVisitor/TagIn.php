<?php
/**
 * Created by PhpStorm.
 * User: jlchassaing
 * Date: 29/01/2018
 * Time: 10:05
 */

namespace Gie\FacetBundle\Content\Query\Content\CriterionVisitor;


use EzSystems\EzPlatformSolrSearchEngine\Query\CriterionVisitor;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Operator;
use Gie\FacetBundle\Content\Query\Criterion\Tag;
use Netgen\TagsBundle\API\Repository\TagsService;

class TagIn extends CriterionVisitor
{

    /**
     * @var TagsService
     */
    private $tagService;

    public function __construct(TagsService $tagsService)
    {
        $this->tagService = $tagsService;
    }

    /**
     * CHeck if visitor is applicable to current criterion
     *
     * @param Criterion $criterion
     *
     * @return boolean
     */
    public function canVisit( Criterion $criterion )
    {
        return
            $criterion instanceof Tag &&
            ( ( $criterion->operator ?: Operator::IN ) === Operator::IN ||
              $criterion->operator === Operator::EQ );
    }


    /**
     * Map field value to a proper Solr representation
     *
     * @param Criterion $criterion
     * @param CriterionVisitor $subVisitor
     *
     * @return string
     */
    public function visit( Criterion $criterion, CriterionVisitor $subVisitor = null )
    {
        return '(' .
               implode(
                   ' OR ',
                   array_map(
                       function ( $value )
                       {
                           return 'content_tags_ids_mi:"' . $this->tagService->loadTagByRemoteId($value)->id . '"';
                       },
                       $criterion->value
                   )
               ) .
               ')';
    }
}