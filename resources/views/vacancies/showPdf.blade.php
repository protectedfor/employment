<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('css/vacancyPdfStyles.css') }}">

<div class="smallvakansia">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
	            <div class="manager">
	                @include('vacancies.partials._showVacancyInfo', ['pdf' => true, 'published' => 'Опубликована', 'deadline' => 'Крайний срок', 'expired' => 'Истёк срок подачи заявок ',
					'left' => 'осталось', 'days' => 'дней', 'salary' => 'Зарплата', 'perInterview' => 'По результатам собеседования ', 'workExperience' => 'Опыт работы',
					'city' => 'Город', 'business' => 'Занятость', 'schedule' => 'График работы'])
	            </div> <!-- end manager -->
            </div>
	        <div class="col-xs-5 b-show-company-info">
		        @include('vacancies.partials._showCompanyInfo', ['pdf' => true, 'address' => 'Адрес', 'contactPerson' => 'Контактное лицо', 'phone' => 'Телефон'])
	        </div>
        </div>
	    <div class="row">
		    @include('vacancies.partials._showInformation', ['pdf' => true, 'info' => 'Информация', 'commonInfo' => 'Общие сведения', 'requirements' => 'Требования ',
			'duties' => 'Обязанности', 'conditions' => 'Условия', 'aboutCompany' => 'О компании'])
        </div>
    </div>
</div>

