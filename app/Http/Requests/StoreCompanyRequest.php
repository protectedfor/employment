<?php

namespace App\Http\Requests;

use Auth;
use Carbon\Carbon;

class StoreCompanyRequest extends Request
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
	        "fio.required_if" => 'Поле обязательно для заполнения, когда когда выбрано "Показать на сайте"',
	        "phone.required_if" => 'Поле обязательно для заполнения, когда когда выбрано "Показать на сайте"',
	        "site.required_if" => 'Поле обязательно для заполнения, когда когда выбрано "Показать на сайте"',
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
	        'title' => 'required|max:100',
	        'scope_id' => 'required|exists:scopes,id',
	        'city_id' => 'required|exists:cities,id',
	        'address' => 'max:50',
	        'about_company' => 'required|max:2000',
	        'fio' => 'required_if:show_fio,1|max:50',
	        'phone' => 'required|max:50',
	        'site' => 'max:100',
        ];
    }
}
