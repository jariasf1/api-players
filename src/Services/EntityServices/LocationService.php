<?php


namespace App\Services\EntityServices;

use App\Entity\Location;
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;

class LocationService
{
    const ENTITY_NAME = Location::class;
    /** @var EntityManagerInterface  */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function find(int $id)
    {
        return $this->em->getRepository(self::ENTITY_NAME)->find($id);
    }

    /**
     */
    public function remove(Player $player)
    {
        $this->em->remove($player);
        $this->em->flush();
    }
}
