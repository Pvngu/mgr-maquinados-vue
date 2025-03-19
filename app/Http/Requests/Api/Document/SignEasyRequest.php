<?php

namespace App\Http\Requests\Api\Document;

use App\Http\Requests\Api\BaseRequest;

class SignEasyRequest extends BaseRequest
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
        $rules = [
			'subject' => 'required',
			'message' => 'required',
			'template_id' => 'required',
        ];
		return $rules;
	}
}
