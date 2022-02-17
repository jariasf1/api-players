<?php

namespace App\Controller;

use App\Entity\Player;
use App\Services\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/filter", name="filter")
 */
class FilterController extends AbstractController
{
    private $manager;
    private $serializer;

    public function __construct(Manager $manager, SerializerInterface $serializer)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/players-from-team/{team}", name="playersFromTeam")
     */
    public function playersFromTeam(int $team): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $playersArray = $em->getRepository(Player::class)->findByTeam($team);
        $players = $this->serializer->serialize($playersArray, 'json');
        return $this->json([
            'players' => $this->manager->jsonDecode($players)
        ]);
    }

    /**
     * @Route("/players-from-location/{location}", name="playersFromTeamLocation")
     */
    public function playersFromLocation(int $location): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $playersArray = $em->getRepository(Player::class)->findByLocation($location);
        $players = $this->serializer->serialize($playersArray, 'json');
        return $this->json([
            'players' => $this->manager->jsonDecode($players)
        ]);
    }

    /**
     * @Route("/players/team-{team}/location-{location}", name="playersFromLocation")
     */
    public function players(int $team, int $location): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $playersArray = $em->getRepository(Player::class)->findByTeamAndLocation($team, $location);
        $players = $this->serializer->serialize($playersArray, 'json');
        return $this->json([
            'players' => $this->manager->jsonDecode($players)
        ]);
    }
}
