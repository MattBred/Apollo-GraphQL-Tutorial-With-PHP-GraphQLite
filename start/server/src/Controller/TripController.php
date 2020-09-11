<?php

namespace App\Controller;

use App\Api\LaunchApi;
use App\Entity\Trip;
use App\Type\TripUpdateResponseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use TheCodingMachine\GraphQLite\Annotations\Autowire;
use TheCodingMachine\GraphQLite\Annotations\InjectUser;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Types\ID;

class TripController
{

	/**
	 * @Mutation
	 *
	 * @param ID[] $launchIds
	 * @InjectUser(for="user")
	 * @Autowire(for="em")
	 * @Autowire(for="api")
	 *
	 * @return TripUpdateResponseType
	 */
	public function bookTrips(UserInterface $user, EntityManagerInterface $em, LaunchApi $api, array $launchIds): TripUpdateResponseType
	{
		$booked = [];
		$launches = $api->getLaunchesByIds($launchIds);
		$failed = [];

		foreach ($launchIds as $launchId)
		{
			$trip = new Trip($user, $launchId->val());
			$em->persist($trip);
			try
			{
				$em->flush();
				$booked[] = $launchId;
			}
			catch (\Exception $e)
			{
				$failed[] = $launchId;
			}
		}

		$message = count($failed) === 0 ? "trips booked successfully" : "the following launches couldn't be booked: " . implode(", ", $failed);

		return new TripUpdateResponseType(count($booked) === $launchIds, $message, $launches);
	}

	/**
	 * @Mutation
	 * @InjectUser(for="user")
	 * @Autowire(for="em")
	 * @Autowire(for="api")
	 * @return TripUpdateResponseType
	 */
	public function cancelTrip(UserInterface $user, EntityManagerInterface $em, LaunchApi $api, ID $launchId): TripUpdateResponseType
	{
		try
		{
			$trip = $em->getRepository(Trip::class)->findOneBy(['launchId' => $launchId->val()]);
			$em->remove($trip);
			$em->flush();
		}
		catch (\Exception $e)
		{
			return new TripUpdateResponseType(false, "failed to cancel trip");
		}

		$launch = $api->getLaunchById($launchId->val());
		return new TripUpdateResponseType(true, "trip cancelled", [$launch]);
	}

}