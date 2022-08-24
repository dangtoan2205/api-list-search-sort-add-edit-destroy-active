<?php

namespace Modules\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    /**
     * Determine if the admin is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'order_by' => 'string',
            'order_type' => 'integer|in:0,1',
            'limit' => 'integer|min:1',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (!$this->has('limit')) {
            $this->merge([
                'limit' => 20,
            ]);
        }

        if (!$this->has('order_by')) {
            $this->merge([
                'order_by' => 'id',
            ]);
        }

        if (!$this->has('order_type')) {
            $this->merge([
                'order_type' => 0,
            ]);
        }
    }
}
