<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('css/resumePdfStyles.css') }}">

<div class="onerezume">
	<div class="container">
		<div class="row">
			<?php $lang = 2 ?>
			@include('resumes.partials._showUserInfo', ['pdf' => true, 'published' => 'Published', 'phone' => 'Phone', 'age' => 'Age',
			'city' => 'Location', 'business' => 'Type of involvement', 'fieldOfActivity' => 'Type of work', 'salary' => 'Desired Salary'])
			<div class="clearfix"></div>

			@include('resumes.partials._showWorkExperience', ['pdf' => true, 'workExperience' => 'Work experience', 'tillNow' => 'till now',
			'fieldOfActivity' => 'Type of work', 'website' => 'Website', 'placeOfWork' => 'Location', 'responsibilities' => 'Responsibilities'])

			@include('resumes.partials._showEducation', ['pdf' => true, 'education' => 'Education', 'faculty' => 'Faculty', 'levelOfEducation' => 'Education level'])

			@include('resumes.partials._showExtraEducation', ['pdf' => true, 'extraEducation' => 'Additional education'])

			@include('resumes.partials._showAdditionInformation', ['pdf' => true, 'additionalInfo' => 'Additional Information', 'aboutMe' => 'About me',
			'languages' => 'Languages', 'nativeLanguage' => 'native language', 'files' => 'Files', 'kb' => 'kb'])
		</div> <!-- end row -->
	</div> <!-- end container -->
</div> <!-- end onerezume -->