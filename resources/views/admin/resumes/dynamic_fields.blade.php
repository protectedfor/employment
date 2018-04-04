<?php

$citizenships = \App\Models\Resumes\Citizenship::activeOrder()->get();
$cities = \App\Models\Resumes\City::activeOrder()->get();
$currencies = \App\Models\Vacancies\Currency::activeOrder()->get();
$scopes = \App\Models\Vacancies\Scope::activeOrder()->get();
$busynesses = \App\Models\Vacancies\Busyness::activeOrder()->get();
$educations = \App\Models\Vacancies\Education::activeOrder()->get();
$languages = \App\Models\Resumes\Language::activeOrder()->get();
$language_proficiencies = \App\Models\Resumes\LanguageProficiency::activeOrder()->get();

$resume = $instance;
$b_counter = 0;
?>

<div class="about">
	<h2>Образование</h2>
</div> <!-- end about -->
<div class="big">
	<div class="dynamic_container">
		@foreach(isset($resume) && $resume->institutions->count() > 0 ? $resume->institutions : old('institutions', [1]) as $key => $val)
			<div class="dynamic_block">
				<input type="hidden" name="institutions[]">
				<div class="clearfix"></div>
				<div class="form-group {{ $errors->has("education_id.{$key}") ? 'has-error' : '' }}" id="bottomline">
					<label for="">Уровень:</label>
					@if(isset($resume))
						{!! Form::select('education_id[]', $educations->lists('title', 'id'), $val->education_id, ['class' => 'form-control']) !!}
					@else
						<select name="education_id[]" id="" class="form-control">
							<option value="">Выберите образование</option>
							@foreach($educations as $entry)
								<option @if($entry->id == old("education_id.{$key}")) selected @endif value="{{ $entry->id }}">{{ $entry->title }}</option>
							@endforeach
						</select>
					@endif
					@if($errors->has("education_id.{$key}"))
						<span class="help-block">{{ $errors->first("education_id.{$key}") }}</span>
					@endif
				</div>

				<div class="form-group {{ $errors->has("institution.{$key}") ? 'has-error' : '' }}">
					<label for="">Учебное заведение:</label>
					@if(isset($resume))
						<input name="institution[]" value="{{ $val->institution }}" class="form-control" type="text" placeholder="Введите учебное заведение">
					@else
						<input name="institution[]" value="{{ old("institution.{$key}") }}" class="form-control" type="text" placeholder="Введите учебное заведение">
					@endif
					@if($errors->has("institution.{$key}"))
						<span class="help-block">{{ $errors->first("institution.{$key}") }}</span>
					@endif
				</div>

				<div class="form-group {{ $errors->has("department.{$key}") ? 'has-error' : '' }}">
					<label for="">Факультет:</label>
					@if(isset($resume))
						<input name="department[]" value="{{ $val->department }}" class="form-control" type="text" placeholder="Введите факультет">
					@else
						<input name="department[]" value="{{ old("department.{$key}") }}" class="form-control" type="text" placeholder="Введите факультет">
					@endif
					@if($errors->has("department.{$key}"))
						<span class="help-block">{{ $errors->first("department.{$key}") }}</span>
					@endif
				</div>

				<div class="form-group {{ $errors->has("specialty.{$key}") ? 'has-error' : '' }}">
					<label for="">Специальность:</label>
					@if(isset($resume))
						<input name="specialty[]" value="{{ $val->specialty }}" class="form-control" type="text" placeholder="Например: “Бухгалтерский учет” или “Управление персоналом”">
					@else
						<input name="specialty[]" value="{{ old("specialty.{$key}") }}" class="form-control" type="text" placeholder="Например: “Бухгалтерский учет” или “Управление персоналом”">
					@endif
					@if($errors->has("specialty.{$key}"))
						<span class="help-block">{{ $errors->first("specialty.{$key}") }}</span>
					@endif
				</div>

				<div class="form-group {{ $errors->has("year_of_ending.{$key}") ? 'has-error' : '' }}">
					<label for="">Год окончания</label>
					{!! Form::select('year_of_ending[]', array_combine(range(date("Y") + 7, 1910), range(date("Y") + 7, 1910)), isset($resume) ? $val->year_of_ending : '', ['class' => 'form-control', 'placeholder' => 'Выберите']) !!}
					@if($errors->has("year_of_ending.{$key}"))
						<span class="help-block">{{ $errors->first("year_of_ending.{$key}") }}</span>
					@endif
				</div>
			</div>
		@endforeach
	</div>

	@if($resume->extraInstitutions->count())
		<div class="about">
			<h2>Дополнительное образование</h2>
		</div> <!-- end about -->
		<div class="big">
			<div class="dynamic_container">
				@foreach(isset($resume) && $resume->extraInstitutions->count() > 0 ? $resume->extraInstitutions : old('extraInstitutions', [1]) as $key => $val)
					<div class="dynamic_block">
						<input type="hidden" name="extraInstitutions[]">
						<div class="clearfix"></div>
						@InputBlock([$type = "input", $item = 'extra_inst_title[]', $label = "Название курса/тренинга", $var = @$val, $errorKey = $key, $p = "placeholder=\"Введите название курса/тренинга\""])
						@InputBlock([$type = "input", $item = 'extra_inst_organizer[]', $label = "Организатор курса/тренинга", $var = @$val, $errorKey = $key, $p = "placeholder=\"Укажите организатора курса/тренинга\""])
						@InputBlock([$type = "input", $item = 'extra_inst_date[]', $label = "Дата", $var = @$val, $errorKey = $key, $p = "placeholder=\"Введите дату\""])
						@InputBlock([$type = "input", $item = 'extra_inst_location[]', $label = "Город/Страна", $var = @$val, $errorKey = $key, $p = "placeholder=\"Введите город/страну\""])
					</div>
				@endforeach
			</div>
		</div> <!-- end big -->
	@endif
</div> <!-- end contentform -->

<div class="about">
	<h2>Знание языков</h2>
</div> <!-- end about -->
<div class="big">

	<div class="clearfix"></div>

	<div class="form-group">
		<label for="">Родной язык:</label>
		{!! Form::select('native_language_id', $languages->lists('title', 'id'), isset($resume) ? $resume->native_language_id : '', ['class' => 'form-control', 'placeholder' => 'Выберите родной язык']) !!}
	</div>

	<div class="form-group">
		<label for="">Другие языки</label>
		<div class="coversq">
			<div class="dynamic_container">
				@foreach(isset($resume) && $resume->resumeLanguages->count() > 0 ? $resume->resumeLanguages : old('languages', [1]) as $key => $val)
					<div class="dynamic_block">
						<div class="form-group {{ $errors->has("language_id.{$key}") || $errors->has("language_proficiency_id.{$key}")  ? 'has-error' : '' }}">
							<input type="hidden" name="languages[]">
							@if(isset($resume))
								{!! Form::select('lang_id[]', $languages->lists('title', 'id'), $val->language_id, ['class' => 'form-control']) !!}
								{!! Form::select('language_proficiency_id[]', $language_proficiencies->lists('title', 'id'), $val->language_proficiency_id, ['class' => 'form-control']) !!}
							@else
								<select name="language_id[]" id="" class="form-control">
									<option value="">Выберите язык</option>
									@foreach($languages as $entry)
										<option @if($entry->id == old("language_id.{$key}")) selected @endif value="{{ $entry->id }}">{{ $entry->title }}</option>
									@endforeach
								</select>
								<select name="language_proficiency_id[]" id="" class="form-control">
									@foreach($language_proficiencies as $entry)
										<option @if($entry->id == old("language_proficiency_id.{$key}")) selected @endif value="{{ $entry->id }}">{{ $entry->title }}</option>
									@endforeach
								</select>
							@endif
							@if($errors->has("language_id.{$key}"))
								<span class="help-block">{{ $errors->first("language_id.{$key}") }}</span>
							@elseif($errors->has("language_proficiency_id.{$key}"))
								<span class="help-block">{{ $errors->first("language_proficiency_id.{$key}") }}</span>
							@endif
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</div>
</div> <!-- end big -->

<div class="about">
	<h2>Опыт работы</h2>
</div> <!-- end about -->
<div class="big">
	<div class="dynamic_container">
		@foreach(isset($resume) && $resume->workExperiences->count() > 0 ? $resume->workExperiences : old('work_experiences', [1]) as $key => $val)
			<div class="dynamic_block">
				<input type="hidden" name="work_experiences[]">

				<div class="clearfix"></div>

				<div class="form-group {{ $errors->has("position.{$key}") ? 'has-error' : '' }}">
					<label for="">Должность:</label>
					@if(isset($resume))
						<input name="position[]" value="{{ $val->position }}" class="form-control" type="text" placeholder="Введите занимаемую вами должность">
					@else
						<input name="position[]" value="{{ old("position.{$key}") }}" class="form-control" type="text" placeholder="Введите занимаемую вами должность">
					@endif
					@if($errors->has("position.{$key}"))
						<span class="help-block">{{ $errors->first("position.{$key}") }}</span>
					@endif
				</div>

				<div class="form-group {{ $errors->has("organization.{$key}") ? 'has-error' : '' }}">
					<label for="">Организация:</label>
					@if(isset($resume))
						<input name="organization[]" value="{{ $val->organization }}" class="form-control" type="text" placeholder="Введите организацию">
					@else
						<input name="organization[]" value="{{ old("organization.{$key}") }}" class="form-control" type="text" placeholder="Введите организацию">
					@endif
					@if($errors->has("organization.{$key}"))
						<span class="help-block">{{ $errors->first("organization.{$key}") }}</span>
					@endif
				</div>

				<div class="form-group {{ $errors->has("scope.{$key}") || $errors->has("exp_scope_id.{$key}")  ? 'has-error' : '' }}">
					<label for="">Сфера деятельности:</label>
					@if(isset($resume))
						<input name="scope[]" value="{{ $val->scope }}" id="drop" class="form-control" type="text" placeholder="Например: “Туристическая компания”">
						{!! Form::select('exp_scope_id[]', $scopes->lists('title', 'id'), $val->exp_scope_id, ['class' => 'form-control', 'id' => "drop"]) !!}
					@else
						<input name="scope[]" value="{{ old("scope.{$key}") }}" id="drop" class="form-control" type="text" placeholder="Например: “Туристическая компания”">
						<select id="drop" name="exp_scope_id[]" id="" class="form-control">
							<option value="">Выберите из списка</option>
							@foreach($scopes as $entry)
								<option @if($entry->id == old("exp_scope_id.{$key}")) selected @endif value="{{ $entry->id }}">{{ $entry->title }}</option>
							@endforeach
						</select>
					@endif
					@if($errors->has("scope.{$key}"))
						<span class="help-block">{{ $errors->first("scope.{$key}") }}</span>
					@elseif($errors->has("exp_scope_id.{$key}"))
						<span class="help-block">{{ $errors->first("exp_scope_id.{$key}") }}</span>
					@endif
				</div>

				<div class="form-group {{ $errors->has("exp_city_id.{$key}") ? 'has-error' : '' }}">
					<label for="">Город</label>
					@if(isset($resume))
						{!! Form::select('exp_city_id[]', $cities->lists('title', 'id'), $val->exp_city_id, ['class' => 'form-control']) !!}
					@else
						<select name="exp_city_id[]" id="" class="form-control">
							<option value="">Выберите город</option>
							@foreach($cities as $entry)
								<option @if($entry->id == old("exp_city_id.{$key}")) selected @endif value="{{ $entry->id }}">{{ $entry->title }}</option>
							@endforeach
						</select>
					@endif
					@if($errors->has("exp_city_id.{$key}"))
						<span class="help-block">{{ $errors->first("exp_city_id.{$key}") }}</span>
					@endif
				</div>

				<div class="form-group {{ $errors->has("exp_org_site.{$key}") ? 'has-error' : '' }}">
					<label for="">Сайт организации:</label>
					@if(isset($resume))
						<input name="exp_org_site[]" value="{{ $val->exp_org_site }}" class="form-control" type="text" placeholder="Введите сайт организации">
					@else
						<input name="exp_org_site[]" value="{{ old("exp_org_site.{$key}") }}" class="form-control" type="text" placeholder="Введите сайт организации">
					@endif
					@if($errors->has("exp_org_site.{$key}"))
						<span class="help-block">{{ $errors->first("exp_org_site.{$key}") }}</span>
					@endif
				</div>

				<div class="form-group {{ $errors->has("exp_start_work.{$key}") ? 'has-error' : '' }}">
					<label for="">Начало работы:</label>
					@if(isset($resume))
						<input name="exp_start_work[]" value="{{ $val->exp_start_work }}" id="date" class="form-control" type="date">
					@else
						<input name="exp_start_work[]" value="{{ old("exp_start_work.{$key}") }}" id="date" class="form-control" type="date">
					@endif
					{{--<input name="exp_is_working[{{ $val->id }}]" value="0" type="checkbox" checked style="display: none;">--}}
					{{--<label class="label--checkbox">--}}
					{{--<input name="exp_is_working[{{ $val->id }}]" value="1" id="chexbox" type="checkbox" class="checkbox is_working_checkbox" @if((isset($resume) && $val->exp_is_working) || old("exp_is_working.{$key}")) checked @endif>--}}
					{{--По настоящее время--}}
					{{--</label>--}}
					<div class="clearfix"></div>
					@if($errors->has("exp_start_work.{$key}"))
						<span class="help-block">{{ $errors->first("exp_start_work.{$key}") }}</span>
					@endif
				</div>

				<div class="form-group {{ $errors->has("exp_end_work.{$key}") ? 'has-error' : '' }}">
					<label for="">Окончание работы:</label>
					@if(isset($resume))
						<input name="exp_end_work[]" value="{{ $val->exp_end_work }}" id="date" class="form-control" type="date" @if(isset($resume) && $val->exp_is_working) disabled @endif>
					@else
						<input name="exp_end_work[]" value="{{ old("exp_end_work.{$key}") }}" id="date" class="form-control" type="date" @if(old("exp_is_working.{$key}")) disabled @endif>
					@endif
					<div class="clearfix"></div>
					@if($errors->has("exp_end_work.{$key}"))
						<span class="help-block">{{ $errors->first("exp_end_work.{$key}") }}</span>
					@endif
				</div>

				<div class="form-group {{ $errors->has("exp_achievements.{$key}") ? 'has-error' : '' }}">
					<label for="">Достижения, результаты, функции, обязанности:</label>
					@if(isset($resume))
						<textarea name="exp_achievements[]" class="form-control" placeholder="">{{ $val->exp_achievements }}</textarea>
					@else
						<textarea name="exp_achievements[]" class="form-control" placeholder="">{{ old("exp_achievements.{$key}") }}</textarea>
					@endif
					@if($errors->has("exp_achievements.{$key}"))
						<span class="help-block">{{ $errors->first("exp_achievements.{$key}") }}</span>
					@endif
				</div>
			</div>
		@endforeach
	</div>
</div> <!-- end big -->