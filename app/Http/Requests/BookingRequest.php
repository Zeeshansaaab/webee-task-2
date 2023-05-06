<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Service;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class BookingRequest extends FormRequest
{
    private $service;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'slot_id' => 'required|exists:slots,id',
            'date' => 'required|date|after_or_equal:today',
            'personal_details' => 'required|array',
            'personal_details.*.first_name' => 'required|string|max:50',
            'personal_details.*.last_name' => 'required|string|max:50',
            'personal_details.*.email' => 'required|string|max:50',
        ];
    }

    public function messages()
    {
        return [
            'slot_id.required' => 'Please select a slot',
            'slot_id.slot_id' => 'Slot is not available',
            'date.required' => 'Please select a slot',
            'personal_details.*.first_name.required' =>  "Please enter firstname",
            'personal_details.*.last_name.required' =>  "Please enter firstname",
            'personal_details.*.email.required' =>  "Please enter firstname",
        ];
    }

    public function valid()
    {
        $this->service = Service::whereHas('details.slots', function($query){
            $query->where('id', request()->slot_id);
        })->first();
        
        $this->isSlotBooked();
        $this->isUserLimitExceed();
    }

    public function isSlotBooked()
    {
        $totalClinetBookedTheSlots = BookingDetail::whereHas('booking', function($query){
            $query->whereDate('date', request()->date)->where('slot_id', request()->slot_id);
        })->count();
        if($totalClinetBookedTheSlots >= $this->service->max_clients)
            throw new \Exception('Slot is already booked', JsonResponse::HTTP_UNPROCESSABLE_ENTITY);

        $remainingSlots = ($this->service->max_clients - $totalClinetBookedTheSlots);
        
        if($remainingSlots < count(request()->personal_details)){
            throw new \Exception('Only ' . $remainingSlots . ' client space available in this slot', JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function isUserLimitExceed()
    {
        $currentDate = Carbon::now(); 
        $requestDate = Carbon::parse(request()->date);
        $diffInDays = $currentDate->diffInDays($requestDate);
        if ($diffInDays >= $this->service->max_booking_days) {
            throw new \Exception('You can only book a service in span of ' . $this->service->max_booking_days . ' days', JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
