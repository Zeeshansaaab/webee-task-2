<?php
namespace App\Transformers;

use App\Models\ServiceDetail;
use App\Transformers\Transformer;

class ServiceTransformer extends Transformer
{
    public function transform($service, $options = null)
    {
        return [
            'name' => $service->name,
            'slot_duration' => $service->slot_duration,
            'days' => (new ServiceDetailTransformer())->transformCollection($service->details)
        ];
    }
}