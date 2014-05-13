<?php

namespace Didweb\Bundle\ResizeBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DidwebResizeExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
		$configuration = new Configuration();
		
		
		$config = $this->processConfiguration($configuration, $configs);
		$container->setParameter('img_carpeta', $config['img_carpeta']);
		$container->setParameter('img_ancho_p', $config['img_ancho_p']);
		$container->setParameter('img_alto_p', $config['img_alto_p']);
		$container->setParameter('img_ancho_g', $config['img_ancho_g']);
		$container->setParameter('img_alto_g', $config['img_alto_g']);
		
		
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
