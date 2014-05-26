<?php

namespace NFQAkademija\BaseBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Configuration, but not very important cause bundle just for user interfacec
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class NFQAkademijaBaseExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $cfgSet = new Configuration();
        $fileType = 'yml';
        $this->processConfiguration($cfgSet, $configs);
        // jei naudosim sita extetion'a uncommentint $config = $this->processConfiguration($configuration, $configs);

        $servicesFile = 'services.' . $fileType;
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load($servicesFile);
    }
}
