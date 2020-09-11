<?php

namespace App\Type;

use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type
 */
class LaunchConnectionType
{

	/**
	 * @var string
	 */
	protected $cursor;
	/**
	 * @var bool
	 */
	protected $hasMore;
	/**
	 * @var array
	 */
	protected $launches;

	public function __construct(?string $cursor, bool $hasMore, array $launches)
	{
		$this->cursor = $cursor;
		$this->hasMore = $hasMore;
		$this->launches = $launches;
	}

	/**
	 * @Field
	 */
	public function getCursor(): ?String
	{
		return $this->cursor;
	}

	/**
	 * @Field
	 */
	public function hasMore(): bool
	{
		return $this->hasMore;
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