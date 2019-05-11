<?php
/**
 * Created by PhpStorm.
 * User: jlchassaing
 * Date: 14/05/18
 * Time: 08:42
 */


namespace Gie\FacetBundle\Tests\Content\Search\Helper;


use Gie\FacetBundle\Content\Search\Helper\FacetConfig;
use PHPUnit\Framework\TestCase;

/**
 * Class FacetConfigTest
 * @package Gie\FacetBundle\Tests\Content\Search\Helper
 */
class FacetConfigTest extends TestCase
{


    public function testGetAlias()
    {
        $facetConfig = new FacetConfig( "title", "test", []);

        $this->assertInternalType('string', $facetConfig->getTitle());
        $this->assertInternalType('string', $facetConfig->getType());
    }
}