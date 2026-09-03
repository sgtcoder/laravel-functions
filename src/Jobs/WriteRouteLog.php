<?php

namespace SgtCoder\LaravelFunctions\Jobs;

use App\Models\LogRoute as LogRouteModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Writes one log_routes row off the request thread.
 *
 * Takes a plain array. Never pass the Request or Response: both hold closures that
 * will not serialise.
 */
class WriteRouteLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /** The request already succeeded; a failed log write must not be retried. */
    public int $tries = 1;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function __construct(public array $attributes) {}

    public function handle(): void
    {
        // @phpstan-ignore-next-line - the model lives in the consuming application
        LogRouteModel::create($this->attributes);
    }
}
