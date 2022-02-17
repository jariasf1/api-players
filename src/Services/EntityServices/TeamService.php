<?php


namespace App\Services\EntityServices;

use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;

class TeamService
{
    const ENTITY_NAME = Team::class;

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

    public function remove(Team $player)
    {
        $this->em->remove($player);
        $this->em->flush();
    }
}
