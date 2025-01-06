<?php
namespace App\Filters\Role;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterUserRole implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->whereHas('roles', function ($query) use ($value) {
            $query->where('id', $value);
        });

        return $query;

    }
}
?>
