<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bridge\Doctrine\RegistryInterface;

class EventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findUntil(\DateTime $dateTime = null)
    {
        $criteria = Criteria::create();
        if (!$dateTime) {
            $dateTime = new \DateTime();
        }

        $criteria->where(Criteria::expr()->lte('date', $dateTime));
        $criteria->orderBy(['date' => 'asc']);
        return $this->matching($criteria);
    }
}
