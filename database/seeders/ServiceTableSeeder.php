<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $services = [
            [
                'name' => 'Men Haircut',
                'slot_duration' => 10,
                'break_between_appointment' => 5,
                'max_clients' => 3
            ],[
                'name' => 'Women Haircut',
                'slot_duration' => 60,
                'break_between_appointment' => 10,
                'max_clients' => 3
            ]
        ];
        foreach($services as $service){
            $service = Service::create($service);
            $date = Carbon::today();
            $end_date = Carbon::today()->addDays(7);
            while ($date->lt($end_date)) {
                // Check if day is Sunday
                if ($date->dayOfWeek === Carbon::SUNDAY) {
                    $date->addDay();
                    continue;
                }
                // Loop through the time slots
                $start_time = Carbon::createFromTime(8, 0, 0);
                $end_time = Carbon::createFromTime(20, 0, 0);
                if ($date->dayOfWeek === Carbon::SATURDAY) {
                    $start_time->addHour(2);
                    $end_time->addHour(2);
                }
                $service->details()->create([
                    'day' => $date->dayOfWeek,
                    'start_time' => $start_time,
                    'end_time' => $end_time
                ]);

                $date->addDay();
            }
        }
        
    }
}
