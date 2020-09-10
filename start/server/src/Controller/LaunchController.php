<?php

namespace App\Controller;

use App\Api\LaunchApi;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Type\LaunchConnectionType;
use App\Type\LaunchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Types\ID;

class LaunchController
{

	/**
	 * @var LaunchApi
	 */
	protected $launchApi;

	public function __construct(LaunchApi $launchApi)
	{
		$this->launchApi = $launchApi;
	}

	/**
	 * @Query
	 */
	public function getLaunches(int $pageSize = 20, string $after = null): LaunchConnectionType
	{
		$allLaunches = $this->launchApi->getAllLaunches();
		$allLaunches = array_reverse($allLaunches);
		/** @var LaunchType[] $launches */
		$launches = $this->paginateResults($after, $pageSize, $allLaunches);

		$cursor = !empty($launches) ? $launches[count($launches) - 1]->getCursor() : null;
		$hasMore = !empty($launches) ? $launches[count($launches) - 1]->getCursor() !== $allLaunches[count($allLaunches) - 1]->getCursor() : false;

		return new LaunchConnectionType($cursor, $hasMore, $launches);
	}

	/**
	 * @Query
	 * @return LaunchType
	 */
	public function getLaunch(ID $id): LaunchType
	{
		return $this->launchApi->getLaunchById($id);
	}

	/**
	 * @param string|null $cursor
	 * @param int $pageSize
	 * @param LaunchType[] $results
	 *
	 * @return LaunchType[]
	 */
	protected function paginateResults(?string $cursor, int $pageSize, array $results): array
	{
		if ($pageSize < 1)
		{
			return [];
		}
		if (!$cursor)
		{
			return array_slice($results, 0, $pageSize);
		}

		$cursorIndex = null;
		foreach ($results as $idx => $item)
		{
			if ($item->getCursor() === $cursor)
			{
				$cursorIndex = $idx;
				break;
			}
		}
		if (!$cursorIndex || $cursorIndex === 0)
		{
			return array_slice($results, 0, $pageSize);
		}

		if ($cursorIndex === count($results) - 1)
		{
			return [];
		}

		return array_slice($results, $cursorIndex + 1, $pageSize);
	}

}