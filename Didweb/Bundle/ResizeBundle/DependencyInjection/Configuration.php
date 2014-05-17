<?php

namespace Didweb\Bundle\ResizeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
   
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('didweb_resize');

		$rootNode
			->children()
				->scalarNode('img_carpeta')->defaultValue('fotos')->end()
				->integerNode('img_ancho_p')->defaultValue(240)->end()	
				->integerNode('img_alto_p')->defaultValue(196)->end()	
				->integerNode('img_ancho_g')->defaultValue(1024)->end()	
				->integerNode('img_alto_g')->defaultValue(768)->end()
				->scalarNode('img_directorio')->defaultValue('fotos')->end()
			->end();	


        return $treeBuilder;
    }
}
