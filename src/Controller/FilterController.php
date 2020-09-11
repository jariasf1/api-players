<?php

namespace App\Controller;

use App\Entity\Player;
use App\Services\CurrencyManager;
use App\Services\Manager;
use Doctrine\DBAL\Exception\ReadOnlyException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/filter", name="filter")
 */
class FilterController extends AbstractController
{
    /**
     * @Route("/players-from-team/{team}", name="playersFromTeam")
     */
    public function playersFromTeam(Request $request, Manager $manager, SerializerInterface $serializer, int $team)
    {
        $em = $this->getDoctrine()->getManager();
        $playersArray = $em->getRepository(Player::class)->findByTeam($team);
        $players = $serializer->serialize($playersArray, 'json');
        return $this->json([
            'players' => $manager->jsonDecode($players)
        ]);
    }

    /**
     * @Route("/players-from-location/{location}", name="playersFromTeamLocation")
     */
    public function playersFromLocation(Request $request, Manager $manager, SerializerInterface $serializer, int $location)
    {
        $em = $this->getDoctrine()->getManager();
        $playersArray = $em->getRepository(Player::class)->findByLocation($location);
        $players = $serializer->serialize($playersArray, 'json');
        return $this->json([
            'players' => $manager->jsonDecode($players)
        ]);
    }

    /**
     * @Route("/players/team-{team}/location-{location}", name="playersFromLocation")
     */
    public function players(Request $request, Manager $manager, SerializerInterface $serializer, int $team, int $location)
    {
        $em = $this->getDoctrine()->getManager();
        $playersArray = $em->getRepository(Player::class)->findByTeamAndLocation($team, $location);
        $players = $serializer->serialize($playersArray, 'json');
        return $this->json([
            'players' => $manager->jsonDecode($players)
        ]);
    }
}
