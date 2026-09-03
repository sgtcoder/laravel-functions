<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Stand-in for the consuming application's log route model, which the LogRoute middleware
 * writes through by class name.
 */
class LogRoute extends Model
{
    protected $table = 'log_routes';

    protected $guarded = [];

    protected $casts = [
        'request_headers' => 'array',
        'request_body' => 'array',
        'response_headers' => 'array',
        'response_body' => 'array',
        'total_ms' => 'integer',
    ];

    /** @var list<string> */
    public $ignorable = ['report'];
}
