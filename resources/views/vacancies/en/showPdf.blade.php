<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('css/vacancyPdfStyles.css') }}">

<div class="smallvakansia">
	<div class="container">
		<div class="row">
			<?php $lang = 2 ?>
			<div class="col-xs-6">
				<div class="manager">
					@include('vacancies.partials._showVacancyInfo', ['pdf' => true, 'published' => 'Published', 'deadline' => 'Deadline', 'expired' => 'The deadline for applications has expired ',
					'left' => 'remained', 'days' => 'days', 'salary' => 'Salary', 'perInterview' => 'According to the interview results ', 'workExperience' => 'Work experience',
					'city' => 'Location', 'business' => 'Type of involvement', 'schedule' => 'Schedule'])
				</div> <!-- end manager -->
			</div>
			<div class="col-xs-5 b-show-company-info">
				@include('vacancies.partials._showCompanyInfo', ['pdf' => true, 'address' => 'Address', 'contactPerson' => 'Contact person', 'phone' => 'Phone'])
			</div>
		</div>
		<div class="row">
			@include('vacancies.partials._showInformation', ['pdf' => true, 'info' => 'Information', 'commonInfo' => 'Common information',
			'requirements' => 'Qualification requirements ', 'duties' => 'Duties', 'conditions' => 'Conditions', 'aboutCompany' => 'About company'])
		</div>
	</div>
</div>