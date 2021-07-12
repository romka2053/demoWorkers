<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'post'=>$this->post,
            'device_date'=>$this->device_date,
            'salary'=>$this->salary,
            'parent_id'=>$this->parent_id,
            'urlImage'=>$this->urlImage,


        ];
    }
}
