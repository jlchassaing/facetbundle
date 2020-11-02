<?php
/**
 * Created by PhpStorm.
 * User: jlchassaing
 * Date: 29/01/2018
 * Time: 09:55
 */

namespace Gie\FacetBundle\Content\Query\Criterion;


use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Operator\Specifications;
use eZ\Publish\API\Repository\Values\Content\Query\CriterionInterface;

/**
 * A criterion that matches content based on its ContentType Identifier.
 *
 * Supported operators:
 * - IN: will match from a list of ContentTypeIdentifier
 * - EQ: will match against one ContentTypeIdentifier
 */
class SolrField extends Criterion implements CriterionInterface
{
    /**
     * Creates a new ContentType criterion.
     *
     * Content will be matched if it matches one of the contentTypeIdentifier in $value
     *
     * @param string|string[] $value One or more content type identifiers that must be matched
     *
     * @throws \InvalidArgumentException if the value type doesn't match the operator
     */
    public function __construct($value, $target)
    {
        parent::__construct(null, null, $value);
        $this->target = $target;
    }

    public function getSpecifications(): array
    {
        return array(
            new Specifications(
                Criterion\Operator::IN,
                Specifications::FORMAT_ARRAY,
                Specifications::TYPE_STRING
            ),
            new Specifications(
                Criterion\Operator::EQ,
                Specifications::FORMAT_SINGLE,
                Specifications::TYPE_STRING
            ),
        );
    }

    public static function createFromQueryBuilder($target, $operator, $value)
    {
        return new self($value);
    }
}