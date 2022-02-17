<?php

namespace App\Controller\Player;

use App\Entity\Location;
use App\Entity\Player;
use App\Entity\Team;
use App\Repository\PlayerRepository;
use App\Services\EntityServices\LocationService;
use App\Services\EntityServices\TeamService;
use App\Services\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/player", name="player_")
 */
final class NewPlayerController extends AbstractController
{

    /**
     * @Route("/new", name="new", methods={"POST"})
     * @param Request $request
     * @param Manager $manager
     * @param TeamService $teamService
     * @param LocationService $locationService
     * @return JsonResponse
     */
    public function __invoke(Request $request, Manager $manager, TeamService $teamService, LocationService $locationService): JsonResponse
    {
        $status = 500;
        $response = 'fail';
        $player = new Player();
        $team = $teamService->find($request->get('team'));
        $request->request->set('team', $team);
        $location = $locationService->find($request->get('location'));
        $request->request->set('location', $location);
        if (empty($team) || empty($location)) {
            $this->createNotFoundException('No correct Team or Location');
        }
        $data = $request->request->all();
        $save = $manager->objectSave($data, $player);
        if (true == $save['result']) {
            $status = 200;
            $response = 'success';
        }
        return $this->json([
            'status' => $status,
            'response' => $response,
        ], 200);
    }
}
