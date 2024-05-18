<?php


namespace Topdata\DemoshopSwitcherSW6\Service;

use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * 05/2024 created
 */
class DemoshopSwitcherService
{


    private SystemConfigService $systemConfigService;
    private RequestStack $requestStack;
    private DemoshopSwitcherService $demoshopSwitcherService;

    public function __construct(SystemConfigService $systemConfigService, RequestStack $requestStack, DemoshopSwitcherService $demoshopSwitcherService)
    {
        $this->systemConfigService = $systemConfigService;
        $this->requestStack = $requestStack;
    }






    public function getDomains()
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

        $ret = [];

        // ---- analyze current url
        $currentUrl = $this->requestStack->getMainRequest()->getUri();
        $currentUrlParsed = parse_url($currentUrl);


        // ---- build data for twig
        foreach ($domainNames as $domainName) {
            $exploded = explode('|', $domainName);
            $fqdn = $exploded[0];
            $label = $exploded[1] ?? $fqdn;



            $ret[] = [
                'name'   => $fqdn,
                'label'  => $label,
                'href'   => $this->_getNewUrl($currentUrlParsed, $fqdn),
                'active' => $currentUrlParsed['host'] === $fqdn,
            ];

        }

        return $ret;
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