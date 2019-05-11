<?php
/**
 * Created by PhpStorm.
 * User: jlchassaing
 * Date: 27/04/18
 * Time: 16:30
 */

namespace Gie\FacetBundle\Content\Search\Helper;


class FacetConfig implements FacetConfigInterface
{

    protected $title;

    protected $type;

    protected $params;

    /**
     * FacetConfig constructor.

     * @param $title
     * @param array $params
     */
    public function __construct( $title, $type, $params = [])
    {

        if (is_null($type)) throw new \InvalidArgumentException("a type must be set");
        if (!is_string($type)) throw new \InvalidArgumentException("type must be a string");
     
        $this->title = $title;
        $this->type = $type;
        $this->params = $params;

    }

 

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }



}