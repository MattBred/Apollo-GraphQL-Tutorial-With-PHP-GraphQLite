<?php

namespace App\Type;

use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type(name="TripUpdateResponse")
 */
class TripUpdateResponseType
{

	/**
	 * @var bool
	 */
	protected $success;
	/**
	 * @var string
	 */
	protected $message;
	/**
	 * @var array
	 */
	protected $launches;

	public function __construct(bool $success, string $message, array $launches = [])
	{
		$this->success = $success;
		$this->message = $message;
		$this->launches = $launches;
	}

	/**
	 * @Field
	 */
	public function getSuccess(): bool
	{
		return $this->success;
	}

	/**
	 * @Field
	 */
	public function getMessage(): ?string
	{
		return $this->message;
	}

	/**
	 * @Field
	 * @return LaunchType[]
	 */
	public function getLaunches(): array
	{
		return $this->launches;
	}
}