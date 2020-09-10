<?php

namespace App\Type;

use App\Api\LaunchApi;
use App\Entity;
use Doctrine\ORM\EntityManagerInterface;
use TheCodingMachine\GraphQLite\Annotations\Autowire;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type(name="User")
 */
class UserType implements EntityType
{

	/** @var Entity\User */
	protected $user;

	public function __construct($entity)
	{
		$this->user = $entity;
	}

	/**
	 * @Field
	 */
	public function getEmail(): string
	{
		return $this->user->getEmail();
	}

	/**
	 * @Field
	 */
	public function getUsername(): string
	{
		return $this->user->getUsername();
	}

	/**
	 * @Field
	 * 
	 * @return string[]
	 */
	public function getRoles(): array
	{
		return $this->user->getRoles();
	}

	/**
	 * @Field
	 *
	 * @Autowire(for="entityManager")
	 * @Autowire(for="api")
	 *
	 * @return LaunchType[]
	 */
	public function getTrips(EntityManagerInterface $entityManager, LaunchApi $api): array
	{
		$launchIds = $entityManager->getRepository(Entity\User::class)->getLaunchIdsByUser($this->user);
		if (empty($launchIds))
		{
			return [];
		}
		return $api->getLaunchesByIds($launchIds);
	}

}