<?php

namespace Topdata\TopdataDemoshopSwitcherSW6\Subscriber;

use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Page\GenericPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use WhyooOs\Util\UtilDebug;


/**
 * 05/2024 created
 */
class MyEventSubscriber implements EventSubscriberInterface
{

    private SystemConfigService $systemConfigService;
    private RequestStack $requestStack;

    public function __construct(SystemConfigService $systemConfigService, \Symfony\Component\HttpFoundation\RequestStack $requestStack)
    {
        $this->systemConfigService = $systemConfigService;
        $this->requestStack = $requestStack;
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
        if (empty($strDomains)) {
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

        // ---- analyze current url
        $currentUrl = $this->requestStack->getMainRequest()->getUri();
        $currentUrlParsed = parse_url($currentUrl);


        // ---- build data for twig
        foreach ($domainNames as $domainName) {
            $exploded = explode('|', $domainName);
            $fqdn = $exploded[0];
            $label = $exploded[1] ?? $fqdn;



            $domains[] = [
                'name'   => $fqdn,
                'label'  => $label,
                'href'   => $this->_getNewUrl($currentUrlParsed, $fqdn),
                'active' => $currentUrlParsed['host'] === $fqdn,
            ];

        }

        // inject data into page
        $event->getPage()->assign(['TopdataDemoshopSwitcherSW6_domains' => $domains]);
    }


    /**
     * source: https://www.php.net/manual/en/function.parse-url.php#106731
     */
    private static function unparse_url(array $parsed_url): string
    {
        $scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host = isset($parsed_url['host']) ? $parsed_url['host'] : '';
        $port = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        $user = isset($parsed_url['user']) ? $parsed_url['user'] : '';
        $pass = isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : '';
        $pass = ($user || $pass) ? "$pass@" : '';
        $path = isset($parsed_url['path']) ? $parsed_url['path'] : '';
        $query = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
        $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';

        return "$scheme$user$pass$host$port$path$query$fragment";

    }


    /**
     * replaces the domain (host)
     * 05/2024 created
     */
    private function _getNewUrl(array $urlParsed, string $newDomainName): string
    {
        $urlParsed['host'] = $newDomainName;

        return self::unparse_url($urlParsed);
    }


}