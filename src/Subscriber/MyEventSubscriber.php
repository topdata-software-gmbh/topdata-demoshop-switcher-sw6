<?php

namespace Topdata\TopdataDemoshopSwitcherSW6\Subscriber;

use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Page\GenericPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WhyooOs\Util\UtilDebug;


/**
 * 05/2024 created
 */
class MyEventSubscriber implements EventSubscriberInterface
{

    private SystemConfigService $systemConfigService;

    public function __construct(SystemConfigService $systemConfigService)
    {
        $this->systemConfigService = $systemConfigService;
    }


    public static function getSubscribedEvents(): array
    {
        return [
            GenericPageLoadedEvent::class => 'onGenericPageLoadedEvent',
        ];
    }


    public function onGenericPageLoadedEvent(GenericPageLoadedEvent $event): void
    {
        $strDomains = $this->systemConfigService->get('TopdataDemoshopSwitcherSW6.config.domains');
        if(empty($strDomains)) {
            // fallback to default values
            $domainNames = [
                'sw64.docker|6.4',
                'sw65.docker|6.5',
                'sw66.docker|6.6',
            ];
        } else {
            $domainNames = explode("\n", $strDomains);
        }

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