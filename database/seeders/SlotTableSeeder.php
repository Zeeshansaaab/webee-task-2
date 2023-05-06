<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Slot;
use App\Models\Event;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use PhpParser\Node\Expr\Cast\Object_;

class SlotTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = Service::with(['breaks', 'details', 'holidays'])->get();
        foreach($services as $service){
            $date = Carbon::today();
            $end_date = Carbon::today()->addDays(7);
            while ($date->lt($end_date)) {
                $serviceDetail = $service->details->where('day', $date->dayOfWeek)->first();
                $isHoliday = $service->holidays->where('date', Carbon::today())->first();
                if ($serviceDetail && is_null($isHoliday)) {
                    // $serviceDetail
                    $start_time = $serviceDetail->start_time;
                    $end_time = $serviceDetail->end_time;
                    
                    while ($start_time->lt($end_time)) {
                        $break = $service->breaks->where('start_time', '<', $start_time->copy()->addMinute($service->slot_duration))
                        ->where('end_time', '>', $start_time)->first();
                        // Make sure the booking wont come in between start and end time
                        // High chances that booking will start before break but ends in break
                        // Need to add Duration in start and end time
                        if (is_null($break)) {
                            // Create the slot
                            $slot = $serviceDetail->slots()->create([
                                'start_time' => $date->copy()->setTimeFrom($start_time),
                                'end_time' => $date->copy()->setTimeFrom($start_time)->addMinutes($service->slot_duration),
                            ]);
                            // Increment start time by 10 minutes
                            $start_time->addMinutes($service->slot_duration + $service->break_between_appointment);
                        }else {
                            $start_time = $break->end_time;
                        }
                        
                    }
                }
                $date->addDay();
            }
        }
        // $lunch_break_start = 12; // 12:00 pm
        // $cleaning_break_start = 15;

        // Define number of clients per slot and slot duration
        // $max_clients_per_slot = 3;
        // $slot_duration = 10; // in minutes
        // $cleaning_break_between_slot = 5; // in minutes
        
        // Get the public holidays for the first week only
        // $public_holidays = [
        //     Carbon::today()->addDays(2)->format('Y-m-d')
        // ];

        // Get the service
        // $service = Service::where('name', 'Men Haircut')->first();

        // // Loop through the next 7 days
        // $date = Carbon::today();
        // $end_date = Carbon::today()->addDays(7);
        // while ($date->lt($end_date)) {
            // Check if day is Sunday
            // if ($date->dayOfWeek === Carbon::SUNDAY) {
            //     $date->addDay();
            //     continue;
            // }
            
            // // Check if day is a public holiday
            // if (in_array($date->format('Y-m-d'), $public_holidays)) {
            //     $date->addDay();
            //     continue;
            // }
            
            
            // // Loop through the time slots
            // $start_time = Carbon::createFromTime(8, 0, 0);
            // $end_time = Carbon::createFromTime(20, 0, 0);
            // if ($date->dayOfWeek === Carbon::SATURDAY) {
            //     $start_time->addHour(2);
            //     $end_time->addHour(2);
            // }
            // while ($start_time->lt($end_time)) {
            //     // Check if slot is during lunch break
            //     // if ($start_time->hour === $lunch_break_start) {
            //     //     $start_time->addMinutes(60);
            //     //     continue;
            //     // }

            //     // // Check if slot is during cleaning break
            //     // if ($start_time->hour === $cleaning_break_start) {
            //     //     $start_time->addMinutes(60);
            //     //     continue;
            //     // }
            //     // // Check if slot is during cleaning break
            //     // if ($start_time->hour === 15) {
            //     //     $start_time->addMinutes(60);
            //     //     continue;
            //     // }

                // Create the slot
            //     $slot = new Slot();
            //     $slot->service_id = $service->id;
            //     $slot->day = $date->dayOfWeek;
            //     $slot->start_time = $date->copy()->setTimeFrom($start_time);
            //     $slot->end_time = $date->copy()->setTimeFrom($start_time)->addMinutes(10);
            //     $slot->max_clients = $max_clients_per_slot;
            //     $slot->save();

            //     // Increment start time by 10 minutes
            //     $start_time->addMinutes($slot_duration + $cleaning_break_between_slot);
            // }

            // // Move to next day
            // $date->addDay();
        // }
    }
}
