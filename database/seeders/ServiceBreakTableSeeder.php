<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceBreakTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = Service::all();
        $breaks = [
            [
                'name' => "lunch break",
                'start_time' => Carbon::createFromTime(12, 0, 0),
                'end_time' => Carbon::createFromTime(13, 0, 0)
            ],[
                'name' => "cleaning break",
                'start_time' => Carbon::createFromTime(15, 0, 0),
                'end_time' => Carbon::createFromTime(16, 0, 0)
            ]
        ];
        foreach($services as $service){
            foreach($breaks as $break){
                $service->breaks()->create($break);
            }
        }
    }
}
