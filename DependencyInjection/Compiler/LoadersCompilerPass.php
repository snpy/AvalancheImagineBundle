<?php

namespace Avalanche\Bundle\ImagineBundle\DependencyInjection\Compiler;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class LoadersCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('imagine.filter.manager')) {
            return;
        }

        $manager = $container->getDefinition('imagine.filter.manager');

        foreach ($container->findTaggedServiceIds('imagine.filter.loader') as $id => $tags) {
            foreach ($tags as $tag) {
                if (empty($tag['filter'])) {
                    $message = sprintf('The "filter" attribute is missing for the service "%s"', $id);
                    throw new InvalidArgumentException($message);
                }

                $manager->addMethodCall('addLoader', array($tag['filter'], new Reference($id)));
            }
        }
    }
}
