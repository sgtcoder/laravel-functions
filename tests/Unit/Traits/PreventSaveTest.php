<?php

namespace SgtCoder\LaravelFunctions\Tests\Unit\Traits;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\Test;
use SgtCoder\LaravelFunctions\Tests\TestCase;
use SgtCoder\LaravelFunctions\Traits\PreventSave;

class PreventSaveTest extends TestCase
{
    #[Test]
    public function it_allows_save_by_default()
    {
        $model = new class extends Model {
            use PreventSave;

            protected $table = 'test_models';
            public $timestamps = false;

            public function save(array $options = [])
            {
                // Mock parent save to avoid database operations
                if ($this->preventSave) {
                    return false;
                }
                return true; // Simulate successful save
            }
        };

        $result = $model->save();

        $this->assertTrue($result);
    }

    #[Test]
    public function it_prevents_save_when_flag_is_set()
    {
        $model = new class extends Model {
            use PreventSave;

            protected $table = 'test_models';
            public $timestamps = false;

            public function save(array $options = [])
            {
                if ($this->preventSave) {
                    return false;
                }
                return true;
            }
        };

        $model->setPreventSave(true);
        $result = $model->save();

        $this->assertFalse($result);
    }

    #[Test]
    public function it_allows_save_when_flag_is_disabled()
    {
        $model = new class extends Model {
            use PreventSave;

            protected $table = 'test_models';
            public $timestamps = false;

            public function save(array $options = [])
            {
                if ($this->preventSave) {
                    return false;
                }
                return true;
            }
        };

        $model->setPreventSave(true);
        $model->setPreventSave(false);
        $result = $model->save();

        $this->assertTrue($result);
    }

    #[Test]
    public function it_returns_model_instance_for_chaining()
    {
        $model = new class extends Model {
            use PreventSave;

            protected $table = 'test_models';
            public $timestamps = false;
        };

        $result = $model->setPreventSave(true);

        $this->assertSame($model, $result);
    }

    #[Test]
    public function it_sets_prevent_save_to_true_by_default()
    {
        $model = new class extends Model {
            use PreventSave;

            protected $table = 'test_models';
            public $timestamps = false;

            public function save(array $options = [])
            {
                if ($this->preventSave) {
                    return false;
                }
                return true;
            }
        };

        $model->setPreventSave(); // No argument, should default to true
        $result = $model->save();

        $this->assertFalse($result);
    }
}
