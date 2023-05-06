<?php
namespace App\Transformers;

use App\Transformers\Transformer;
use Carbon\Carbon;

class ServiceDetailTransformer extends Transformer
{
    public function transform($detail, $options = null)
    {
        return [
            'day' => Carbon::getDays()[$detail->day],
            'slots' => (new SlotTransformer())->transformCollection($detail->slots)
        ];
    }
}