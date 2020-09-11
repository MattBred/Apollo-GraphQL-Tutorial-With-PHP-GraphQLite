<?php

namespace App\Entity;

use App\Repository\TripRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=TripRepository::class)
 */
class Trip
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity=User::class, inversedBy="trips")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $user;

	/**
	 * @ORM\Column(type="integer")
	 */
	private $launchId;

	public function __construct(UserInterface $user, int $launchId)
	{
		$this->user = $user;
		$this->launchId = $launchId;
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getUser(): ?UserInterface
	{
		return $this->user;
	}

	public function setUser(?User $user): self
	{
		$this->user = $user;

		return $this;
	}

	public function getLaunchId(): ?int
	{
		return $this->launchId;
	}

	public function setLaunchId(int $launchId): self
	{
		$this->launchId = $launchId;

		return $this;
	}
}
