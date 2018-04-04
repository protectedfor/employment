<?php

namespace App\Http\Requests;

use Auth;
use Carbon\Carbon;

class StoreTrainingRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && Auth::user()->hasRoleFix('employers');
    }

    /**
     * Set custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'position' => 'должность'
        ];
    }

    /**
     * Set custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
	        "email.required_if" => 'Поле обязательно для заполнения, когда когда курсы проводятся в Кыргызстане',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
	        'photo' => 'max:255',
	        'location' => 'required',
            'title' => 'required|max:255',
            'coordinator' => 'required|max:255',
            'category_id' => 'required|exists:training_categories,id',
	        'price' => 'required|max:255',
            'start_date' => 'required|max:255',
	        'duration' => 'max:255',
	        'schedule' => 'max:255',
	        'place' => 'max:255',
	        'description' => 'required',
	        'expires_at' => 'max:255|date_format:d.m.Y|after:' . Carbon::now() . '|before:' . Carbon::now()->addYear(),
	        'contacter' => 'max:255',
	        'coach' => 'max:255',
	        'phone' => 'max:255',
	        'email' => 'required_if:location,0|max:255|email',
	        'site' => 'max:255',
	        'address' => 'max:255',
        ];
    }
}
