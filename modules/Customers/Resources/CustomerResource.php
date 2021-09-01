<?php

namespace Modules\Customers\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = $this->resource;
        return [
            'id' => $resource->id,
            'name' => $resource->name ==null ? " ":$resource->name,
            'email' => $resource->email == null ? " ":$resource->email
            ];
    }
}
