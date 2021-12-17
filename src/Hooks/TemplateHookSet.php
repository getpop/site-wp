<?php

declare(strict_types=1);

namespace PoP\SiteWP\Hooks;

use PoP\ComponentModel\HelperServices\ApplicationStateHelperServiceInterface;
use PoP\EngineWP\Component;
use PoP\BasicService\AbstractHookSet;

class TemplateHookSet extends AbstractHookSet
{
    private ?ApplicationStateHelperServiceInterface $applicationStateHelperService = null;

    final public function setApplicationStateHelperService(ApplicationStateHelperServiceInterface $applicationStateHelperService): void
    {
        $this->applicationStateHelperService = $applicationStateHelperService;
    }
    final protected function getApplicationStateHelperService(): ApplicationStateHelperServiceInterface
    {
        return $this->applicationStateHelperService ??= $this->instanceManager->getInstance(ApplicationStateHelperServiceInterface::class);
    }

    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            'template_include',
            [$this, 'setTemplate'],
            // Execute last
            PHP_INT_MAX
        );
    }

    public function setTemplate(string $template): string
    {
        // If doing JSON, for sure return json.php which only prints the encoded JSON
        if (!$this->getApplicationStateHelperService()->doingJSON()) {
            return Component::getTemplatesDir() . '/Output.php';
        }
        return $template;
    }
}
