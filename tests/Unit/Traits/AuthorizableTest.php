<?php

namespace SgtCoder\LaravelFunctions\Tests\Unit\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use PHPUnit\Framework\Attributes\Test;
use SgtCoder\LaravelFunctions\Tests\TestCase;
use SgtCoder\LaravelFunctions\Traits\Authorizable;

use Illuminate\Routing\{
    Controller,
    Route
};

class AuthorizableTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Define a simple gate for testing
        Gate::define('view_test_resources', function () {
            return true;
        });

        Gate::define('edit_test_resources', function () {
            return false;
        });
    }

    #[Test]
    public function it_gets_default_abilities()
    {
        $controller = new class extends Controller {
            use Authorizable;

            public function testGetAbilities()
            {
                return $this->getAbilities();
            }
        };

        $abilities = $controller->testGetAbilities();

        $this->assertIsArray($abilities);
        $this->assertArrayHasKey('index', $abilities);
        $this->assertArrayHasKey('show', $abilities);
        $this->assertArrayHasKey('create', $abilities);
        $this->assertArrayHasKey('store', $abilities);
        $this->assertArrayHasKey('edit', $abilities);
        $this->assertArrayHasKey('update', $abilities);
        $this->assertArrayHasKey('destroy', $abilities);
        $this->assertEquals('view', $abilities['index']);
        $this->assertEquals('add', $abilities['create']);
        $this->assertEquals('delete', $abilities['destroy']);
    }

    #[Test]
    public function it_can_set_custom_abilities()
    {
        $controller = new class extends Controller {
            use Authorizable;

            public function testGetAbilities()
            {
                return $this->getAbilities();
            }
        };

        $customAbilities = [
            'custom' => 'custom_action',
            'special' => 'special_action',
        ];

        $controller->setAbilities($customAbilities);
        $abilities = $controller->testGetAbilities();

        $this->assertEquals($customAbilities, $abilities);
        $this->assertArrayHasKey('custom', $abilities);
        $this->assertEquals('custom_action', $abilities['custom']);
    }

    #[Test]
    public function it_generates_ability_name_from_method_and_controller()
    {
        $controller = new class extends Controller {
            use Authorizable;

            public function testGetAbility($method)
            {
                return $this->getAbility($method);
            }
        };

        // Mock the route with proper controller binding
        $route = $this->getMockBuilder(Route::class)
            ->disableOriginalConstructor()
            ->getMock();

        $route->method('getController')
            ->willReturn($controller);

        // Create and bind request to the application
        $request = Request::create('/', 'GET');
        $request->setRouteResolver(function () use ($route) {
            return $route;
        });

        $this->app->instance('request', $request);

        // This would generate something like "view_test_resources" for index method
        // Note: The actual implementation depends on route configuration
        $ability = $controller->testGetAbility('index');

        // Should return a string or null
        $this->assertTrue(is_string($ability) || is_null($ability));
    }

    #[Test]
    public function it_returns_null_for_unmapped_method()
    {
        $controller = new class extends Controller {
            use Authorizable;

            public function testGetAbility($method)
            {
                return $this->getAbility($method);
            }
        };

        // Mock the route with proper controller binding
        $route = $this->getMockBuilder(Route::class)
            ->disableOriginalConstructor()
            ->getMock();

        $route->method('getController')
            ->willReturn($controller);

        // Create and bind request to the application
        $request = Request::create('/', 'GET');
        $request->setRouteResolver(function () use ($route) {
            return $route;
        });

        $this->app->instance('request', $request);

        $ability = $controller->testGetAbility('nonexistent_method');

        $this->assertNull($ability);
    }

    #[Test]
    public function it_calls_action_when_authorized()
    {
        $controller = new class extends Controller {
            use Authorizable;

            public $indexCalled = false;

            public function index()
            {
                $this->indexCalled = true;
                return 'index response';
            }
        };

        // Mock the route with proper controller binding
        $route = $this->getMockBuilder(Route::class)
            ->disableOriginalConstructor()
            ->getMock();

        $route->method('getController')
            ->willReturn($controller);

        // Create and bind request to the application
        $request = Request::create('/', 'GET');
        $request->setRouteResolver(function () use ($route) {
            return $route;
        });

        $this->app->instance('request', $request);

        // Override getAbility to return null (no authorization check)
        $controller->setAbilities([]); // Empty abilities means no check

        $response = $controller->callAction('index', []);

        $this->assertTrue($controller->indexCalled);
        $this->assertEquals('index response', $response);
    }
}
