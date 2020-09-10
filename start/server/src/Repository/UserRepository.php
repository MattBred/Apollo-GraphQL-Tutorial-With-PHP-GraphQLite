<?php

namespace App\Repository;

use App\Entity\Trip;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method UserInterface|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserInterface|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserInterface[]    findAll()
 * @method UserInterface[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, User::class);
	}

	public function isBookedOnLaunch(UserInterface $user, int $launchId)
	{
		return !empty(
			$this->_em->createQueryBuilder()
			->select('t')
			->from(Trip::class, 't')
			->andWhere('t.launchId = :launchId')
			->andWhere('t.user = :user')
			->setParameter('launchId', $launchId)
			->setParameter('user', $user)
			->getQuery()
			->getArrayResult()
		);
	}
	
	public function getLaunchIdsByUser(UserInterface $user)
	{
		return $this->_em->createQueryBuilder()
			->select('t.launchId')
			->from(Trip::class, 't')
			->andWhere('t.user = :user')
			->setParameter('user', $user)
			->getQuery()
			->getArrayResult();
	}
}
