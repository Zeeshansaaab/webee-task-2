<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TimeSchedulingTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_available_slots(): void
    {
        $response = $this->get('api/services?date=2023-05-06');
        
        $response->assertStatus(200);
    }


    public function test_book_slot(): void
    {
        $response = $this->post('api/services', [
            "slot_id" => 1,
            "date" => Carbon::now(),
            "personal_details" => [
                [
                    "first_name" => "Zeeshan",
                    "last_name" => "Mughal",
                    "email" => "Email@gmail.com"
                ],
            ]
        ]);
        
        $response->assertStatus(200);
    }
}
