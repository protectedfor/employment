<?php

namespace App\Http\Requests;

use Auth;
use Carbon\Carbon;

class StoreVacancyRequest extends Request
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
//            'position' => 'должность'
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
	        "form_from_file.required_if" => 'Поле обязательно для заполнения, когда выбрана опция подачи через заполенение прикреплённой формы',
	        "link_online_form.required_if" => 'Поле обязательно для заполнения, когда выбрана опция подачи через онлайн-форму',
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
            'position' => 'required|max:255',
            'scope_id' => 'required|exists:scopes,id',
//            'place_of_work' => 'required|max:255',
            'city_id' => 'required|exists:cities,id',
            'education_id' => 'required|exists:education,id',
            'busyness_id' => 'required|exists:busynesses,id',
            'work_graphite' => 'max:255',
            'experience' => 'max:255',
            'wages_from' => 'integer',
            'wages_to' => 'integer|greater_than:wages_from',
            'currency_id' => 'exists:currencies,id',
            'expires_at' => 'max:255|date_format:d.m.Y|after:' . Carbon::now()->subDay() . '|before:' . Carbon::now()->addYear(),
            'overview' => '',
            'qualification_requirements' => 'required',
            'duties' => 'required',
            'conditions' => '',
            'response_email_notifications' => 'required|in:0,1',
            'only_in_english' => 'required|in:0,1',
            'request_type' => 'required|in:resume,form_from_file,online_form',
            'form_from_file' => 'required_if:request_type,form_from_file',
            'link_online_form' => 'required_if:request_type,online_form|url'

        ];
    }
}
