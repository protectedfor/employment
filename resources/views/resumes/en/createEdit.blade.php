@extends('layouts.app')
@section('css-assets')
    <style>
        .hidden {
            display: none !important;
            visibility: hidden !important;
        }
    </style>
@endsection
@section('js-assets')
    <script src="{{ asset('js/flow.min.js') }}"></script>
    <script src="{{ asset('js/sortable.jquery.binding.js') }}"></script>
    <script src="{{ asset('js/Sortable.min.js') }}"></script>
    <script src="{{ asset('js/init-flow.js') }}"></script>
    <script src="{{ asset('js/resume-script.js') }}"></script>
{{--    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>--}}
    {{--<script> var editor = CKEDITOR.replace( '', { customConfig : 'config.js', } ); </script>--}}
@endsection
@section('content')
    <div class="rezumenew create-edit-page">
        <div class="container">
            <div class="row">

	            <?php Request::get('lang') ? $lang = Request::get('lang') : (isset($resume) && $resume->language_id ? $lang = $resume->language_id : $lang = '1') ?>
                <div class="backarrow">
                    <img src="/img/png/arrwoback.png" alt="">
	                <a href="{{ route('resumes.index') }}">Resumes</a>
                </div> <!-- end backarrow -->

	            <h1>{{ isset($resume) ? 'Edit resume "' . $resume->career_objective .'"' : 'New resume' }}</h1>
                {!! Form::open(['route' => isset($resume) ? ['resumes.update', $resume->id] : 'resumes.store', 'method' => 'POST']) !!}
                <div class="coverwhite">
                    <div class="formcontainer">
                        <div class="contentform col-md-9">
                            @if(count($errors) > 0)
                                <div class="alert alert-danger" role="alert">
                                    <ul>
                                        <li>Errors while filling out the form!</li>
                                    </ul>
                                </div>
                            @endif
                            <div class="about">
                                <h2>Overview</h2>
                                <div class="checks">
	                                <div class="card">
		                                <span>Language of resume</span>
		                                @if(Request::url() == route('resumes.create'))
			                                <label class="radio">
				                                <input class="lang" id="radio1" type="radio" name="lang" value="1" data-target-page="{{ route('resumes.create', ['lang' => '1']) }}" {{ $lang == '1' ? 'checked' : '' }}><span class="outer"><span class="inner"></span></span>
				                                {{ $lang == '2' ? 'Russian' : 'Русский' }}
			                                </label>
			                                <label class="radio">
				                                <input class="lang" id="radio2" type="radio" name="lang" value="2" data-target-page="{{ route('resumes.create', ['lang' => '2']) }}" {{ $lang == '2' ? 'checked' : '' }}><span class="outer"><span class="inner"></span></span>
				                                {{ $lang == '2' ? 'English' : 'Английский' }}
			                                </label>
		                                @else
			                                <label class="radio">
				                                <input class="lang" id="radio1" type="radio" name="lang" value="1" data-target-page="{{ route('resumes.edit', [$resume->id, 'lang' => '1']) }}" {{ $lang == '1' ? 'checked' : '' }}><span class="outer"><span class="inner"></span></span>
				                                {{ $lang == '2' ? 'Russian' : 'Русский' }}
			                                </label>
			                                <label class="radio">
				                                <input class="lang" id="radio2" type="radio" name="lang" value="2" data-target-page="{{ route('resumes.edit', [$resume->id, 'lang' => '2']) }}" {{ $lang == '2' ? 'checked' : '' }}><span class="outer"><span class="inner"></span></span>
				                                {{ $lang == '2' ? 'English' : 'Английский' }}
			                                </label>
		                                @endif
	                                </div>
                                </div>

                            </div> <!-- end about -->

                            <div class="big">
                                <div class="titlephoto">
                                    <p>Photo</p>
                                </div>

                                <div class="photodownload imageUpload" data-target="{{ route('ajax.uploadImage') }}" data-token="{{ csrf_token() }}">
                                    <section>
                                        <div class="img-wrapper thumbnail">
	                                        @if (isset($resume) && $resume->photo != '')
		                                        <img class="has-value" src="{{ route('imagecache', ['95_130', $resume->photo])}}" id="img" alt="">
		                                        <img class="no-value hidden" src="https://placehold.it/95x130" alt="">
	                                        @elseif (!isset($resume) && ($profile->logo != '' || old('photo')))
                                                <img class="has-value" src="{{ old('photo') ? route('imagecache', ['95_130', old('photo')]) : route('imagecache', ['95_130', $profile->logo]) }}" id="img" alt="">
		                                        <img class="no-value hidden" src="https://placehold.it/95x130" alt="">
	                                        @else
		                                        <img class="has-value hidden" src="" id="img" alt="">
                                                <img class="no-value" src="https://placehold.it/95x130" alt="">
											@endif
                                        </div>
                                        <ul>
                                            <li class="red">
                                                <a id="removephoto" class="btn imageRemove">Remove</a>
                                            </li>
                                            <li class="blue">
                                                <a id="addphoto" class="btn imageBrowse">Upload</a>
                                            </li>
                                        </ul>
                                    </section>
                                    <div class="errors">
                                        {{--<p class="help-block">Invalid image type</p>--}}
                                    </div>
                                    <input name="photo" value="{{ isset($resume) ? $resume->photo : (old('photo') ? old('photo') : $profile->logo) }}" type="hidden" class="imageValue" id="photo" accept="image/*;capture=camera" capture="camera"/>
                                </div> <!-- end photodownload -->

                                <div class="clearfix"></div>

                                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                    <label for="">Name:</label>
                                    <input name="name" value="{{ isset($resume) ? $resume->name : (old('name') ? old('name') : $profile->name) }}" class="form-control" type="text" placeholder="Enter your name">
                                    @if($errors->has('name'))
                                        <span class="help-block">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('sname') ? 'has-error' : '' }}">
                                    <label for="">Surname:</label>
                                    <input name="sname" value="{{ isset($resume) ? $resume->sname : (old('sname') ? old('sname') : $profile->sname) }}" class="form-control" type="text" placeholder="Enter your surname">
                                    @if($errors->has('sname'))
                                        <span class="help-block">{{ $errors->first('sname') }}</span>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('mname') ? 'has-error' : '' }}">
                                    <label for="">Middlename:</label>
                                    <input name="mname" value="{{ isset($resume) ? $resume->mname : (old('mname') ? old('mname') : $profile->mname) }}" class="form-control" type="text" placeholder="Enter your middlename">
                                    @if($errors->has('mname'))
                                        <span class="help-block">{{ $errors->first('mname') }}</span>
                                    @endif
                                </div>
                              <div class="form-group {{ $errors->has('date_of_birth') ? 'has-error' : '' }}">
                                    <div class='input-group ' id='datetimepicker5' style="width: 100%">
                                        <label for="">Date of Birth:</label>
                                        <input name="date_of_birth" value="{{ isset($resume) ? $resume->date_of_birth->format('d.m.Y') : (old('date_of_birth') ? old('date_of_birth') : ($profile->date_of_birth ? $profile->date_of_birth->format('d.m.Y'): '')) }}" id="date" class="form-control smalldate date datepicker" type="text">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                    </div>
                                    @if($errors->has('date_of_birth'))
                                        <span class="help-block">{{ $errors->first('date_of_birth') }}</span>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                                    <label for="">Phone:</label>
                                    <input name="phone" value="{{ isset($resume) ? $resume->phone : (old('phone') ? old('phone') : $profile->phone) }}" class="form-control" type="text" placeholder="+996551334455">
                                    @if($errors->has('phone'))
                                        <span class="help-block">{{ $errors->first('phone') }}</span>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('citizenship_id') ? 'has-error' : '' }}">
                                    <label for="">Citizenship:</label>
                                    {!! Form::select('citizenship_id', $citizenships->lists('english_slug', 'id'), isset($resume) ? $resume->citizenship_id : $profile->citizenship_id, ['class' => 'form-control', 'placeholder' => 'Select citizenship']) !!}
                                    @if($errors->has('citizenship_id'))
                                        <span class="help-block">{{ $errors->first('citizenship_id') }}</span>
                                    @endif
                                </div>
                            </div> <!-- end big -->
                        </div> <!-- end contentform -->

                        <div class="col-md-3">
	                        <h2>Follow our advice and get your job offer soon!</h2>
                            <p>Before you begin compiling your resume answer for the question, "Who is my potential employer?". Depending on the answer, select the language of your resume.</p>
                            <p>According to statistics, resume with photo attracts more employers. So, please upload your photo to make employers interested in your resume.</p>
                            <p> Include as much information as possible. Employers expect maximum openness on your part </p>
                        </div>
                    </div> <!-- end formcontainer -->

                    <div class="formcontainertwo">
                        <div class="contentformtwo col-md-9">

                            <div class="about">
                                <h2>Education</h2>
                            </div> <!-- end about -->
                            <div class="big">
                                <div class="dynamic_container">
                                    @foreach(isset($resume) && $resume->institutions->count() > 0 ? $resume->institutions : old('institutions', [1]) as $key => $val)
                                        <div class="dynamic_block">
                                            <input type="hidden" name="institutions[]">
                                            <p class="remove_block" style="cursor: pointer;float: right;">X</p>
                                            <div class="clearfix"></div>
                                            <div class="form-group {{ $errors->has("education_id.{$key}") ? 'has-error' : '' }}" id="bottomline">
                                                <label for="">Level of education:</label>
                                                @if(isset($resume))
		                                            {!! Form::select('education_id[]', $educations->lists('title', 'id'), $val->education_id , ['class' => 'form-control', 'placeholder' => "Select education"]) !!}
                                                @else
		                                            <select name="education_id[]" id="" class="form-control">
		                                                <option value="">Select level of education</option>
	                                                    @foreach($educations as $entry)
	                                                        <option @if($entry->id == old("education_id.{$key}")) selected @endif value="{{ $entry->id }}">{{ $entry->english_slug }}</option>
	                                                    @endforeach
		                                            </select>
                                                @endif
                                                @if($errors->has("education_id.{$key}"))
                                                    <span class="help-block">{{ $errors->first("education_id.{$key}") }}</span>
                                                @endif
                                            </div>

                                            <div class="form-group {{ $errors->has("institution.{$key}") ? 'has-error' : '' }}">
                                                <label for="">Institution:</label>
	                                            @if(isset($resume))
		                                            <input name="institution[]" value="{{ $val->institution }}" class="form-control" type="text" placeholder="Indicate full name of your institution">
	                                            @else
                                                    <input name="institution[]" value="{{ old("institution.{$key}") }}" class="form-control" type="text" placeholder="Indicate full name of your institution">
	                                            @endif
                                                @if($errors->has("institution.{$key}"))
                                                    <span class="help-block">{{ $errors->first("institution.{$key}") }}</span>
                                                @endif
                                            </div>

                                            <div class="form-group {{ $errors->has("department.{$key}") ? 'has-error' : '' }}">
                                                <label for="">Department:</label>
	                                            @if(isset($resume))
		                                            <input name="department[]" value="{{ $val->department }}" class="form-control" type="text" placeholder="Indicate department you have studied">
	                                            @else
                                                    <input name="department[]" value="{{ old("department.{$key}") }}" class="form-control" type="text" placeholder="Indicate department you have studied">
	                                            @endif
                                                @if($errors->has("department.{$key}"))
                                                    <span class="help-block">{{ $errors->first("department.{$key}") }}</span>
                                                @endif
                                            </div>

                                            <div class="form-group {{ $errors->has("specialty.{$key}") ? 'has-error' : '' }}">
                                                <label for="">Major:</label>
	                                            @if(isset($resume))
		                                            <input name="specialty[]" value="{{ $val->specialty }}" class="form-control" type="text" placeholder='For example, “Lawyer” or “IT Specialist”'>
	                                            @else
                                                    <input name="specialty[]" value="{{ old("specialty.{$key}") }}" class="form-control" type="text" placeholder='For example, “Lawyer” or “IT Specialist”'>
	                                            @endif
                                                @if($errors->has("specialty.{$key}"))
                                                    <span class="help-block">{{ $errors->first("specialty.{$key}") }}</span>
                                                @endif
                                            </div>

                                            <div class="form-group {{ $errors->has("year_of_ending.{$key}") ? 'has-error' : '' }}">
                                                <label for="">Year of graduation</label>
	                                            @if(isset($resume))
		                                            {!! Form::select('year_of_ending[]', array_combine(range(date("Y") + 7, 1910), range(date("Y") + 7, 1910)), $val->year_of_ending, ['class' => 'form-control', 'placeholder' => 'Select']) !!}
	                                            @else
		                                            <select name="year_of_ending[]" id="" class="form-control">
			                                            <option value="">Select</option>
			                                            @foreach(array_combine(range(date("Y") + 7, 1910), range(date("Y") + 7, 1910)) as $entry)
				                                            <option @if($entry == old("year_of_ending.{$key}")) selected @endif value="{{ $entry }}">{{ $entry }}</option>
			                                            @endforeach
		                                            </select>
	                                            @endif
                                                <label id="back" for="">If you have not graduated yet, indicate the expected year of graduation</label>
                                                @if($errors->has("year_of_ending.{$key}"))
                                                    <span class="help-block">{{ $errors->first("year_of_ending.{$key}") }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn open add_dynamic_block">Add institution</button>
                            </div> <!-- end big -->

	                        <div class="about">
		                        <h2>Additional education</h2>
	                        </div> <!-- end about -->
	                        <div class="big">
		                        <div class="dynamic_container">
			                        @foreach(isset($resume) && $resume->extraInstitutions->count() > 0 ? $resume->extraInstitutions : old('extraInstitutions', [1]) as $key => $val)
				                        <div class="dynamic_block">
					                        <input type="hidden" name="extraInstitutions[]">
					                        <p class="remove_block" style="cursor: pointer;float: right;">X</p>
					                        <div class="clearfix"></div>
					                        @InputBlock([$type = "input", $item = 'extra_inst_title[]', $label = "Название курса/тренинга", $var = is_object($val) ? $val : null, $errorKey = $key, $p = "placeholder=\"Введите название курса/тренинга\""])
					                        @InputBlock([$type = "input", $item = 'extra_inst_organizer[]', $label = "Организатор курса/тренинга", $var = is_object($val) ? $val : null, $errorKey = $key, $p = "placeholder=\"Укажите организатора курса/тренинга\""])
					                        @InputBlock([$type = "input", $item = 'extra_inst_date[]', $label = "Дата", $var = is_object($val) ? $val : null, $errorKey = $key, $p = "placeholder=\"Введите дату\""])
					                        @InputBlock([$type = "input", $item = 'extra_inst_location[]', $label = "Город/Страна", $var = is_object($val) ? $val : null, $errorKey = $key, $p = "placeholder=\"Введите город/страну\""])
				                        </div>
			                        @endforeach
		                        </div>
		                        <button type="button" class="btn open add_dynamic_block">Указать еще один курс/тренинг</button>
	                        </div> <!-- end big -->
                        </div> <!-- end contentform -->

	                    <div class="col-md-3">
		                    <p> It is important to provide information about education. Many employers pay attention to education, particularly for those jobs where special expertise is required. </p>
		                    <p> It is necessary to specify only the higher or secondary special education. Do not include secondary education, since such information is not needed to the employer. </p>
	                    </div>
                    </div> <!-- end formcontainer -->

                    <div class="formcontainertree">
                        <div class="contentformtree col-md-9">

                            <div class="about">
                                <h2>Languages</h2>
                            </div> <!-- end about -->

                            <div class="big">

                                <div class="clearfix"></div>

	                            <div class="form-group  {{ $errors->has('native_language_id') ? 'has-error' : '' }}">
                                    <label for="">Native language:</label>
                                    {!! Form::select('native_language_id', $languages->lists('english_slug', 'id'), isset($resume) ? $resume->native_language_id : '', ['class' => 'form-control', 'placeholder' => 'Select native language']) !!}
		                            @if($errors->has('native_language_id'))
			                            <span class="help-block">{{ $errors->first('native_language_id') }}</span>
		                            @endif
                                </div>

                                <div class="form-group">
                                    <label for="">Other languages</label>
                                    <div class="coversq">
                                        <div class="dynamic_container">
                                            @foreach(isset($resume) && $resume->resumeLanguages->count() > 0 ? $resume->resumeLanguages : old('languages', [1]) as $key => $val)
                                                <div class="dynamic_block">
                                                    <div class="form-group {{ $errors->has("language_id.{$key}") || $errors->has("language_proficiency_id.{$key}")  ? 'has-error' : '' }}">
                                                        <input type="hidden" name="languages[]">
                                                        <p class="remove_block" data-max-blocks="{{ count($languages) }}" style="cursor: pointer;float: right;">X</p>
	                                                    @if(isset($resume))
		                                                    {!! Form::select('language_id[]', $languages->lists('english_slug', 'id'), $val->language_id, ['class' => 'form-control']) !!}
		                                                    {!! Form::select('language_proficiency_id[]', $language_proficiencies->lists('title', 'id'), $val->language_proficiency_id, ['class' => 'form-control']) !!}
	                                                    @else
	                                                        <select name="language_id[]" id="" class="form-control">
	                                                            <option value="">Select language</option>
	                                                            @foreach($languages as $entry)
	                                                                <option @if($entry->id == old("language_id.{$key}")) selected @endif value="{{ $entry->id }}">{{ $entry->english_slug }}</option>
	                                                            @endforeach
	                                                        </select>
	                                                        <select name="language_proficiency_id[]" id="" class="form-control">
		                                                        <option value="">Select language proficiency</option>
	                                                            @foreach($language_proficiencies as $entry)
	                                                                <option @if($entry->id == old("language_proficiency_id.{$key}")) selected @endif value="{{ $entry->id }}">{{ $entry->english_slug }}</option>
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
	                                        <button type="button" class="btn open add_dynamic_block" data-max-blocks="{{ count($languages) }}" style="{{ (count(old('languages')) < count($languages)) ?: 'display: none;' }}">Add language</button>
                                    </div>
                                </div>
                            </div> <!-- end big -->
                        </div> <!-- end contentform -->

                        <div class="col-md-3">
                            <p>Specify all languages you speak, even those on which you have the basic knowledge. Remember, knowledge of several languages can give you a good advantage over other applicants.</p>
                        </div>
                    </div> <!-- end formcontainer -->

                    <div class="formcontainertrees">
                        <div class="contentformtrees col-md-9">

                            <div class="about">
                                <h2>Work experience</h2>
                            </div> <!-- end about -->

                            <div class="big">
                                <div class="dynamic_container">
                                    @foreach(isset($resume) && $resume->workExperiences->count() > 0 ? $resume->workExperiences : old('work_experiences', [1]) as $key => $val)
                                        <div class="dynamic_block">
                                            <input type="hidden" name="work_experiences[]">
                                            <p class="remove_block" style="cursor: pointer;float: right;">X</p>

                                            <div class="clearfix"></div>

                                            <div class="form-group {{ $errors->has("position.{$key}") ? 'has-error' : '' }}">
                                                <label for="">Position:</label>
	                                            @if(isset($resume))
		                                            <input name="position[]" value="{{ $val->position }}" class="form-control" type="text" placeholder="Enter the position you are looking for">
	                                            @else
                                                    <input name="position[]" value="{{ old("position.{$key}") }}" class="form-control" type="text" placeholder="Enter the position you are looking for">
	                                            @endif
                                                @if($errors->has("position.{$key}"))
                                                    <span class="help-block">{{ $errors->first("position.{$key}") }}</span>
                                                @endif
                                            </div>

                                            <div class="form-group {{ $errors->has("organization.{$key}") ? 'has-error' : '' }}">
                                                <label for="">Employer’s name:</label>
	                                            @if(isset($resume))
		                                            <input name="organization[]" value="{{ $val->organization }}" class="form-control" type="text" placeholder="Enter full name of organization">
	                                            @else
                                                    <input name="organization[]" value="{{ old("organization.{$key}") }}" class="form-control" type="text" placeholder="Enter full name of organization">
	                                            @endif
                                                @if($errors->has("organization.{$key}"))
                                                    <span class="help-block">{{ $errors->first("organization.{$key}") }}</span>
                                                @endif
                                            </div>

                                            <div class="form-group {{ $errors->has("scope.{$key}") || $errors->has("exp_scope_id.{$key}")  ? 'has-error' : '' }}">
                                                <label for="">Type of work:</label>
	                                            @if(isset($resume))
		                                            <input name="scope[]" value="{{ $val->scope }}" id="drop" class="form-control" type="hidden" placeholder='Select type of work'>
		                                            {!! Form::select('exp_scope_id[]', $scopes->lists('english_slug', 'id'), $val->exp_scope_id, ['class' => 'form-control'/*, 'id' => "drop"*/]) !!}
	                                            @else
                                                    <input name="scope[]" value="{{ old("scope.{$key}") }}" id="drop" class="form-control" type="hidden" placeholder='Select type of work'>
	                                                <select {{--id="drop" --}}name="exp_scope_id[]" id="" class="form-control">
	                                                    <option value="">Select from the list</option>
	                                                    @foreach($scopes as $entry)
	                                                        <option @if($entry->id == old("exp_scope_id.{$key}")) selected @endif value="{{ $entry->id }}">{{ $entry->english_slug }}</option>
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
                                                <label for="">Location:</label>
	                                            @if(isset($resume))
		                                            {!! Form::select('exp_city_id[]', $cities->lists('english_slug', 'id'), $val->exp_city_id, ['class' => 'form-control']) !!}
	                                            @else
	                                                <select name="exp_city_id[]" id="" class="form-control">
	                                                    <option value="">Select location</option>
	                                                    @foreach($cities as $entry)
	                                                        <option @if($entry->id == old("exp_city_id.{$key}")) selected @endif value="{{ $entry->id }}">{{ $entry->english_slug }}</option>
	                                                    @endforeach
	                                                </select>
	                                            @endif
                                                @if($errors->has("exp_city_id.{$key}"))
                                                    <span class="help-block">{{ $errors->first("exp_city_id.{$key}") }}</span>
                                                @endif
                                            </div>

                                            <div class="form-group {{ $errors->has("exp_org_site.{$key}") ? 'has-error' : '' }}">
                                                <label for="">Employer’s website:</label>
	                                            @if(isset($resume))
		                                            <input name="exp_org_site[]" value="{{ $val->exp_org_site }}" class="form-control" type="text" placeholder="Enter Employer’s website">
	                                            @else
                                                    <input name="exp_org_site[]" value="{{ old("exp_org_site.{$key}") }}" class="form-control" type="text" placeholder="Enter Employer’s website">
	                                            @endif
                                                @if($errors->has("exp_org_site.{$key}"))
                                                    <span class="help-block">{{ $errors->first("exp_org_site.{$key}") }}</span>
                                                @endif
                                            </div>

                                           <div class="form-group {{ $errors->has("exp_start_work.{$key}") ? 'has-error' : '' }}">
                                                <label for="">Start date:</label>
                                                <div class='input-group date datepicker' id='datetimepicker5'>
                                                    @if(isset($resume))
                                                        <input name="exp_start_work[]" value="{{ $val->exp_start_work->format('d.m.Y') }}" id="date" class="form-control" type="text">
                                                    @else
                                                        <input name="exp_start_work[]" value="{{ old("exp_start_work.{$key}") }}" id="date" class="form-control" type="text">
                                                    @endif
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                </div>
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
                                                     <label for="">End date:
                                                <p>(по умолчанию по настоящее время)</p>
                                                </label>
                                                <div class='input-group date datepicker' id='datetimepicker5'>
                                           
                                                    @if(isset($resume))
                                                        <input name="exp_end_work[]" value="{{ $val->exp_end_work ? $val->exp_end_work->format('d.m.Y') : '' }}" id="date" class="form-control" type="text" @if(isset($resume) && $val->exp_is_working) disabled @endif>
                                                    @else
                                                        <input name="exp_end_work[]" value="{{ old("exp_end_work.{$key}") }}" id="date" class="form-control" type="text" @if(old("exp_is_working.{$key}")) disabled @endif>
                                                    @endif
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                </div>
                                                <div class="clearfix"></div>
                                                @if($errors->has("exp_end_work.{$key}"))
                                                    <span class="help-block">{{ $errors->first("exp_end_work.{$key}") }}</span>
                                                @endif
                                            </div>

                                            <div class="form-group {{ $errors->has("exp_achievements.{$key}") ? 'has-error' : '' }} cke_editor_exp_achievements">
                                                <label for="">Achievements, results, responsibilities:</label>
	                                            @if(isset($resume))
		                                            <textarea name="exp_achievements[]" class="form-control ckeditor" placeholder="">{!! $val->exp_achievements !!}</textarea>
	                                            @else
                                                    <textarea name="exp_achievements[]" class="form-control ckeditor" placeholder="">{!! old("exp_achievements.{$key}") !!}</textarea>
	                                            @endif
                                                @if($errors->has("exp_achievements.{$key}"))
                                                    <span class="help-block">{{ $errors->first("exp_achievements.{$key}") }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn open add_dynamic_block">Add work experience</button>
                            </div> <!-- end big -->
                        </div> <!-- end contentform -->

                        <div class="col-md-3">
	                        <p>Employers usually look at the experience first as a key part of your resume. Do not hesitate to point out any work experience, including work during your studies, internships and even volunteer activities.</p>
	                        <p>It is highly recommended to fill in the " Achievements, results, responsibilities" line. This will give the employer a clear understanding of the role you played in the company, and what results you achieved.</p>
	                        <p>If you could not find location you needed, please indicate it in the line "Position". <i>For example, “Office-Manager (Dushanbe)”</i></p>
                        </div>
                    </div> <!-- end formcontainer -->

                    <div class="formcontainerfour">
                        <div class="contentformfour col-md-9">

                            <div class="about">
                                <h2>Work you are looking for</h2>
                            </div> <!-- end about -->

                            <div class="big">

                                <div class="clearfix"></div>

                                <div class="form-group {{ $errors->has('career_objective') ? 'has-error' : '' }}">
                                    <label for="">Position:</label>
                                    <input name="career_objective" value="{{ isset($resume) ? $resume->career_objective : old('career_objective') }}" class="form-control" type="text" placeholder="Enter position">
                                    @if($errors->has("career_objective"))
                                        <span class="help-block">{{ $errors->first("career_objective") }}</span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('city_id') ? 'has-error' : '' }}">
                                    <label for="">Location:</label>
                                    {!! Form::select('city_id', $filteredCities->lists('english_slug', 'id'), isset($resume) ? $resume->city_id : '', ['class' => 'form-control', 'placeholder' => 'Select location']) !!}
                                    @if($errors->has("city_id"))
                                        <span class="help-block">{{ $errors->first("city_id") }}</span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('salary') || $errors->has('currency_id') ? 'has-error' : '' }}">
                                    <label for="">Desired salary:</label>
                                    <input name="salary" value="{{ isset($resume) ? $resume->salary : old('salary') }}" id="cashe" class="form-control" type="text">
                                    {!! Form::select('currency_id', $currencies->lists('title', 'id'), isset($resume) ? $resume->currency_id : '', ['class' => 'form-control']) !!}
                                    @if($errors->has("salary"))
                                        <span class="help-block">{{ $errors->first("salary") }}</span>
                                    @elseif($errors->has("currency_id"))
                                        <span class="help-block">{{ $errors->first("salary") }}</span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('scope_id') ? 'has-error' : '' }}">
                                    <label for="">Type of work:</label>
                                    {!! Form::select('scope_id', $scopes->lists('english_slug', 'id'), isset($resume) ? $resume->scope_id : '', ['class' => 'form-control', 'id' => 'other', 'placeholder' => 'Select type of work']) !!}
                                    @if($errors->has("scope_id"))
                                        <span class="help-block">{{ $errors->first("scope_id") }}</span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('busyness_id') ? 'has-error' : '' }}">
                                    <label for="">Type of involvement:</label>
                                    {!! Form::select('busyness_id', $busynesses->lists('english_slug', 'id'), isset($resume) ? $resume->busyness_id : '', ['class' => 'form-control', 'id' => 'other', 'placeholder' => 'Select type of involvement']) !!}
                                    @if($errors->has("busyness_id"))
                                        <span class="help-block">{{ $errors->first("busyness_id") }}</span>
                                    @endif
                                </div>
                            </div> <!-- end big -->
                        </div> <!-- end contentform -->

	                    <div class="col-md-3">
		                    <p>In the " Work you are looking for" section specify what kind of work you are looking for, in what location and what condition.</p>
                            <p>In the " Position” line specify a position you want to be considered. If you want to specify more than one position, please be sure to create a separate resume for each desired position.</p>
                            <p>In the "Type of work" line select the area where you wish to work. This information will allow the employer to better define the scope of your interest.</p>
	                    </div>
                    </div> <!-- end formcontainer -->

                    <div class="formcontainerfive">
                        <div class="contentformfive col-md-9">

                            <div class="about">
                                <h2>Additional Information</h2>
                            </div> <!-- end about -->

                            <div class="big">
                                <div class="clearfix"></div>

                                {{--<div class="form-group">--}}
                                {{--<label for="">Ключевые навыки:</label>--}}
                                {{--<input class="form-control" type="text" name="tags" id="autocomplete" placeholder="Введите текст"/>--}}
                                {{--<span id="addme" class="postfix small-1 columns"><a class="add" href="#">Добавить</a></span>--}}
                                {{--<p class="selected clearfix">--}}
                                {{--<span class="label info">--}}
                                {{--Пример--}}
                                {{--<a href="" class="removeme close">×</a>--}}
                                {{--</span>--}}
                                {{--<span class="label info">--}}
                                {{--Пример2--}}
                                {{--<a href="" class="removeme close">×</a>--}}
                                {{--</span>--}}
                                {{--</p>--}}
                                {{--</div>--}}

                                <div class="form-group {{ $errors->has('about_me') ? 'has-error' : '' }}">
                                    <label for="">About me:<p>Indicate any additional information which makes your resume interesting and strong (i.e. professional skills or interests)</p></label>
                                    <textarea name="about_me" class="form-control" placeholder="Enter text">{!! isset($resume) ? $resume->about_me : (old('about_me') ? old('about_me') : $profile->about_me) !!}</textarea>
                                    @if($errors->has("about_me"))
                                        <span class="help-block">{{ $errors->first("about_me") }}</span>
                                    @endif
                                </div>

                            <div class="form-group">
                                    <label style="width: 100%; " for="">Вы можете прикрепить дополнительные документы (сертификаты, рекомендательные письма и т.д.)</label>
                                    <div class="clearfix"></div>

                                    <div style="width: 50%; float: left;" class="form-group {{ $errors->has('filename1') ? 'has-error' : '' }}">
                                        <input style="width: 95%" name="filename1" class="form-control" type="text" value="{{ isset($resume) && $resume->file1 ? $resume->filename1 : (old('filename1')) }}" placeholder="Введите название файла. Например: “Сертификат”">
                                        @if($errors->has("filename1"))
                                            <span class="help-block">{{ $errors->first("filename1") }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group" style="float: left: overflow: hidden;">
                                        <label for="file1"></label>
                                        <div class="uploadFile" data-target="{{ route('ajax.uploadFile', ['section' => 'resume_file']) }}" data-token="{{ csrf_token() }}">
                                            <div class="relative" style="position: relative;">
                                            <div class="resumse download">
                                                <div class="thumbnail" style="padding: 6px 6px 6px 12px; border-radius: 0; height: 33px">
                                                    <div class="no-value">
                                                        <span>{{ isset($resume) && $resume->file1 ? $resume->file1 : (old('file1') ? old('file1') : 'Файл не загружен') }}</span>
                                                    </div>
                                                    <div class="has-value hidden">
                                                        <a href="{{ route('home') }}" data-toggle="tooltip" title="Скачать"><i class="fa fa-fw fa-file-o"></i> <span></span></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="befores" style="position: absolute;right: 0; bottom: 0;">
                                                <div class="btn btn-primary imageBrowse" style="border-radius: 0; background: #2f9cf3; border: 0"><i class="fa fa-upload"></i>Select file<input class="b-button-upload_video" type="file"></div>
                                                <div class="btn btn-danger imageRemove"><i class="fa fa-times"></i>Удалить</div>
                                            </div>
                                            </div>


                                            <input name="file1" class="imageValue" type="hidden" value="{{ isset($resume) && $resume->file1 ? $resume->file1 : old('file1') }}">
                                            <div class="errors"></div>
                                        </div>
                                    </div>

                                    <div style="width: 50%; float: left;" class="form-group {{ $errors->has('filename2') ? 'has-error' : '' }}">
                                        <input style="width: 95%" name="filename2" class="form-control" type="text" value="{{ isset($resume) && $resume->file2 ? $resume->filename2 : (old('filename2')) }}" placeholder="Введите название файла. Например: “Сертификат”">
                                        @if($errors->has("filename2"))
                                            <span class="help-block">{{ $errors->first("filename2") }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group" style="float: left: overflow: hidden;">
                                        <label for="file2"></label>
                                        <div class="uploadFile" data-target="{{ route('ajax.uploadFile', ['section' => 'resume_file']) }}" data-token="{{ csrf_token() }}">
                                        <div class="relative" style="position: relative;">
                                            <div class="resumse download">
                                                <div class="thumbnail" style="padding: 6px 6px 6px 12px; border-radius: 0;">
                                                    <div class="no-value">
                                                        <span>{{ isset($resume) && $resume->file2 ? $resume->file2 : (old('file2') ? old('file2') : 'Файл не загружен') }}</span>
                                                    </div>
                                                    <div class="has-value hidden">
                                                        <a href="{{ route('home') }}" data-toggle="tooltip" title="Скачать"><i class="fa fa-fw fa-file-o"></i> <span></span></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="befores" style="position: absolute;right: 0; bottom: 0">
                                                <div class="btn btn-primary imageBrowse" style="border-radius: 0; background: #2f9cf3; border: 0; height: 33px;"><i class="fa fa-upload"></i>Select file<input class="b-button-upload_video" type="file"></div>
                                                <div class="btn btn-danger imageRemove"><i class="fa fa-times"></i>Удалить</div>
                                            </div>
                                        </div>
                                            <input name="file2" class="imageValue" type="hidden" value="{{ isset($resume) && $resume->file2 ? $resume->file2 : old('file2') }}">
                                            <div class="errors"></div>
                                        </div>
                                    </div>

                                    <div style="width: 50%; float: left;" class="form-group {{ $errors->has('filename3') ? 'has-error' : '' }}">
                                        <input style="width: 95%" name="filename3" class="form-control" type="text" value="{{ isset($resume) && $resume->file3 ? $resume->filename3 : (old('filename3')) }}" placeholder="Введите название файла. Например: “Сертификат”">
                                        @if($errors->has("filename3"))
                                            <span class="help-block">{{ $errors->first("filename3") }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group" style="float: left: overflow: hidden;">
                                        <label for="file3"></label>
                                        <div class="uploadFile" data-target="{{ route('ajax.uploadFile', ['section' => 'resume_file']) }}" data-token="{{ csrf_token() }}">
                                        <div class="relative" style="position: relative;">
                                            <div class="resumse download">
                                                <div class="thumbnail" style="padding: 6px 6px 6px 12px; border-radius: 0;">
                                                    <div class="no-value">
                                                        <span>{{ isset($resume) && $resume->file3 ? $resume->file3 : (old('file3') ? old('file3') : 'Файл не загружен') }}</span>
                                                    </div>
                                                    <div class="has-value hidden">
                                                        <a href="{{ route('home') }}" data-toggle="tooltip" title="Скачать"><i class="fa fa-fw fa-file-o"></i> <span></span></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="befores" style="position: absolute;right: 0; bottom: 0">
                                                <div class="btn btn-primary imageBrowse" style="border-radius: 0; background: #2f9cf3; border: 0; height: 33px;"><i class="fa fa-upload"></i>Select file<input class="b-button-upload_video" type="file"></div>
                                                <div class="btn btn-danger imageRemove"><i class="fa fa-times"></i>Удалить</div>
                                            </div>
                                        </div>
                                            <input name="file3" class="imageValue" type="hidden" value="{{ isset($resume) && $resume->file3 ? $resume->file3 : old('file3') }}">
                                            <div class="errors"></div>
                                        </div>
                                    </div>
                                </div>

	                            <div class="bottomline resumse">
		                            @if(isset($resume) && !$resume->draft)
			                            <input name="submit" value="Save" type="submit" id="blues">
		                            @else
			                            <h3>By posting resumes on the site, you agree to the <a target="_blank" href="{{ \App\Models\Page::whereId(6)->count() > 0 ? route('page', \App\Models\Page::whereId(6)->first()->slug) : '' }}">terms</a></h3>
			                            {{--@if(isset($resume))--}}
			                            {{--<input name="submit" value="Сохранить" type="submit" id="blues">--}}
			                            {{--@elseif(isset($resume) && $resume->draft)--}}
			                            <input name="preview" value="Preview" type="submit" id="white">
			                            <input name="submit" value="Publish" type="submit" id="blues">
			                            {{--@else--}}
			                            {{--<input name="preview" value="Предварительный просмотр" type="submit" id="white">--}}
			                            {{--<input name="submit" value="Отправить на публикацию" type="submit" id="blues">--}}
			                            {{--@endif--}}
			                            <p>Your resume will be published after moderation, but you can apply for vacancies without passing moderation.</p>
		                            @endif
	                            </div>
                            </div> <!-- end big -->
                        </div> <!-- end contentform -->

	                    <div class="col-md-3">
		                    <p> Use the "Additional Information" section to stand out among the other candidates. Think about what makes you a strong candidate and what you would like to draw the attention of the employer when considering your candidacy. </p>
		                    <p>Do not miss the opportunity to attach additional documents (certificates, letters of recommendation, etc.), which will strengthen your resume. Attached files will be available to all employers who will consider your resume unless you have hidden it in your <a href="{{ route('workers.profile') }}" target="_blank">profile</a>.</p>
	                    </div>
                    </div> <!-- end formcontainer -->
                </div> <!-- end bg -->
                {!! Form::close() !!}
            </div> <!-- end row   -->
        </div>
    </div>
@endsection