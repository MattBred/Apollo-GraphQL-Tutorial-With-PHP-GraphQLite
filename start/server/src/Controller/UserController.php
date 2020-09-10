<?php

namespace App\Controller;

use App\Entity;
use Doctrine\ORM\EntityManagerInterface;
use TheCodingMachine\GraphQLite\Annotations\Autowire;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Types\ID;

class UserController
{

	/**
	 * @Query
	 * @Autowire(for="entityManager")
	 */
	public function getUser(ID $id, EntityManagerInterface $entityManager): ?Entity\User
	{
		return $entityManager->getRepository(Entity\User::class)->find($id->val());
	}

}
