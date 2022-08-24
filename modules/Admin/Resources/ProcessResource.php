<?php

namespace Modules\Admin\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProcessResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param mixed $request Request.
     *
     * @return mixed
     */
    public function toArray($request)
    {
        return [
            'title_vi' => ['required', 'min:4', 'unique:processes'],
            'title_en' => ['required', 'min:4', 'unique:processes'],
            'title_ja' => ['required', 'min:4', 'unique:processes'],
            'status' => ['required', 'integer', 'unique:processes'],
            'image' => [
                'required',
                'image',
                'mimes:jpeg,png',
                'mimetypes:image/jpeg,image/png',
                'max:2048',
                'unique:processes'
            ],
        ];
    }
}
