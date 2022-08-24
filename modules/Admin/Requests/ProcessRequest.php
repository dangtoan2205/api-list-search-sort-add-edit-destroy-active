<?php

namespace Modules\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessRequest extends FormRequest
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
        return [
            'title_vi' => ['required', 'min:4', 'unique:processes'],
            'title_en' => ['required', 'min:4', 'unique:processes'],
            'title_ja' => ['required', 'min:4', 'unique:processes'],
            'status' => ['required', 'integer'],
            'image' => [
                'required',
                'image',
                'mimes:jpeg,png',
                'mimetypes:image/jpeg,image/png',
                'max:2048',
            ]
        ];
    }

    public function messages()
    {
        return [
            'title_vi.required'=>'Vui lòng nhập tiêu đề cho ngôn ngữ',
            'title_en.required'=>'Vui lòng nhập tiêu đề cho ngôn ngữ',
            'title_ja.required'=>'Vui lòng nhập tiêu đề cho ngôn ngữ',
            'title_status.required'=>'Vui lòng nhập trạng thái',
            'image.required'=>'Vui lòng chọn ảnh',

            'title_vi.unique'=>'Tiêu đề cho ngôn ngữ đã tồn tại',
            'title_en.unique'=>'Tiêu đề cho ngôn ngữ đã tồn tại',
            'title_ja.unique'=>'Tiêu đề cho ngôn ngữ đã tồn tại',
            'image.unique.mimes'=>'Ảnh phải thuộc dạng .jpeg, .png',
            'image.unique.mimetypes'=>'Ảnh phải thuộc dạng .jpeg, .png',
        ];
    }
}
