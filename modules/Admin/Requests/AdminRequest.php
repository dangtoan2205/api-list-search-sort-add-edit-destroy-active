<?php

namespace Modules\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
        $required = !$this->user ? 'required|' : '';

        return [
            'username' => 'required|max:50',
            'email' => 'required|max:50',
            'password' => 'required|max:60',
        ];
    }
}
