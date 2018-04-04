<?php $slug = isset($lang) ? 'english_slug' : 'title' ?>

<div class="category3">
	<h2>{{ $additionalInfo }}</h2>
	@if($resume->about_me)
		<div class="subcategory">
			<div class="left">
				<div class="date">
					<h4>{{ $aboutMe }}</h4>
				</div>
			</div>
			<div class="right">
				<div class="about">
					<p>{!! $resume->about_me !!}</p>
				</div>
			</div>
		</div> <!-- end subcategory -->
	@endif
	<div class="clearfix"></div>
	<div class="subcategory">
		<div class="left">
			<div class="date">
				<h4>{{ $languages }}</h4>
			</div>
		</div>
		<div class="right">
			<div class="language">
				<strong>{{ $resume->nativeLanguage->{$slug} }} - {{ $nativeLanguage }}</strong>
			</div>
			@foreach($resume->resumeLanguages as $language)
				<div class="language">
					<span>{{ $language->language->{$slug} }} - </span>
					<span>{{ mb_strtolower($language->languageProficiency->{$slug}) }}</span>
				</div>
			@endforeach
		</div>
	</div> <!-- end subcategory -->
	<div class="clearfix"></div>
	@if($accessToContacts && ($resume->file1 || $resume->file2 || $resume->file3))
		<div class="subcategory">
			<div class="left">
				<div class="date">
					<h4>{{ $files }}</h4>
				</div>
			</div>
			<div class="right">
				<div class="file">
					@if($resume->file1)
						@if(!isset($pdf)) <img src="/img/button/docblue.png" alt=""> @endif
						<a class="doc" href="{{ url(config('admin.imagesUploadDirectory'). '/'. $resume->file1) }}" target="_blank">{{ $resume->filename1 }}<span> ({{ round(filesize(config('admin.imagesUploadDirectory'). '/'. $resume->file1)/1000) }}{{ $kb }})</span></a>
					@endif
					@if($resume->file2)
						@if(!isset($pdf)) <img src="/img/button/docblue.png" alt=""> @endif
						<a class="doc" href="{{ url(config('admin.imagesUploadDirectory'). '/'. $resume->file2) }}" target="_blank">{{ $resume->filename2 }}<span> ({{ round(filesize(config('admin.imagesUploadDirectory'). '/'. $resume->file2)/1000) }}{{ $kb }})</span></a>
					@endif
					@if($resume->file3)
						@if(!isset($pdf)) <img src="/img/button/docblue.png" alt=""> @endif
						<a class="doc" href="{{ url(config('admin.imagesUploadDirectory'). '/'. $resume->file3) }}" target="_blank">{{ $resume->filename3 }}<span> ({{ round(filesize(config('admin.imagesUploadDirectory'). '/'. $resume->file3)/1000) }}{{ $kb }})</span></a>
					@endif
				</div>
			</div>
		</div> <!-- end subcategory -->
	@endif
</div> <!-- end category -->