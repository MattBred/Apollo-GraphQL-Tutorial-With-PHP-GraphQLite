<?php

namespace App\Pagination;

class Paginator
{

	/**
	 * Paginates by cursor.
	 *
	 * @param string|null $cursor
	 * @param int $pageSize
	 * @param PaginatibleByCursorInterface[] $results
	 *
	 * @return PaginatibleByCursorInterface[]
	 */
	public function paginateByCursor(?string $cursor, int $pageSize, array $results): array
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