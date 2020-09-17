<?php
/**
 * Created by PhpStorm.
 * User: jlchassaing
 * Date: 07/02/2018
 * Time: 08:48
 */

namespace Gie\FacetBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FacetHelperCompilerPass implements CompilerPassInterface
{
    public function process( ContainerBuilder $container )
    {

        if (!$container->hasDefinition('gie.facet.facet_loader'))
        {
            return;
        }

        $definition = $container->getDefinition('gie.facet.facet_loader');
        $taggedServices = $container->findTaggedServiceIds('gie.facet_search.helper');

        foreach ( $taggedServices as $id => $tags )
        {
            foreach ( $tags as $attributes )
            {
                $definition->addMethodCall('addFacetHelper', [new Reference($id), $attributes['alias']]);
            }

        }

    }


}