<?php

namespace App\Http\Requests;

use Auth;
use Carbon\Carbon;

class StoreResumeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && Auth::user()->hasRoleFix('workers');
    }

    /**
     * Set custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        $messages = [];

        foreach ($this->input('institutions') as $key => $value) {
            $messages["education_id.{$key}.required"] = 'Выберите образование';
            $messages["education_id.{$key}.exists"] = 'Неверное значение';
            $messages["institution.{$key}.required"] = 'Введите учебное заведение';
            $messages["department.{$key}.required"] = 'Введите факультет';
            $messages["specialty.{$key}.required"] = 'Введите специальность';
            $messages["year_of_ending.{$key}.required"] = 'Выберите год';
        }

	    foreach ($this->input('extraInstitutions') as $key => $value) {
		    $messages["extra_inst_title.{$key}.required"] = 'Введите название курса/тренинга';
		    $messages["extra_inst_organizer.{$key}.required"] = 'Укажите организатора курса/тренинга';
		    $messages["extra_inst_date.{$key}.required"] = 'Введите дату';
		    $messages["extra_inst_location.{$key}.required"] = 'Введите город/страну';
	    }

        foreach ($this->input('languages') as $key => $value) {
            $messages["language_id.{$key}.required"] = 'Выберите язык';
            $messages["language_proficiency_id.{$key}.required"] = 'Выберите значение';
        }

        foreach ($this->input('work_experiences') as $key => $value) {
            $messages["position.{$key}.required"] = 'Введите должность';
            $messages["scope.{$key}.required_without"] = 'Поле обязательно для заполнения';
            $messages["exp_end_work.{$key}.after"] = 'В поле должна быть дата после даты "Начала работы"';
        }
        return $messages;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'photo' => 'max:255',
            'name' => 'required|max:255',
            'sname' => 'required|max:255',
            'mname' => 'max:255',
            'date_of_birth' => 'required|max:255|date_format:d.m.Y|after:' . Carbon::now()->subYears(100) . '|before:' . Carbon::now(),
            'phone' => 'required|max:255',
            'citizenship_id' => 'exists:citizenships,id',
            'native_language_id' => 'required|exists:languages,id',
            'salary' => 'integer',
            'currency_id' => 'exists:currencies,id|required_with:salary',
            'career_objective' => 'required|max:255',
            'city_id' => 'required|exists:cities,id',
            'scope_id' => 'required|exists:scopes,id',
            'busyness_id' => 'required|exists:busynesses,id',
            'about_me' => '',
            'filename1' => 'required_with:file1|max:255',
	        'file1' => 'max:255',
	        'filename2' => 'required_with:file2|max:255',
	        'file2' => 'max:255',
	        'filename3' => 'required_with:file3|max:255',
	        'file3' => 'max:255',
        ];

        foreach ($this->input('institutions') as $key => $value) {
            $rules["education_id.{$key}"] = 'required|exists:education,id';
            $rules["institution.{$key}"] = 'required|max:255';
            $rules["department.{$key}"] = 'max:255';
            $rules["specialty.{$key}"] = 'required|max:255';
            $rules["year_of_ending.{$key}"] = 'required|integer';
        }

//	    foreach ($this->input('extraInstitutions') as $key => $value) {
//		    $rules["extra_inst_title.{$key}"] = 'required|max:255';
//		    $rules["extra_inst_organizer.{$key}"] = 'required|max:255';
//		    $rules["extra_inst_date.{$key}"] = 'required|max:255';
//		    $rules["extra_inst_location.{$key}"] = 'required|max:255';
//	    }

        foreach ($this->input('languages') as $key => $value) {
            $rules["language_id.{$key}"] = 'required|exists:languages,id';
            $rules["language_proficiency_id.{$key}"] = 'required|exists:language_proficiencies,id';
        }

        foreach ($this->input('work_experiences') as $key => $value) {
            $rules["position.{$key}"] = 'required|max:255';
            $rules["organization.{$key}"] = 'required|max:255';
            $rules["scope.{$key}"] = 'max:255|required_without:exp_scope_id.' . $key;
            $rules["exp_scope_id.{$key}"] = 'required_without:scope.' . $key . '|exists:scopes,id';
            $rules["exp_city_id.{$key}"] = 'required|exists:cities,id';
            $rules["exp_org_site.{$key}"] = 'max:255';
            $rules["exp_start_work.{$key}"] = 'required|max:255|date_format:d.m.Y|after:' . Carbon::now()->subYears(100) . '|before:' . Carbon::now();
//            $rules["exp_is_working.{$key}"] = 'in:0,1';
//            $rules["exp_end_work.{$key}"] = 'max:255|date_format:Y-m-d|required_without:exp_is_working.' . $key;
            $rules["exp_end_work.{$key}"] = 'max:255|date_format:d.m.Y|after:exp_start_work.' . $key . '|before:' . Carbon::now();
        }

        return $rules;
    }
}
