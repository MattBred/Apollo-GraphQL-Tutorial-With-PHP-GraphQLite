<?php

namespace App\Type;

use App\Enum\PatchSizeEnum;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type(name="TripUpdateResponse")
 */
class TripUpdateResponseType
{

	/**
	 * @Field
	 */
	public function getSuccess(): bool
	{

	}

	/**
	 * @Field
	 */
	public function getMessage(): ?string
	{

	}

	/**
	 * @Field
	 * @return LaunchType[]
	 */
	public function getLaunches(): array
	{

	}
}