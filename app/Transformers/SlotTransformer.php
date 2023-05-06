<?php
namespace App\Transformers;

use App\Transformers\Transformer;

class SlotTransformer extends Transformer
{
    public function transform($slot, $options = null)
    {
        $data = [
            'id' => $slot->id, 
            'start_time' => $slot->start_time,
            'end_time' => $slot->end_time,
            'max_client' => $slot->max_clients,
        ];

        if(isset($options['withService']) && $options['withService']){
            $data['service'] = $slot->detail->service->name;
        }
        return $data;
    }
}