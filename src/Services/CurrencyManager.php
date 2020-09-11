<?php


namespace App\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CurrencyManager
{
    /** @var HttpClientInterface  */
    private $client;

    /** @var ContainerInterface */
    private $container;

    public function __construct(HttpClientInterface $client, ContainerInterface $container)
    {
        $this->client = $client;
        $this->container = $container;
    }

    public function getCurrencyRateChange(string $currency)
    {
        $rate = 1.00;
        if ($currency !== 'EUR') {
            $response = $this->client->request('GET', $this->container->getParameter('service_current_exchange_rate'));

            $statusCode = $response->getStatusCode();
            if ($statusCode === 200) {
                $content = $response->toArray();
                $rate = $content['rates'][$currency];
            }
        }

        return $rate;
    }
}
