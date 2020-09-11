<?php

namespace App\Type;

use App\Entity\User;
use App\Pagination\PaginatibleByCursorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use TheCodingMachine\GraphQLite\Annotations\Autowire;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\InjectUser;
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Types\ID;

/**
 * @Type(name="Launch")
 */
class LaunchType implements PaginatibleByCursorInterface
{

	/**
	 * @var ID
	 */
	protected $id;
	/**
	 * @var bool
	 */
	protected $isBooked;
	/**
	 * @var string|null
	 */
	protected $site;
	/**
	 * @var MissionType|null
	 */
	protected $mission;
	/**
	 * @var RocketType|null
	 */
	protected $rocket;
	/**
	 * @var string
	 */
	private $cursor;

	public function __construct(ID $id, string $cursor, string $site = null, MissionType $mission = null, RocketType $rocket = null)
	{
		$this->id = $id;
		$this->cursor = $cursor;
		$this->site = $site;
		$this->mission = $mission;
		$this->rocket = $rocket;
	}

	/**
	 * @Field
	 */
	public function getId(): ID
	{
		return $this->id;
	}

	/**
	 * @Field
	 */
	public function getSite(): ?string
	{
		return $this->site;
	}

	/**
	 * @Field
	 */
	public function getMission(): ?MissionType
	{
		return $this->mission;
	}

	/**
	 * @Field
	 */
	public function getRocket(): ?RocketType
	{
		return $this->rocket;
	}

	public function getCursor(): string
	{
		return $this->cursor;
	}

	/**
	 * @Field
	 * @Autowire(for="em")
	 * @InjectUser(for="user")
	 */
	public function getIsBooked(EntityManagerInterface $em, ?UserInterface $user): bool
	{
		if (!$user instanceof UserInterface)
		{
			return false;
		}
		return $em->getRepository(User::class)->isBookedOnLaunch($user, (int)$this->id->val());
	}
}