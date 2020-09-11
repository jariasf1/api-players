<?php


namespace App\EventListeners;

use App\Entity\Player;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SetPriceRate
{
    /** @var RequestStack */
    protected $requestStack;

    /** @var HttpClientInterface  */
    private $client;

    /** @var ContainerInterface */
    private $container;

    public function __construct(RequestStack $requestStack, HttpClientInterface $client, ContainerInterface $container)
    {
        $this->requestStack = $requestStack;
        $this->client = $client;
        $this->container = $container;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $currency = $this->requestStack->getCurrentRequest()->get('currency', $this->container->getParameter('default_currency'));
        if ($entity instanceof Player && $currency !== $this->container->getParameter('default_currency')) {
            $rate = $this->getCurrencyRateChange($currency);
            $newPrice = $entity->getPrice() * $rate;
            $entity->setPrice($newPrice);
        }
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
