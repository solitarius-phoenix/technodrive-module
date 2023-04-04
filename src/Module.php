<?php

namespace Technodrive\Module;
use Technodrive\Core\Service\ServiceManager;
use Technodrive\Process\Enumeration\StepEnum;
use Technodrive\Process\Interface\ProcessInterface;
use Technodrive\Process\Interface\StepInterface;
use Technodrive\Process\ProcessManager;
use Technodrive\Process\Process;

class Module
{
    protected ProcessInterface $process;
    protected Process $step;

    protected \ArrayObject $configuration;
    protected ServiceManager $serviceManager;

    public function __construct(ServiceManager $serviceManager)
    {
        $this->setConfiguration($serviceManager->getConfig());
        $this->setServiceManager($serviceManager);
        $this->setProcess();
    }

    public function loadModules(): void
    {
        $step = $this->getStep();
        $step->setCurrentStep(StepEnum::load);
        $this->process->triggerStep($step);
        $this->process->setProcess($step);
    }

    public function getStep()
    {
        if(! isset($this->step) || ! $this->step instanceof StepInterface){
            $this->setStep();
        }
        return $this->step;
    }

    protected function setStep(): self
    {
        if(! isset($this->step) || $this->step instanceof ProcessInterface) {
            $this->step = new Process();
        }
        return $this;
    }

    /**
     * @return \ArrayObject
     */
    public function getConfiguration(): \ArrayObject
    {
        return $this->configuration;
    }

    /**
     * @param \ArrayObject $configuration
     * @return Module
     */
    public function setConfiguration(\ArrayObject $configuration): Module
    {
        $this->configuration = $configuration;
        return $this;
    }

    /**
     * @return ServiceManager
     */
    public function getServiceManager(): ServiceManager
    {
        return $this->serviceManager;
    }

    /**
     * @param ServiceManager $serviceManager
     * @return Module
     */
    public function setServiceManager(ServiceManager $serviceManager): Module
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }


    public function getProcess(): ProcessInterface
    {
        if(! isset($this->process) || $this->process instanceof ProcessInterface) {
            $this->setProcess();
        }
        return $this->process;
    }

    protected function setProcess(): self
    {
        if(! isset($this->process) || $this->process instanceof ProcessInterface) {
            $this->process = new ProcessManager($this->serviceManager);
            $this->serviceManager->add($this->process);
        }
        return $this;
    }
}