<?php

namespace Helper;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CacheHelper
{
    /**
     * @var array $data
     */
    public static function cachePaginatedData(array $data, string $tag_name): void
    {
        $data = collect($data);
        $page_size = 15;
        $total_pages = ($data->count() / $page_size) + 1;

        for ($page = 1; $page <= $total_pages; $page++) {
            $paginated_data = $data->splice(0, $page_size);
            Cache::put($tag_name . '-' . $page, $paginated_data, now()->addWeek());
        }
    }

    public static function retrieveCachedPaginatedData(string $tag_name, int $requested_page): ?Collection
    {
        $cached_data = Cache::get($tag_name . '-' . $requested_page);
        return $cached_data ? $cached_data : null;
    }

    public static function forgetCachedPaginatedData(string $tag_name, int $requested_page): bool
    {
        return Cache::forget($tag_name . '-' . $requested_page);
    }
}
