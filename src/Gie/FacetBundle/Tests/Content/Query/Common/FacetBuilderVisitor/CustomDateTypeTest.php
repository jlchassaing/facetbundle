<?php
/**
 * Created by PhpStorm.
 * User: jlchassaing
 * Date: 13/05/18
 * Time: 15:43
 */


namespace Gie\FacetBundle\Tests\Content\Query\Common\FacetBuilderVisitor;


use eZ\Publish\API\Repository\Values\Content\Query\FacetBuilder;
use Gie\FacetBundle\Content\Query\Common\FacetBuilderVisitor\CustomDateType;
use Gie\FacetBundle\Content\Query\FacetBuilder\CustomDateFacetBuilder;
use Gie\FacetBundle\Content\Values\Search\Facet\CustomDateFacet;
use PHPUnit\Framework\TestCase;

class CustomDateTypeTest extends TestCase
{

    public function testMapField()
    {
        $facetBuilder = $this->createMock(FacetBuilder::class);
        $facetBuilder->name = 'testFacetName';

        $field = "";
        $customDateType = new CustomDateType();

        $map = $customDateType->mapField($field,['counts' => []], $facetBuilder);

        $this->assertInstanceOf(CustomDateFacet::class, $map);
        $this->assertEquals($map->name, $facetBuilder->name);

    }

    public function testCanVisit()
    {
        $facetBuilder = $this->createMock(CustomDateFacetBuilder::class);
        $customDateType = new CustomDateType();

        $this->assertTrue($customDateType->canVisit($facetBuilder));

    }

    public function testVisitBuilder()
    {
        $facetBuilder = $this->createMock(FacetBuilder::class);
        $facetBuilder->start= 'start';
        $facetBuilder->end = 'end';
        $facetBuilder->gap = 'gap';
        $facetBuilder->field = 'field';
        $customDateType = new CustomDateType();

        $this->assertInternalType('array', $customDateType->visitBuilder($facetBuilder, 'id'));
        $this->assertArrayHasKey('facet.range', $customDateType->visitBuilder($facetBuilder, 'id'));

    }


}