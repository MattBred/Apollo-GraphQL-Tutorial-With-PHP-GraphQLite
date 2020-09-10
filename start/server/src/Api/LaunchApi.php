<?php

namespace App\Api;

use App\Type\LaunchType;
use App\Type\MissionType;
use App\Type\RocketType;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use TheCodingMachine\GraphQLite\Types\ID;

class LaunchApi
{
	
	const BASE_URL = "https://api.spacexdata.com/v2/";
	
	/**
	 * @var \Symfony\Contracts\HttpClient\HttpClientInterface
	 */
	private $client;

	public function __construct()
	{
		$this->client = HttpClient::create();
	}

	/**
	 * @return LaunchType[]
	 */
	public function getAllLaunches(): array
	{
		try
		{
			$launches = $this->get('launches')->toArray();
			return array_reduce($launches, [$this, "launchReducer"], []);
		}
		catch (\Throwable $e)
		{
			return [];
		}
	}
	
	public function getLaunchById($launchId): ?LaunchType
	{
		try
		{
			$launches = $this->get('launches', ['flight_number' => (string)$launchId])->toArray();
			return array_reduce($launches, [$this, "launchReducer"], [])[0];
		}
		catch (\Throwable $e)
		{
			return null;
		}
	}
	
	public function getLaunchesByIds(array $launchIds)
	{
		return array_map(function($launchId) {return $this->getLaunchById($launchId);}, $launchIds);
	}
	
	protected function launchReducer(array $launches, array $launch): array
	{
		$mission = new MissionType($launch['mission_name'], $launch['links']['mission_patch_small'], $launch['links']['mission_patch']);
		$rocket = new RocketType(new ID($launch['rocket']['rocket_id']), $launch['rocket']['rocket_name'], $launch['rocket']['rocket_type']);
		$launches[] = new LaunchType(new ID($launch['flight_number'] ?? 0), $launch['launch_date_unix'], $launch['launch_site']['site_name'] ?? null, $mission, $rocket);
		return $launches;
	}
	
	public function get(string $url, array $query = []): ResponseInterface
	{
		$options['query'] = $query;
		return $this->client->request("GET", static::BASE_URL . $url, $options);
	}
	
}