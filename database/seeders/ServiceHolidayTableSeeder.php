<?php

namespace Database\Seeders;

use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceHolidayTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = Service::where(['name' => 'Men Haircut'])->get();
        foreach($services as $service){
            $service->holidays()->create([
                'name' => 'Public Holiday',
                'date' => Carbon::now()->addDay(3),
                'start_time' => Carbon::today()->startOfDay(),
                'end_time' => Carbon::today()->endOfDay(),
            ]);
        }
        
    }
}
