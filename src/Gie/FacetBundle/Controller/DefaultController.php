<?php

namespace Gie\FacetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GCGieTagFacetBundle:Default:index.html.twig');
    }
}
