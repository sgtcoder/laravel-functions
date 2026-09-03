<?php

namespace SgtCoder\LaravelFunctions\Tests\Unit\Middleware;

use Illuminate\Database\Schema\Blueprint;
use PHPUnit\Framework\Attributes\Test;
use SgtCoder\LaravelFunctions\Jobs\WriteRouteLog;
use SgtCoder\LaravelFunctions\Middleware\LogRoute;
use SgtCoder\LaravelFunctions\Tests\TestCase;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Illuminate\Http\{
    JsonResponse,
    Request,
    UploadedFile
};
use Illuminate\Support\Facades\{
    Queue,
    Schema
};

/**
 * The middleware persists through the consuming application's App\Models\LogRoute, so the
 * suite stands one up against the in-memory database.
 */
class LogRouteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('log_routes', function (Blueprint $table) {
            $table->id();
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('api_provider')->nullable();
            $table->string('uri');
            $table->text('request_headers')->nullable();
            $table->text('request_body')->nullable();
            $table->string('method');
            $table->string('ip')->nullable();
            $table->string('http_code');
            $table->unsignedInteger('total_ms')->nullable();
            $table->text('response_headers')->nullable();
            $table->text('response_body')->nullable();
            $table->boolean('processed')->default(false);
            $table->timestamps();
        });
    }

    private function run_middleware(Request $request, mixed $response = null): mixed
    {
        $response ??= new JsonResponse(['ok' => true]);

        return (new LogRoute)->handle($request, fn() => $response);
    }

    private function rows(): \Illuminate\Support\Collection
    {
        return \App\Models\LogRoute::query()->get();
    }

    #[Test]
    public function it_writes_inline_by_default()
    {
        Queue::fake();

        $this->run_middleware(Request::create('https://example.test/v1/thing', 'POST', ['a' => 1]));

        $this->assertCount(1, $this->rows());
        Queue::assertNothingPushed();
    }

    #[Test]
    public function it_dispatches_instead_of_writing_in_queue_mode()
    {
        Queue::fake();
        config()->set('laravel-functions.log_route.mode', 'queue');
        config()->set('laravel-functions.log_route.connection', 'redis');
        config()->set('laravel-functions.log_route.queue', 'log_route');

        $this->run_middleware(Request::create('https://example.test/v1/thing', 'POST', ['a' => 1]));

        $this->assertCount(0, $this->rows(), 'queue mode must not write on the request thread');

        Queue::assertPushed(WriteRouteLog::class, function (WriteRouteLog $job) {
            return $job->queue === 'log_route'
                && $job->connection === 'redis'
                && $job->attributes['uri'] === 'https://example.test/v1/thing';
        });
    }

    /**
     * The payload has to survive serialisation, which is the whole reason it is a plain
     * array rather than the Request and Response objects.
     */
    #[Test]
    public function it_queues_a_serialisable_payload()
    {
        Queue::fake();
        config()->set('laravel-functions.log_route.mode', 'queue');

        $this->run_middleware(Request::create('https://example.test/v1/thing', 'POST', ['a' => 1]));

        Queue::assertPushed(WriteRouteLog::class, function (WriteRouteLog $job) {
            serialize($job);

            return true;
        });
    }

    #[Test]
    public function it_defers_the_write_until_termination()
    {
        config()->set('laravel-functions.log_route.mode', 'after_response');

        $this->run_middleware(Request::create('https://example.test/v1/thing', 'POST'));

        $this->assertCount(0, $this->rows(), 'the write must not happen before termination');

        $this->app->terminate();

        $this->assertCount(1, $this->rows());
    }

    #[Test]
    public function it_redacts_the_authorization_header()
    {
        $request = Request::create('https://example.test/v1/thing', 'POST');
        $request->headers->set('Authorization', 'Bearer super-secret-token');
        $request->headers->set('X-Api-Key', 'another-secret');

        $this->run_middleware($request);

        $headers = $this->rows()->first()->request_headers;

        $this->assertSame(['REDACTED'], $headers['authorization']);
        $this->assertSame(['REDACTED'], $headers['x-api-key']);
        $this->assertStringNotContainsString('super-secret-token', json_encode($headers));
    }

    /**
     * all() merges uploaded files, whose temp files are gone by the time a worker runs.
     */
    #[Test]
    public function it_keeps_uploaded_files_out_of_the_payload()
    {
        Queue::fake();
        config()->set('laravel-functions.log_route.mode', 'queue');

        $request = Request::create(
            'https://example.test/v1/upload',
            'POST',
            ['note' => 'hello'],
            [],
            ['report' => UploadedFile::fake()->create('report.pdf', 1)]
        );

        $this->run_middleware($request);

        Queue::assertPushed(WriteRouteLog::class, function (WriteRouteLog $job) {
            $encoded = serialize($job->attributes);

            $this->assertStringNotContainsString('UploadedFile', $encoded);
            $this->assertSame('hello', $job->attributes['request_body']['note']);

            return true;
        });
    }

    /**
     * getContent() returns false for a StreamedResponse; strlen(false) is a PHP 8.5
     * deprecation that the middleware's Exception catch would not have caught.
     */
    #[Test]
    public function it_handles_a_streamed_response()
    {
        $streamed = new StreamedResponse(fn() => print('chunk'));

        $this->run_middleware(Request::create('https://example.test/v1/stream', 'GET'), $streamed);

        $this->assertCount(1, $this->rows());
        $this->assertNull($this->rows()->first()->response_body);
    }

    #[Test]
    public function it_can_disable_response_body_storage()
    {
        config()->set('laravel-functions.log_route.store_response_body', false);

        $this->run_middleware(Request::create('https://example.test/v1/thing', 'GET'));

        $this->assertNull($this->rows()->first()->response_body);
    }
}
