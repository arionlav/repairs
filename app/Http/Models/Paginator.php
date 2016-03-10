<?php
namespace App\Http\Models;

use Illuminate\Pagination\LengthAwarePaginator as BasePaginator;

/**
 * Class Paginator provide logic for pagination
 *
 * @package App\Http\Models
 */
class Paginator extends BasePaginator
{
    /**
     * Create a new paginator instance.
     *
     * @param  mixed  $items
     * @param  int    $perPage
     * @param  string $path Base path
     * @param  int    $page
     */
    public function __construct($items, $perPage, $path, $page)
    {
        // Set the "real" items that will appear here
        $trueItems = [];

        // That is, add the correct items
        for ($i = $perPage * ($page - 1); $i < min(count($items), $perPage * $page); $i++) {
            $trueItems[] = $items[$i];
        }

        // Set path as provided
        $this->path = $path;

        // Call parent
        parent::__construct($trueItems, count($items), $perPage);

        // Override "guessing" of page
        $this->currentPage = $page;
    }

    /**
     * Get a URL for a given page number.
     *
     * @param  int $page
     * @return string
     */
    public function url($page)
    {
        if ($page <= 0) {
            $page = 1;
        }

        if ($page === 1) {
            // delete '/' from the end path
            $path = substr($this->path, 0, -1);

            return $path;
        }

        return $this->path . config('var.wordInPagination') . $page;
    }
}
