<?php


namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Manager
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var ContainerInterface */
    protected $container;


    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    /**
     * @param $data
     * @param $entity
     * @param false $edit
     * @return array
     */
    public function objectSave($data, $entity, $edit = false)
    {
        $result = [];
        $save = false;
        $em = $this->entityManager;
        $exist = $this->fromData($entity, $data);
        if ($exist) {
            $em->persist($entity);
            $em->flush();
            $save = true;
        }
        return ['result' => $save, 'object' => $entity];
    }

    /**
     * @param $entity
     * @param $data
     * @return bool
     */
    public function fromData($entity, $data)
    {
        $exist = false;

        foreach ($data as $key => $value) {
            $attr = \ucfirst($key);
            $method = 'set'.$attr;
            if (\method_exists($entity, $method)) {
                $entity->$method($value);
                $exist = true;
                continue;
            }
        }

        return $exist;
    }

    /**
     * @param $object
     *
     * @return array
     *
     * @throws \ReflectionException
     */
    public function dismount($object)
    {
        $reflectionClass = new \ReflectionClass(\get_class($object));
        $array = [];
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($object);
            $property->setAccessible(false);
        }

        return $array;
    }
}
