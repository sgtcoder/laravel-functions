<?php

namespace SgtCoder\LaravelFunctions\Tests\Unit\Providers;

use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use SgtCoder\LaravelFunctions\Tests\TestCase;

/** Query builder macros registered by CustomServiceProvider. */
class QueryMacroTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return array_merge(parent::getPackageProviders($app), [
            \SgtCoder\LaravelFunctions\Providers\CustomServiceProvider::class,
        ]);
    }

    /**
     * Native whereLike binds verbatim. A macro re-adding '%' would silently flip every
     * caller from exact to contains matching.
     */
    #[Test]
    public function it_does_not_add_wildcards_to_native_where_like()
    {
        $query = DB::table('users')->whereLike('name', 'smith');

        $this->assertSame(
            ['smith'],
            $query->getBindings(),
            'If this binds %smith% the package macro is live and the wildcard semantics differ.'
        );
    }

    #[Test]
    public function it_does_not_add_wildcards_to_native_or_where_like()
    {
        $query = DB::table('users')->where('id', 1)->orWhereLike('name', 'smith');

        $this->assertSame(['smith'], array_slice($query->getBindings(), 1));
    }

    /** A hostile search term must not be able to terminate the LIKE literal. */
    #[Test]
    public function it_binds_the_search_term_in_raw_like_macros()
    {
        $hostile = 'x" OR 1=1 -- ';

        $query = DB::table('users')->whereLikeRaw('name', $hostile);

        $this->assertSame(['%' . $hostile . '%'], $query->getBindings());
        $this->assertStringNotContainsString('OR 1=1', $query->toSql());

        $or = DB::table('users')->where('id', 1)->orWhereLikeRaw('name', $hostile);
        $this->assertStringNotContainsString('OR 1=1', $or->toSql());
    }

    #[Test]
    public function it_excludes_null_and_empty_string_from_where_not_empty()
    {
        DB::statement('create table t (id integer primary key, v text null)');
        DB::table('t')->insert([
            ['id' => 1, 'v' => 'set'],
            ['id' => 2, 'v' => ''],
            ['id' => 3, 'v' => null],
        ]);

        $this->assertSame([1], DB::table('t')->whereNotEmpty('v')->pluck('id')->all());
    }

    #[Test]
    public function it_applies_where_if_only_when_the_condition_holds()
    {
        $applied = DB::table('users')->whereIf(true, fn($q) => $q->where('id', 1));
        $skipped = DB::table('users')->whereIf(false, fn($q) => $q->where('id', 1));

        $this->assertNotEmpty($applied->getBindings());
        $this->assertEmpty($skipped->getBindings());
    }

    #[Test]
    public function it_applies_the_if_macro_only_when_the_condition_holds()
    {
        $applied = DB::table('users')->if(true, 'id', '=', 5);
        $skipped = DB::table('users')->if(false, 'id', '=', 5);

        $this->assertSame([5], $applied->getBindings());
        $this->assertEmpty($skipped->getBindings());
    }

    #[Test]
    public function it_returns_null_from_to_object_when_empty()
    {
        $this->assertNull(collect([])->toObject());

        $object = collect([['a' => 1]])->toObject();
        $this->assertSame(1, $object[0]->a);
    }
}
