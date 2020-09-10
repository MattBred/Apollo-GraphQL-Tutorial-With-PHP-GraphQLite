<?php

namespace App\Controller;

use App\Type\LaunchType;
use App\Type\TripUpdateResponseType;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Types\ID;

class TripController
{

	/**
	 * @Mutation
	 *
	 * @param ID[] $launchIds
	 *
	 * @return TripUpdateResponseType
	 */
	public function bookTrips(array $launchIds): TripUpdateResponseType
	{
		
	}

	/**
	 * @Mutation
	 * @return TripUpdateResponseType
	 */
	public function cancelTrip(ID $id): TripUpdateResponseType
	{
		
	}

}