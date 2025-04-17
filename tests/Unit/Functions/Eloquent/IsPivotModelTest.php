<?php

namespace Orchestra\Sidekick\Tests\Functions\Eloquent;

use PHPUnit\Framework\TestCase;

use function Orchestra\Sidekick\Eloquent\is_pivot_model;

class IsPivotModelTest extends TestCase
{
    public function test_it_can_determine_if_model_is_a_pivot_model()
    {
        $this->assertTrue(is_pivot_model(new class extends \Illuminate\Database\Eloquent\Relations\Pivot
        {
            // ...
        }));
    }

    public function test_it_can_determine_if_model_is_a_pivot_model_when_using_as_pivot_trait()
    {
        $this->assertTrue(is_pivot_model(new class extends \Illuminate\Database\Eloquent\Model
        {
            use \Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;

            // ...
        }));
    }

    public function test_it_cant_determine_if_model_is_a_pivot_model()
    {
        $this->assertFalse(is_pivot_model(new class extends \Illuminate\Database\Eloquent\Model
        {
            // ...
        }));
    }
}
