<?php

namespace App\Controller;

use App\Entity\Team;
use App\Repository\TeamRepository;
use App\Services\EntityServices\TeamService;
use App\Services\Manager;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/team", name="team_")
 */
class TeamController extends AbstractController
{
    /**
     * @Route(name="index")
     * @param TeamRepository $teamRepository
     * @return JsonResponse
     */
    public function index(TeamRepository $teamRepository): JsonResponse
    {
        return $this->json([
            'teams' => $teamRepository->findHidrateAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"POST"})
     * @param Request $request
     * @param Manager $manager
     * @return JsonResponse
     */
    public function new(Request $request, Manager $manager): JsonResponse
    {
        $status = 500;
        $response = 'fail';
        $team = new Team();
        $data = $request->request->all();
        $save = $manager->objectSave($data, $team);
        if (true == $save['result']) {
            $status = 200;
            $response = 'success';
        }
        return $this->json([
            'status' => $status,
            'response' => $response,
        ], 200);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET", "POST"})
     * @param Request $request
     * @param Manager $manager
     * @param int $id
     * @return JsonResponse
     * @throws ReflectionException
     */
    public function edit(Request $request, Manager $manager, TeamService $teamService, int $id): JsonResponse
    {
        $team = $teamService->find($id);
        if (empty($team)) {
            throw $this->createNotFoundException('Team not found');
        }
        $arrayTeam = $manager->dismount($team);
        $dataResponse = ['team' => $arrayTeam];
        if ($request->getMethod() === 'POST') {
            $dataResponse = ['status' => 500, 'response' => 'fail', 'team' => null];
            $data = $request->request->all();
            $save = $manager->objectSave($data, $team);
            if (true == $save['result']) {
                $dataResponse = [
                    'status' => 200,
                    'method' => $request->getMethod(),
                    'response' => 'success',
                    'team' => $manager->dismount($save['object'])
                ];
            }
        }

        return $this->json($dataResponse, 200);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"POST"})
     * @param TeamService $teamService
     * @param int $id
     * @return JsonResponse
     */
    public function delete(TeamService $teamService, int $id): JsonResponse
    {
        $team = $teamService->find($id);
        if (empty($team)) {
            throw $this->createNotFoundException('Team not found');
        }
        try {
            $teamService->remove($team);
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
