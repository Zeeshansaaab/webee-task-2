<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Slot;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Transformers\SlotTransformer;
use App\Transformers\ServiceTransformer;

class TimeSchedulingController extends Controller
{
    public function index()
    {
        request()->validate([
            'date' => 'date|after_or_equal:today'
        ]);
        $services = Service::with(['details' => function($query){
            $query->where('day', date('w', strtotime(request()->date)))->with('slots');
        }])->get();
        return response()->json([
            'data' => (new ServiceTransformer())->transformCollection($services)
        ], JsonResponse::HTTP_OK);
    }

    public function bookSlot(BookingRequest $request)
    {
        try{
            $request->valid();
            DB::beginTransaction();
            $booking = Booking::updateOrCreate(['slot_id' => $request->slot_id, 'date' => $request->date], []);
            $booking->details()->createMany($request->personal_details);
            DB::commit();
            return response()->json([
                'message' => "You have booked the slot"
            ], JsonResponse::HTTP_OK);
        } catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
