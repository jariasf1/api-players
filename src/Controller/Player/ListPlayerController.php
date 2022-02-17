<?php

namespace App\Controller\Player;

use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/player", name="player_")
 */
final class ListPlayerController extends AbstractController
{
    /**
     * @Route(name="index")
     * @param PlayerRepository $playerRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function __invoke(PlayerRepository $playerRepository, SerializerInterface $serializer): JsonResponse
    {
        $allPlayers = $playerRepository->findAll();
        $players = $serializer->serialize($allPlayers, 'json');
        return $this->json([
            'players' => json_decode($players),
        ], 200);
    }
}
