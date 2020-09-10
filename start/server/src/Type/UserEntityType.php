<?php

namespace App\Type;

use App\Api\LaunchApi;
use App\Entity;
use Doctrine\ORM\EntityManagerInterface;
use TheCodingMachine\GraphQLite\Annotations\Autowire;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\SourceField;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type(name="User", class=Entity\User::class)
 * @SourceField(name="id")
 * @SourceField(name="email")
 */
class UserEntityType
{

	/**
	 * @Field
	 *
	 * @Autowire(for="entityManager")
	 * @Autowire(for="api")
	 *
	 * @return LaunchType[]
	 */
	public function getTrips(Entity\User $user, EntityManagerInterface $entityManager, LaunchApi $api): array
	{
		$launchIds = $entityManager->getRepository(Entity\User::class)->getLaunchIdsByUser($user);
		if (empty($launchIds))
		{
			return [];
		}
		return $api->getLaunchesByIds($launchIds);
	}

}