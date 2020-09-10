<?php

namespace App\Type;

use App\Enum\PatchSizeEnum;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type(name="Mission")
 */
class MissionType
{

	/**
	 * @var string|null
	 */
	protected $name;
	
	protected $missionPatchSmall;
	
	protected $missionPatchLarge;

	public function __construct(string $name = null, string $missionPatchSmall = null, string $missionPatchLarge = null)
	{
		$this->name = $name;
		$this->missionPatchSmall = $missionPatchSmall;
		$this->missionPatchLarge = $missionPatchLarge;
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
	public function getMissionPatch(PatchSizeEnum $size): ?string
	{
		if ($size == PatchSizeEnum::SMALL()) {
			return $this->missionPatchSmall;
		}
		if ($size == PatchSizeEnum::LARGE()) {
			return $this->missionPatchLarge;
		}
		return null;
	}
}