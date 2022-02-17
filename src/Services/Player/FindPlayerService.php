<?php


namespace App\Services\Player;

use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;

class FindPlayerService
{
    const ENTITY_NAME = Player::class;
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
}
