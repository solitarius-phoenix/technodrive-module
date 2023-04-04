<?php

namespace Technodrive\Module\Listener;

use Technodrive\Core\Configurator;
use Technodrive\Core\Exception\ClassInitFailureException;
use Technodrive\Core\Interface\ContainerInterface;

class LoadModulesListener
{
    protected $serviceManager;

    public function __construct(ContainerInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;

    }

    public function call()
    {
        $this->configureModules();
    }

    protected function configureModules(): void
    {
        $modules = $this->serviceManager->getConfig()['modules'];
        foreach($modules as $module){
            $className = $module . "\\" . 'Module';
            if(! class_exists($className)) {
                throw new ClassInitFailureException(sprintf('Module %1$s can\'t be initialized, Module class missing', $className));
            }
            if(! method_exists($className, 'getConfig')){
                continue;
            }
            $configFiles = (new $className())->getConfig();
            foreach ($configFiles as $configFile){
                $configArray = require_once $configFile;
                $this->serviceManager->get(Configurator::class)->addConfig($configArray);
            }

        }
    }

}