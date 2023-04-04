<?php

namespace Technodrive\Module\Factory;

use Technodrive\Core\Configurator;
use Technodrive\Core\Exception\ClassInitFailureException;
use Technodrive\Core\Interface\ContainerInterface;
use Technodrive\Core\Interface\FactoryInterface;
use Technodrive\Module\Listener\LoadModulesListener;

class LoadModulesListenerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container)
    {
        return new LoadModulesListener($container);
    }

}