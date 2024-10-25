<?php

namespace SgtCoder\LaravelFunctions\Providers;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

use Illuminate\Support\{
    Facades\Blade,
    Arr,
    ServiceProvider as BaseServiceProvider
};

class CustomServiceProvider extends BaseServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Force Route SSL
        if (!env('DISABLE_SSL', FALSE)) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Macro for simple where
        Builder::macro('if', function ($condition, $column, $operator, $value) {
            if ($condition) {
                /** @phpstan-ignore-next-line */
                return $this->where($column, $operator, $value);
            }

            return $this;
        });

        Builder::macro('whereLike', function ($column, $search) {
            /** @phpstan-ignore-next-line */
            return $this->where($column, 'LIKE', "%{$search}%");
        });

        Builder::macro('orWhereLike', function ($column, $search) {
            /** @phpstan-ignore-next-line */
            return $this->orWhere($column, 'LIKE', "%{$search}%");
        });

        Builder::macro('whereLikeRaw', function ($column, $search) {
            /** @phpstan-ignore-next-line */
            return $this->whereRaw($column . ' LIKE "%' . $search . '%"');
        });

        Builder::macro('orWhereLikeRaw', function ($column, $search) {
            /** @phpstan-ignore-next-line */
            return $this->orWhereRaw($column . ' LIKE "%' . $search . '%"');
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('return', function () {
            return "<?php return; ?>";
        });

        Blade::directive('nl2br', function ($string) {
            return "<?php echo nl2br($string); ?>";
        });

        Blade::directive('var', function ($key) {
            return "<?php " . $key . "; ?>";
        });

        Blade::directive('todo', function ($key) {
            return NULL;
        });

        Blade::directive('svg', function ($filepath) {
            return "<?php echo file_get_contents(public_path(" . $filepath . ")); ?>";
        });

        Blade::anonymousComponentPath(resource_path('views/emails/components'), 'email');

        // https://github.com/korridor/laravel-has-many-sync
        HasMany::macro('sync', function (array $data, bool $deleting = true): array {
            $changes = [
                'created' => [],
                'deleted' => [],
                'updated' => [],
            ];

            /** @var HasMany $this */

            // Get the primary key.
            $relatedKeyName = $this->getRelated()->getKeyName();

            // Get the current key values.
            $current = $this->newQuery()->pluck($relatedKeyName)->all();

            // Cast the given key to an integer if it is numeric.
            $castKey = function ($value) {
                if (is_null($value)) {
                    return $value;
                }

                return is_numeric($value) ? (int) $value : (string) $value;
            };

            // Cast the given keys to integers if they are numeric and string otherwise.
            $castKeys = function ($keys) use ($castKey) {
                return (array) array_map(function ($key) use ($castKey) {
                    return $castKey($key);
                }, $keys);
            };

            // Get any non-matching rows.
            $deletedKeys = array_diff($current, $castKeys(Arr::pluck($data, $relatedKeyName)));

            if ($deleting && count($deletedKeys) > 0) {
                $this->getRelated()->destroy($deletedKeys);
                $changes['deleted'] = $deletedKeys;
            }

            // Separate the submitted data into "update" and "new"
            // We determine "newRows" as those whose $relatedKeyName (usually 'id') is null.
            $newRows = Arr::where($data, function ($row) use ($relatedKeyName) {
                return null === Arr::get($row, $relatedKeyName);
            });

            // We determine "updateRows" as those whose $relatedKeyName (usually 'id') is set, not null.
            $updatedRows = Arr::where($data, function ($row) use ($relatedKeyName) {
                return null !== Arr::get($row, $relatedKeyName);
            });

            if (count($newRows) > 0) {
                $newRecords = $this->createMany($newRows);
                $changes['created'] = $castKeys(
                    $newRecords->pluck($relatedKeyName)->toArray()
                );
            }

            foreach ($updatedRows as $row) {
                $this->getRelated()
                    ->where($relatedKeyName, $castKey(Arr::get($row, $relatedKeyName)))
                    ->first()
                    ->update($row);
            }

            $changes['updated'] = $castKeys(Arr::pluck($updatedRows, $relatedKeyName));

            return $changes;
        });
    }
}
