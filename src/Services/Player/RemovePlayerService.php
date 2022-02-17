<?php


namespace App\Services\Player;

use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;

class RemovePlayerService
{
    /** @var EntityManagerInterface  */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function remove(Player $player)
    {
        $this->em->remove($player);
        $this->em->flush();
    }
}
