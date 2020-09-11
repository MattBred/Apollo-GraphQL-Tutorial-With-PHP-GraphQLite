<?php

namespace App\Pagination;

interface PaginatibleByCursorInterface
{

	/**
	 * Get the pagination cursor for the object.
	 *
	 * @return string
	 */
	public function getCursor(): string;

}
