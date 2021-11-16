<?php

declare(strict_types=1);

namespace App\Repositories;

/**
 * The generic repository interface
 */
interface BaseRepositoryInterface
{
    /**
     * The search method.
     * 
     * @param string $value The value to search.
     * 
     * @return BaseRepository The repository.
     */
    public function search(string $value = ''): BaseRepository;

    /**
     * The count method.
     * 
     * @return int The total count of selected data.
     */
    public function count(): int;

    /**
     * The order by method.
     * 
     * @param string $key The key to order.
     * @param string $sortMethod The sort method to use.
     * 
     * @return BaseRepository The repository.
     */
    public function orderBy(string $key = 'id', $sortMethod = 'DESC'): BaseRepository;

    /**
     * The paginate method.
     * 
     * @param int $limit The limit per page.
     * @param int $pageNumber The current page number.
     * 
     * @return BaseRepository The repository.
     */
    public function paginate(int $limit = 5, int $pageNumber = 1): BaseRepository;

    /**
     * The fetch all method.
     * 
     * @return iterable The result from selected data.
     */
    public function fetchAll(): iterable;
}
