<?php

namespace App\Type;

/**
 * An interface for Doctrine entity types.
 */
interface EntityType
{

	/**
	 * Constructor that must take in a Doctrine entity.
	 *
	 * @param $entity
	 *   The Doctrine entity this entity type will use for its field data.
	 */
	public function __construct($entity);

}
