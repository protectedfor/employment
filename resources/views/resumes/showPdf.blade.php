<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('css/resumePdfStyles.css') }}">

<div class="onerezume">
    <div class="container">
        <div class="row">
	        @include('resumes.partials._showUserInfo', ['pdf' => true, 'published' => 'Опубликовано', 'phone' => 'Телефон', 'age' => 'Возраст',
			'city' => 'Город', 'business' => 'Занятость', 'fieldOfActivity' => 'Сфера деятельности', 'salary' => 'Желаемая зарплата'])
	        <div class="clearfix"></div>

            @include('resumes.partials._showWorkExperience', ['pdf' => true, 'workExperience' => 'Опыт работы', 'tillNow' => 'по настоящее время',
			'fieldOfActivity' => 'Сфера деятельности', 'website' => 'Сайт', 'placeOfWork' => 'Место работы', 'responsibilities' => 'Обязанности'])

            @include('resumes.partials._showEducation', ['pdf' => true, 'education' => 'Образование', 'faculty' => 'Факультет', 'levelOfEducation' => 'Уровень образования'])

            @include('resumes.partials._showExtraEducation', ['pdf' => true, 'extraEducation' => 'Дополнительное образование'])

            @include('resumes.partials._showAdditionInformation', ['pdf' => true, 'additionalInfo' => 'Дополнительная информация', 'aboutMe' => 'Обо мне',
			'languages' => 'Знание языков', 'nativeLanguage' => 'Родной язык', 'files' => 'Файлы', 'kb' => 'кб'])
        </div> <!-- end row -->
    </div> <!-- end container -->
</div> <!-- end onerezume -->