<?php

namespace App\Controller\Player;

use App\Services\Player\FindPlayerService;
use App\Services\Player\RemovePlayerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/player", name="player_")
 */
final class DeletePlayerController extends AbstractController
{
    /**
     * @Route("/delete/{id}", name="delete", methods={"POST"})
     * @param FindPlayerService $findPlayerService
     * @param RemovePlayerService $removePlayerService
     * @param int $id
     * @return JsonResponse
     */
    public function __invoke(FindPlayerService $findPlayerService, RemovePlayerService $removePlayerService, int $id): JsonResponse
    {
        $player = $findPlayerService->find($id);
        if (empty($player)) {
            throw $this->createNotFoundException('Player not found');
        }
        try {
            $removePlayerService->remove($player);
            $status = 200;
            $response = 'success';
        } catch (\Exception $exception) {
            $status = 500;
            $response = $exception->getMessage();
        }
        return $this->json([
            'status' => $status,
            'response' => $response,
        ], 200);
    }
}
