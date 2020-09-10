<?php

namespace App\Controller;

use App\Entity\User;
use App\Type\UserType;
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
	public function getUser(ID $id, EntityManagerInterface $entityManager): ?UserType
	{
		$user = $entityManager->getRepository(User::class)->find($id->val());
		return $user ? new UserType($user) : null;
	}

}
