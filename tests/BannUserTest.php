<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Orchestra\Testbench\TestCase;

class BanFunctionalityTest extends TestCase
{
    /** @test */
    public function it_bans_user_by_user_id()
    {
      
        app('superban')->trackRequest(1, '127.0.0.1', 'test@example.com');

        // Apply ban
        app('superban')->applyBan(1, '127.0.0.1', 'test@example.com', 2, 1, 24);

   
        $this->assertTrue(app('superban')->isUserBanned(1));
    }
}
