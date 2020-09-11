<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\Player;
use App\Entity\Team;
use App\Repository\PlayerRepository;
use App\Services\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/player", name="player_")
 */
class PlayerController extends AbstractController
{
    /**
     * @Route(name="index")
     * @param PlayerRepository $playerRepository
     * @return JsonResponse
     */
    public function index(PlayerRepository $playerRepository, SerializerInterface $serializer)
    {
        $allPlayers = $playerRepository->findAll();
        $players = $serializer->serialize($allPlayers, 'json');
        return $this->json([
            'players' => json_decode($players),
        ], 200);
    }

    /**
     * @Route("/new", name="new", methods={"POST"})
     * @param Request $request
     * @param Manager $manager
     * @return JsonResponse
     */
    public function new(Request $request, Manager $manager)
    {
        $em = $this->getDoctrine()->getManager();
        $status = 500;
        $response = 'fail';
        $player = new Player();
        $team = $em->getRepository(Team::class)->find($request->get('team'));
        $request->request->set('team', $team);
        $location = $em->getRepository(Location::class)->find($request->get('location'));
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

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET", "POST"})
     * @param Request $request
     * @param Manager $manager
     * @param SerializerInterface $serializer
     * @param int $id
     * @return JsonResponse
     */
    public function edit(Request $request, Manager $manager, SerializerInterface $serializer, int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $player = $em->getRepository(Player::class)->find($id);
        if (empty($player)) {
            throw $this->createNotFoundException('Team not found');
        }
        $arrayTeam = $serializer->serialize($player, 'json');
        $dataResponse = ['team' => $manager->jsonDecode($arrayTeam)];
        if ($request->getMethod() === 'POST') {
            if ($request->get('team') !== null) {
                $team = $em->getRepository(Team::class)->find($request->get('team'));
                $request->request->set('team', $team);
            }
            if ($request->get('location') !== null) {
                $location = $em->getRepository(Location::class)->find($request->get('location'));
                $request->request->set('location', $location);
            }
            $dataResponse = ['status' => 500, 'response' => 'fail', 'team' => null];
            $data = $request->request->all();
            $save = $manager->objectSave($data, $player);
            if (true == $save['result']) {
                $dataResponse = ['status' => 200, 'method' => $request->getMethod(), 'response' => 'success', 'team' => $manager->jsonDecode($serializer->serialize($save['object'], 'json'))];
            }
        }

        return $this->json($dataResponse, 200);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"POST"})
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $player = $em->getRepository(Player::class)->find($id);
        if (empty($player)) {
            throw $this->createNotFoundException('Player not found');
        }
        try {
            $em->remove($player);
            $em->flush();
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
