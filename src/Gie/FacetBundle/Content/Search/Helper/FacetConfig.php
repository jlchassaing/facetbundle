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

    protected $alias;

    protected $title;

    protected $params;

    /**
     * FacetConfig constructor.
     * @param $alias
     * @param $title
     * @param array $params
     */
    public function __construct($alias, $title, $params = [])
    {
        $this->alias = $alias;
        $this->title = $title;
        $this->params = $params;

    }

    /**
     * @return mixed
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param mixed $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return array
     */
    public function getParams()
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