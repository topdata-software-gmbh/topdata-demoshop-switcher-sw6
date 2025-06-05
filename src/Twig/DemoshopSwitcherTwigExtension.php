<?php
namespace Topdata\TopdataDemoshopSwitcherSW6\Twig;

use Topdata\TopdataDemoshopSwitcherSW6\Service\DemoshopSwitcherService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DemoshopSwitcherTwigExtension extends AbstractExtension
{
    private $demoshopSwitcherService;

    public function __construct(DemoshopSwitcherService $demoshopSwitcherService)
    {
        $this->demoshopSwitcherService = $demoshopSwitcherService;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('getShopDomains', [$this, 'getShopDomains']),
        ];
    }

    public function getShopDomains(): array
    {
        return $this->demoshopSwitcherService->getShopDomains();
    }
}