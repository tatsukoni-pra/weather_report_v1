<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class RainControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 基本的なテスト例
     *
     * @return void
     */
    public function testIndev()
    {
        $this->assertTrue(true);
        $this->assertTrue(false);
    }
}
