<?php

namespace Topdata\TopdataDemoshopSwitcherSW6\Subscriber;

use Shopware\Storefront\Page\GenericPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Topdata\TopdataDemoshopSwitcherSW6\Service\DemoshopSwitcherService;


/**
 * 05/2024 created
 */
class MyEventSubscriber implements EventSubscriberInterface
{

    private DemoshopSwitcherService $demoshopSwitcherService;

    public function __construct(DemoshopSwitcherService $demoshopSwitcherService)
    {
        $this->demoshopSwitcherService = $demoshopSwitcherService;
    }


    public static function getSubscribedEvents(): array
    {
        return [
            GenericPageLoadedEvent::class => 'onGenericPageLoadedEvent',
        ];
    }


    public function onGenericPageLoadedEvent(GenericPageLoadedEvent $event): void
    {
        $event->getPage()->assign(['TopdataDemoshopSwitcherSW6_shopDomains' => $this->demoshopSwitcherService->getShopDomains()]);
    }


}