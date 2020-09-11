<?php

namespace App\Controller;

use App\Api\LaunchApi;
use App\Pagination\Paginator;
use App\Type\LaunchConnectionType;
use App\Type\LaunchType;
use TheCodingMachine\GraphQLite\Annotations\Autowire;
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
	 * @Autowire(for="paginator")
	 */
	public function getLaunches(Paginator $paginator, int $pageSize = 20, string $after = null): LaunchConnectionType
	{
		$allLaunches = $this->launchApi->getAllLaunches();
		$allLaunches = array_reverse($allLaunches);
		/** @var LaunchType[] $launches */
		$launches = $paginator->paginateByCursor($after, $pageSize, $allLaunches);

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

}