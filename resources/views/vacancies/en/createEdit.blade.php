@extends('layouts.app')
@section('js-assets')
	<script src="{{ asset('js/flow.min.js') }}"></script>
    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
    {{--<script> var editor = CKEDITOR.replace( 'overview',{ customConfig : 'config.js', } ); </script>--}}
@endsection
@section('content')
    <div class="vacansiasmall create-edit-page">
        <div class="container">
            <div class="row">

	            <?php Request::get('lang') ? $lang = Request::get('lang') : (isset($vacancy) && $vacancy->language_id ? $lang = $vacancy->language_id : $lang = '1') ?>
	            <div class="backarrow">
	                <img src="/img/png/arrwoback.png" alt="">
                    <a href="{{ route('vacancies.index') }}">Vacancies</a>
                </div> <!-- end backarrow -->

                <h1>{{ isset($vacancy) ? 'Editing vacancy "' . $vacancy->position .'"' : 'New vacancy' }}</h1>
                {!! Form::open(['route' => isset($vacancy) ? ['vacancies.update', $vacancy->id] : 'vacancies.store', 'method' => 'POST']) !!}
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
                                <h2>General information</h2>
                                <div class="checks">
	                                <div class="card">
		                                <span>Language of vacancy</span>
		                                @if(Request::url() == route('vacancies.create'))
			                                <label class="radio">
				                                <input class="lang hidden" id="radio1" type="radio" name="lang" value="1" data-target-page="{{ route('vacancies.create', ['lang' => '1']) }}" {{ $lang == '1' ? 'checked' : '' }}><span class="outer"><span class="inner"></span></span>
				                                {{ $lang == '2' ? 'Russian' : 'Русский' }}
			                                </label>
			                                <label class="radio">
				                                <input class="lang" id="radio2" type="radio" name="lang" value="2" data-target-page="{{ route('vacancies.create', ['lang' => '2']) }}" {{ $lang == '2' ? 'checked' : '' }}><span class="outer"><span class="inner"></span></span>
				                                {{ $lang == '2' ? 'English' : 'Английский' }}
			                                </label>
		                                @else
			                                <label class="radio">
				                                <input class="lang" id="radio1" type="radio" name="lang" value="1" data-target-page="{{ route('vacancies.edit', [$vacancy->id, 'lang' => '1']) }}" {{ $lang == '1' ? 'checked' : '' }}><span class="outer"><span class="inner"></span></span>
				                                {{ $lang == '2' ? 'Russian' : 'Русский' }}
			                                </label>
			                                <label class="radio">
				                                <input class="lang" id="radio2" type="radio" name="lang" value="2" data-target-page="{{ route('vacancies.edit', [$vacancy->id, 'lang' => '2']) }}" {{ $lang == '2' ? 'checked' : '' }}><span class="outer"><span class="inner"></span></span>
				                                {{ $lang == '2' ? 'English' : 'Английский' }}
			                                </label>
										@endif
	                                </div>
                                </div>
                            </div> <!-- end about -->

                            <div class="big">

                                <div class="clearfix"></div>

                                <div class="form-group {{ $errors->has('position') ? 'has-error' : '' }}">
                                    <label for="">Position:</label>
                                    <input name="position" value="{{ isset($vacancy) ? $vacancy->position : old('position') }}" class="form-control" type="text" placeholder="Enter position">
                                    @if($errors->has('position'))
                                        <span class="help-block">{{ $errors->first('position') }}</span>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('scope_id') ? 'has-error' : '' }}">
                                    <label for="">Field of work</label>
                                    {!! Form::select('scope_id', $scopes->lists('english_slug', 'id'), isset($vacancy) ? $vacancy->scope_id : '', ['class' => 'form-control', 'placeholder' => 'Select field of work']) !!}
                                    @if($errors->has('scope_id'))
                                        <span class="help-block">{{ $errors->first('scope_id') }}</span>
                                    @endif
                                </div>
	                            <div class="form-group {{ $errors->has('city_id') ? 'has-error' : '' }}">
		                            <label for="">Location:</label>
		                            {!! Form::select('city_id', $cities->lists('english_slug', 'id'), isset($vacancy) ? $vacancy->city_id : '', ['class' => 'form-control', 'placeholder' => 'Select location']) !!}
		                            @if($errors->has('city_id'))
			                            <span class="help-block">{{ $errors->first('city_id') }}</span>
		                            @endif
	                            </div>
	                            <p style="color: #a5a3a3;display: inline-block;margin-left: 193px; font-size: 13px;">*If you could not find location you needed, please select "Other location" and indicate it in the line "Position". <i>For example, “Office-Manager (Dushanbe)”</i></p>
                                {{--<div class="form-group {{ $errors->has('place_of_work') ? 'has-error' : '' }}">--}}
                                    {{--<label for="">Место работы:</label>--}}
                                    {{--<input name="place_of_work" value="{{ isset($vacancy) ? $vacancy->place_of_work : old('place_of_work') }}" class="form-control" type="text" placeholder="Введите место работы">--}}
                                    {{--@if($errors->has('place_of_work'))--}}
                                        {{--<span class="help-block">{{ $errors->first('place_of_work') }}</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                                <div class="form-group {{ $errors->has('education_id') ? 'has-error' : '' }}">
                                    <label for="">Required education:</label>
                                    {!! Form::select('education_id', $educations->lists('english_slug', 'id'), isset($vacancy) ? $vacancy->education_id : '', ['class' => 'form-control', 'placeholder' => 'Select required education']) !!}
                                    @if($errors->has('education_id'))
                                        <span class="help-block">{{ $errors->first('education_id') }}</span>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('busyness_id') ? 'has-error' : '' }}">
                                    <label for="">Required type of involvement:</label>
                                    {!! Form::select('busyness_id', $busynesses->lists('english_slug', 'id'), isset($vacancy) ? $vacancy->busyness_id : '', ['class' => 'form-control', 'placeholder' => 'Select required type of involvement']) !!}
                                    @if($errors->has('busyness_id'))
                                        <span class="help-block">{{ $errors->first('busyness_id') }}</span>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('work_graphite') ? 'has-error' : '' }}">
                                    <label for="">Required working hours:</label>
                                    <input name="work_graphite" value="{{ isset($vacancy) ? $vacancy->work_graphite : old('work_graphite') }}" class="form-control" type="text" placeholder="from 10:00 to 19:00">
                                    @if($errors->has('work_graphite'))
                                        <span class="help-block">{{ $errors->first('work_graphite') }}</span>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('experience') ? 'has-error' : '' }}">
                                    <label for="">Required minimal work experience:</label>
                                    <input name="experience" value="{{ isset($vacancy) ? $vacancy->experience : old('experience') }}" class="form-control" type="text" placeholder="For example: 1 year">
                                    @if($errors->has('experience'))
                                        <span class="help-block">{{ $errors->first('experience') }}</span>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('wages_from') || $errors->has('wages_to') ? 'has-error' : '' }}">
                                    <label for="">Salary:</label>
                                    <input name="wages_from" value="{{ isset($vacancy) ? $vacancy->wages_from : old('wages_from') }}" id="cashs" class="form-control" type="text" placeholder="From">
                                    <input name="wages_to" value="{{ isset($vacancy) ? $vacancy->wages_to : old('wages_to') }}" id="cashs" class="form-control" type="text" placeholder="To">
                                    {!! Form::select('currency_id', $currencies->lists('title', 'id'), isset($vacancy) ? $vacancy->currency_id : '', ['class' => 'form-control', 'id' => 'cashs']) !!}
                                    @if($errors->has('wages_from') || $errors->has('wages_to'))
                                        <span class="help-block">{{ $errors->has('wages_from') ? $errors->first('wages_from') :  ($errors->has('wages_to') ? $errors->first('wages_to') : '')}}</span>
                                    @endif
                                </div>
	                            <div class="form-group {{ $errors->has('expires_at') ? 'has-error' : '' }}">
		                            <div class='input-group ' >
			                            <label for="">Deadline:</label>
			                            <input class="date datepicker " id='datetimepicker5' name="expires_at" value="{{ isset($vacancy) && $vacancy->expires_at ? $vacancy->expires_at->format('d.m.Y') : old('expires_at') }}" id="date" class="form-control" type="text">
			                            <span class="input-group-addon -v" style="	right: -2px;top: 0px;width: 40px;height: 32px;"><i class="glyphicon glyphicon-calendar"></i></span>
			                            @if($errors->has('expires_at'))
				                            <span class="help-block">{{ $errors->first('expires_at') }}</span>
			                            @endif
		                            </div>
		                            <p style="color: red;display: inline-block;margin-left: 193px; font-size: 13px;">*If you leave it blank, your vacancy will be inactive after 30 days</p>
	                            </div>
                            </div> <!-- end big -->
                        </div> <!-- end contentform -->

                        <div class="col-md-3">
	                        <h2>Follow our tips to attract the attention of candidates and quickly find employees!</h2>
                            <p>If you wish to place vacancy announcement in English, please change the language into “English”</p>
                            <p>In order to exclude candidates who are not suitable to your requirements at the very beginning, try to describe all lines as detailed as possible. This will help to save your time and effort in searching for candidates.</p>
                        </div>
                    </div> <!-- end formcontainer -->

                    <!-- второе поле заполнение -->
                    <div class="formcontainer">
                        <div class="contentform col-md-9">

                            <div class="about">
                                <h2>Vacancy description</h2>
                            </div> <!-- end about -->

                            <div class="big">

                                <div class="clearfix"></div>
                                <div class="form-group {{ $errors->has('overview') ? 'has-error' : '' }}">
                                    <label for="">General information:</label>
                                    <textarea name="overview" class="form-control ckeditor" placeholder="For example: In connection with the expansion of the company requires managers work with corporate clients">{{ isset($vacancy) ? $vacancy->overview : old('overview') }}</textarea>
                                    @if($errors->has('overview'))
                                        <span class="help-block">{{ $errors->first('overview') }}</span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('qualification_requirements') ? 'has-error' : '' }}">
                                    <label for="">Requirements:</label>
                                    <textarea name="qualification_requirements" class="form-control ckeditor" placeholder="">{{ isset($vacancy) ? $vacancy->qualification_requirements : old('qualification_requirements') }}</textarea>
                                    @if($errors->has('qualification_requirements'))
                                        <span class="help-block">{{ $errors->first('qualification_requirements') }}</span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('duties') ? 'has-error' : '' }}">
                                    <label for="">Responsibilities:</label>
                                    <textarea name="duties" class="form-control ckeditor" placeholder="">{{ isset($vacancy) ? $vacancy->duties : old('duties') }}</textarea>
                                    @if($errors->has('duties'))
                                        <span class="help-block">{{ $errors->first('duties') }}</span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('conditions') ? 'has-error' : '' }}">
                                    <label for="">Employment conditions:</label>
                                    <textarea name="conditions" class="form-control ckeditor" placeholder="">{{ isset($vacancy) ? $vacancy->conditions : old('conditions') }}</textarea>
                                    @if($errors->has('conditions'))
                                        <span class="help-block">{{ $errors->first('conditions') }}</span>
                                    @endif
                                </div>
                            </div> <!-- end big -->
                        </div> <!-- end contentform -->

                        <div class="col-md-3">
	                        <p>Use the "General information" field to attract the attention of candidates. Here you can specify the reason for new vacancy announcement as well as to make the call for application.</p>
                            <p>Well described "Requirements" and "Responsibilities" will help you to reach your target candidates and exclude unsuitable candidates.</p>
                            <p>In the "Employment conditions" field, enter benefits your organization is offering for this position. In this field, you can also describe how to apply to this position and indicate what additional documents required. For example: To apply, you should send a resume, a letter of recommendation, a cover letter, etc.</p>.
	                        <p>Notifications on applications will be sent to your e-mail and <a href="{{ route('employers.profile') }}" target="_blank">your profile page</a>. If you disable this notification, you can view notifications in <a href="{{ route('employers.profile') }}" target="_blank">your profile page</a>.</p>
	                        <p>If your organization considers the applications in English only, please indicate it in the "Language of application" field so that all candidates will be able to learn about such requirement.</p>
                        </div>
                    </div> <!-- end formcontainer -->

                    <div class="formcontainer">
                        <div class="contentform col-md-9">

                            <div class="about">
                                <h2>“How to apply” options</h2>
                            </div> <!-- end about -->

                            <div class="big">

                                <div class="clearfix"></div>

                                <div class="form-group {{ $errors->has('response_email_notifications') ? 'has-error' : '' }}">
                                    <label for="">Notifications:</label>
                                    <input value="0" name="response_email_notifications" type="checkbox" checked style="display: none;">
                                    <label class="label--checkbox">
                                        <input value="1" name="response_email_notifications" id="chexbox" type="checkbox" class="checkbox" @if(isset($vacancy) ? $vacancy->response_email_notifications : (old('response_email_notifications') ? old('response_email_notifications') : true)) checked @endif>
	                                    Receive notifications on new applications by email
                                        @if($errors->has('response_email_notifications'))
                                            <span class="help-block">{{ $errors->first('response_email_notifications') }}</span>
                                        @endif
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="">Language of application:</label>
                                    <input value="0" name="only_in_english" type="checkbox" checked style="display: none;">
                                    <label class="label--checkbox">
                                        <input name="only_in_english" value="1" id="chexbox" type="checkbox" class="checkbox" @if(isset($vacancy) ? $vacancy->only_in_english : old('only_in_english')) checked @endif>
	                                    Applications must be submitted <b>English</b> only
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="">“How to apply” options:</label>
                                    <div class="checks">
                                        <div class="card">
	                                        <label class="radio"><input id="radio1" type="radio" name="request_type" value="resume" @if((isset($vacancy) && $vacancy->request_type == 'resume') || old('request_type') == 'resume' || !old('request_type')) checked @endif><span class="outer"><span class="inner"></span></span>To apply, candidates should send a resume</label>

	                                        <label class="radio"><input id="radio2" type="radio" name="request_type" value="form_from_file" @if((isset($vacancy) && $vacancy->request_type == 'form_from_file') || old('request_type') == 'form_from_file') checked @endif><span class="outer"><span class="inner"></span></span>To apply, candidates should fill out your application form. Please attach it here:</label>
	                                        <div style="padding-left: 34px;" class="form-group b-form-file_upload {{ $errors->has('form_from_file') ? 'has-error' : '' }}">
		                                        <label for="file"></label>
		                                        <div class="uploadFile" data-target="{{ route('ajax.uploadFile') }}" data-token="{{ csrf_token() }}">
			                                        <div>
				                                        <div class="thumbnail">
					                                        <div class="no-value" style="    padding-left: 10px;">
						                                        <span style="font-family: OpenSansRegular;">{{ isset($vacancy) && $vacancy->form_from_file ? $vacancy->form_from_file : (old('form_from_file') ? old('form_from_file') : 'No file') }}</span>
					                                        </div>
					                                        <div class="has-value hidden">
						                                        <a href="{{ route('home') }}" data-toggle="tooltip" title="Скачать"><i class="fa fa-fw fa-file-o"></i> <span></span></a>
					                                        </div>
				                                        </div>
			                                        </div>
			                                        <input name="form_from_file" class="imageValue" type="hidden" value="{{ isset($vacancy) && $vacancy->form_from_file ? $vacancy->form_from_file : old('form_from_file') }}">
			                                        <div class="errors"></div>
			                                        @if($errors->has('form_from_file'))
				                                        <span class="help-block">{{ $errors->first('form_from_file') }}</span>
			                                        @endif
			                                        <div>
				                                        <div style="position: absolute;
                                                                    right: 0;
                                                                    background: #2095f2;
                                                                    top: 0;
                                                                    height: 29px;
                                                                    border: 0;
                                                                    padding: 0 10px;
                                                                    line-height: 28px;" class="btn btn-primary imageBrowse"><i class="fa fa-upload"></i>Select File<input class="b-button-upload_video" type="file"></div>
				                                        <div class="btn btn-danger imageRemove"><i class="fa fa-times"></i> Delete</div>
			                                        </div>
		                                        </div>
	                                        </div>



	                                        <div class="form-group {{ $errors->has('link_online_form') ? 'has-error' : '' }}">
		                                        <label class="radio"><input id="radio3" type="radio" name="request_type" value="online_form" @if((isset($vacancy) && $vacancy->request_type == 'online_form') || old('request_type') == 'online_form') checked @endif><span class="outer"><span class="inner"></span></span>To apply, candidates should fill out your online application form. Please indicate link to online application form here:</label>
		                                        <label class="label--checkbox">
			                                        <input name="link_online_form" value="{{ isset($vacancy) ? $vacancy->link_online_form : old('link_online_form') }}" id="lastforms" class="form-control" type="text" placeholder="Enter link for the online form in format: http://yourdomain.kg/">
			                                        @if($errors->has('link_online_form'))
				                                        <span class="help-block">{{ $errors->first('link_online_form') }}</span>
			                                        @endif
		                                        </label>
	                                        </div>
                                        </div>
                                    </div>
                                </div>
	                            <div class="bottomline resumse">
		                            @if(isset($vacancy) && !$vacancy->draft)
			                            <input name="submit" value="Save" type="submit" id="blues">
		                            @else
			                            <h3>Placing vacancy on the website you agree with the <a target="_blank" href="{{ \App\Models\Page::whereId(5)->count() > 0 ? route('page', \App\Models\Page::whereId(5)->first()->slug) : '' }}">terms of publishing vacancies</a></h3>
			                            {{--@if(isset($resume))--}}
			                            {{--<input name="submit" value="Сохранить" type="submit" id="blues">--}}
			                            {{--@elseif(isset($resume) && $resume->draft)--}}
			                            <input name="preview" value="Preview" type="submit" id="white">
			                            <input name="submit" value="Send for publication" type="submit" id="blues">
			                            {{--@else--}}
			                            {{--<input name="preview" value="Предварительный просмотр" type="submit" id="white">--}}
			                            {{--<input name="submit" value="Отправить на публикацию" type="submit" id="blues">--}}
			                            {{--@endif--}}
			                            <p>Your vacancy will be published once it is checked by moderator</p>
		                            @endif
	                            </div>
                            </div> <!-- end big -->
                        </div> <!-- end contentform -->

                        <div class="col-md-3">
                            <p>Carefully look at the possible options to apply for the vacancy and select the option you needed.</p>
                            <p>If you want the candidate to apply with resume and other required documents, choose the first option.</p>
                            <p>If you want the candidate to apply with filled application form you required (and with required documents), choose the second option and upload your application form.</p>
                            <p>If you have own online application process, choose a third option and indicate a link to online application form.</p>
                        </div>
                    </div> <!-- end formcontainer -->
                </div> <!-- end bg -->
                {!! Form::close() !!}
            </div> <!-- end row   -->
        </div>
    </div> <!-- end rezumenew -->
@endsection