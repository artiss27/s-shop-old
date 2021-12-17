<?php

namespace App\Utils\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class AbstractBaseManager
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    abstract public function getRepository(): ObjectRepository;

    /**
     * @param string $id
     * @return object|null
     */
    public function find(string $id): ?object
    {
        return $this->getRepository()->find($id);
    }

    /**
     * @param string $id
     * @return object|null
     */
    public function get(string $id): ?object
    {
        if (!$result = $this->getRepository()->find($id)) {
            throw new NotFoundHttpException("Object with this id was not found");
        }
        return $result;
    }

    /**
     * @param object $entity
     */
    public function save(object $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * @param object $entity
     */
    public function remove(object $entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
}
