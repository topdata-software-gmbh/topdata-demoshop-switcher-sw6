<?php

namespace Topdata\TopdataDemoshopSwitcherSW6\Subscriber;

use Shopware\Storefront\Page\GenericPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WhyooOs\Util\UtilDebug;


/**
 * 05/2024 created
 */
class MyEventSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            GenericPageLoadedEvent::class => 'onGenericPageLoadedEvent',
        ];
    }


    public function onGenericPageLoadedEvent(GenericPageLoadedEvent $event): void
    {
        // TODO: get the domains from plugin config
        $domainNames = [
            'sw64.docker|6.4',
            'sw65.docker|6.5',
            'sw66.docker|6.6',
            // 'sw67.docker',
        ];

        $domains = [];
        foreach ($domainNames as $domainName) {
            $exploded = explode('|', $domainName);
            $fullName = $exploded[0];
            $label = $exploded[1] ?? $fullName;

            $domains[] = [
                'name'      => $fullName,
                'label' => $label,
                'active'    => false, // fixme
            ];

        }

        // inject data into page
        $event->getPage()->assign(['TopdataDemoshopSwitcherSW6_domains' => $domains]);
    }


}