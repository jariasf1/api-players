<?php

namespace App\Controller\Player;

use App\Entity\Location;
use App\Entity\Player;
use App\Entity\Team;
use App\Repository\PlayerRepository;
use App\Services\EntityServices\LocationService;
use App\Services\EntityServices\TeamService;
use App\Services\Manager;
use App\Services\Player\FindPlayerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/player", name="player_")
 */
final class EditPlayerController extends AbstractController
{
    /**
     * @Route("/edit/{id}", name="edit", methods={"GET", "POST"})
     * @param Request $request
     * @param Manager $manager
     * @param SerializerInterface $serializer
     * @param FindPlayerService $findPlayerService
     * @param TeamService $teamService
     * @param LocationService $locationService
     * @param int $id
     * @return JsonResponse
     */
    public function __invoke(Request $request, Manager $manager, SerializerInterface $serializer,
                             FindPlayerService $findPlayerService, TeamService $teamService,
                             LocationService $locationService, int $id): JsonResponse
    {
        $player = $findPlayerService->find($id);
        if (empty($player)) {
            throw $this->createNotFoundException('Team not found');
        }
        $arrayTeam = $serializer->serialize($player, 'json');
        $dataResponse = ['team' => $manager->jsonDecode($arrayTeam)];
        if ($request->getMethod() === 'POST') {
            if ($request->get('team') !== null) {
                $team = $teamService->find($request->get('team'));
                $request->request->set('team', $team);
            }
            if ($request->get('location') !== null) {
                $location = $locationService->find($request->get('location'));
                $request->request->set('location', $location);
            }
            $dataResponse = ['status' => 500, 'response' => 'fail', 'team' => null];
            $data = $request->request->all();
            $save = $manager->objectSave($data, $player);
            if (true == $save['result']) {
                $dataResponse = [
                    'status' => 200,
                    'method' => $request->getMethod(),
                    'response' => 'success',
                    'team' => $manager->jsonDecode($serializer->serialize($save['object'], 'json'))
                ];
            }
        }

        return $this->json($dataResponse, 200);
    }

}
