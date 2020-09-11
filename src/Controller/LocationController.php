<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use App\Services\Manager;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/location", name="location_")
 */
class LocationController extends AbstractController
{
    /**
     * @Route(name="index")
     * @param LocationRepository $locationRepository
     * @return JsonResponse
     */
    public function index(LocationRepository $locationRepository)
    {
        return $this->json([
            'locations' => $locationRepository->findHidrateAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"POST"})
     * @param Request $request
     * @param Manager $manager
     * @return JsonResponse
     */
    public function new(Request $request, Manager $manager)
    {
        $status = 500;
        $response = 'fail';
        $location = new Location();
        $data = $request->request->all();
        $save = $manager->objectSave($data, $location);
        if (true == $save) {
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
     */
    public function edit(Request $request, Manager $manager, int $id)
    {
        $status = 500;
        $response = 'fail';
        $em = $this->getDoctrine()->getManager();
        $location = $em->getRepository(Location::class)->find($id);
        if (empty($location)) {
            throw $this->createNotFoundException('Location not found');
        }
        $data = $request->request->all();
        $save = $manager->objectSave($data, $location);
        if (true == $save) {
            $status = 200;
            $response = 'success';
        }
        return $this->json([
            'status' => $status,
            'response' => $response,
        ], 200);
    }

    /**
     * @Route("/delete/{id}", name="edit", methods={"POST"})
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $location = $em->getRepository(Location::class)->find($id);
        if (empty($location)) {
            throw $this->createNotFoundException('Location not found');
        }
        try {
            $em->remove($location);
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
