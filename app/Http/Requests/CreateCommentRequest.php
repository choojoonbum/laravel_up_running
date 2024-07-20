<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $blogPostId = $this->route('blogPost'); // blogPost 세그먼트를 변수에 할당

        return auth()->check() && BlogPost::where('id', $blogPostId)->where('user_id', auth()->id())->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => 'required|max:1000'
        ];
    }
}
