<?php

namespace App\Type;

use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Types\ID;

/**
 * @Type(name="Rocket")
 * 
 */
class RocketType
{

	/**
	 * @var ID
	 */
	protected $id;
	/**
	 * @var string|null
	 */
	protected $name;
	/**
	 * @var string|null
	 */
	protected $type;

	public function __construct(ID $id, string $name = null, string $type = null)
	{

		$this->id = $id;
		$this->name = $name;
		$this->type = $type;
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
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * @Field
	 */
	public function getType(): ?string
	{
		return $this->type;
	}
}