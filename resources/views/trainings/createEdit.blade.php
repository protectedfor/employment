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
	<script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
	<script>
		var editor = CKEDITOR.replace( 'description',{
			customConfig : 'configTraining.js',
			filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
			filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()}}',
		} );
	</script>
@endsection
@section('content')
    <div class="vacansiasmall create-edit-page">
        <div class="container">
            <div class="row">

	            <div class="backarrow">
	                <img src="/img/png/arrwoback.png" alt="">
                    <a href="{{ route('trainings.index') }}">Тренинги</a>
                </div> <!-- end backarrow -->

                <h1>{{ isset($training) ? 'Редактирование тренинга "' . $training->title .'"' : 'Новый тренинг' }}</h1>
                {!! Form::open(['route' => isset($training) ? ['trainings.update', $training->id] : 'trainings.store', 'method' => 'POST']) !!}
                <div class="coverwhite">

                    <div class="formcontainer">
                        <div class="contentform col-md-9">
                            @if(count($errors) > 0)
                                <div class="alert alert-danger" role="alert">
                                    <ul>
                                        <li>Ошибки при заполнении формы!</li>
                                    </ul>
                                </div>
                            @endif

                            <?php Request::get('loc') ? $loc = Request::get('loc') : (isset($training) && $training->location ? $loc = $training->location : (old('location') ? $loc = old('location') : $loc = '0')) ?>
                            <div class="about">
	                            <h2>Курсы будут проводиться:</h2>
	                            <div class="checks">
		                            <div class="card">
			                            {{--@if(Request::url() == route('trainings.create'))--}}
				                            {{--<label class="radio">--}}
					                            {{--<input class="lang" id="radio1" type="radio" name="location" value="0" data-target-page="{{ route('trainings.create', ['loc' => '0']) }}" {{ $loc == '0' ? 'checked' : '' }}><span class="outer"><span class="inner"></span></span>--}}
					                            {{--В Кыргызстане--}}
				                            {{--</label>--}}
				                            {{--<label class="radio">--}}
					                            {{--<input class="lang" id="radio2" type="radio" name="location" value="1" data-target-page="{{ route('trainings.create', ['loc' => '1']) }}" {{ $loc == '1' ? 'checked' : '' }}><span class="outer"><span class="inner"></span></span>--}}
					                            {{--За рубежом--}}
				                            {{--</label>--}}
			                            {{--@else--}}
				                            {{--<label class="radio">--}}
					                            {{--<input class="lang" id="radio1" type="radio" name="location" value="0" data-target-page="{{ route('trainings.edit', [$training->id, 'loc' => '0']) }}" {{ $loc == '0' ? 'checked' : '' }}><span class="outer"><span class="inner"></span></span>--}}
					                            {{--В Кыргызстане--}}
				                            {{--</label>--}}
				                            {{--<label class="radio">--}}
					                            {{--<input class="lang" id="radio2" type="radio" name="location" value="1" data-target-page="{{ route('trainings.edit', [$training->id, 'loc' => '1']) }}" {{ $loc == '1' ? 'checked' : '' }}><span class="outer"><span class="inner"></span></span>--}}
					                            {{--За рубежом--}}
				                            {{--</label>--}}
			                            {{--@endif--}}
			                            <label class="radio">
				                            <input class="loc" id="radio1" type="radio" name="location" value="0" data-target-page="loc=0" @if((isset($training) && $training->location == 0) || old('location') == 0) checked @endif><span class="outer"><span class="inner"></span></span>
				                            В Кыргызстане
			                            </label>
			                            <label class="radio">
				                            <input class="loc" id="radio2" type="radio" name="location" value="1" data-target-page="loc=1" @if((isset($training) && $training->location == 1) || old('location') == 1) checked @endif><span class="outer"><span class="inner"></span></span>
				                            За рубежом
			                            </label>
		                            </div>
	                            </div>
                            </div> <!-- end about -->

                            <div class="big">

	                            <div class="titlephoto">
		                            <p>Изображение</p>
	                            </div>

	                            <div class="photodownload imageUpload" data-target="{{ route('ajax.uploadImage') }}" data-token="{{ csrf_token() }}">
		                            <section>
			                            <div class="img-wrapper thumbnail">
				                            @if (isset($training) && $training->photo != '')
					                            <img class="has-value" src="{{ route('imagecache', ['95_130', $training->photo])}}" id="img" alt="">
					                            <img class="no-value hidden" src="https://placehold.it/95x130" alt="">
				                            @elseif (!isset($training) && ($company->logo != '' || old('photo')))
					                            <img class="has-value" src="{{ old('photo') ? route('imagecache', ['95_130', old('photo')]) : route('imagecache', ['95_130', $company->logo]) }}" id="img" alt="">
					                            <img class="no-value hidden" src="https://placehold.it/95x130" alt="">
				                            @else
					                            <img class="has-value hidden" src="" id="img" alt="">
					                            <img class="no-value" src="https://placehold.it/95x130" alt="">
				                            @endif
			                            </div>
			                            <ul class="traineng-buttons">
				                            <li>
					                            <a  id="addphoto" class="btn imageBrowse">Загрузить</a>
				                            </li>
				                            <li>
					                            <a id="removephoto" class="btn imageRemove">Убрать</a>
				                            </li>
			                            </ul>
		                            </section>
		                            <div class="errors">
			                            {{--<p class="help-block">Invalid image type</p>--}}
		                            </div>
		                            <input name="photo" value="{{ isset($training) ? $training->photo : (old('photo') ? old('photo') : $company->logo) }}" type="hidden" class="imageValue" id="photo" accept="image/*;capture=camera" capture="camera"/>
	                            </div> <!-- end photodownload -->

	                            <div class="about">
		                            <h2>Общая информация</h2>
	                            </div> <!-- end about -->

	                            {{--<div class="checks">--}}
		                            {{--<div class="card form-group">--}}
			                            {{--<label for="">Курсы будут проводиться:</label>--}}
			                            {{--<label class="radio">--}}
				                            {{--<input id="radio1" type="radio" name="location" value="0" {{ !isset($training) || isset($training) && !$training->location ? 'checked' : '' }}><span class="outer"><span class="inner"></span></span>--}}
				                            {{--В Кыргызстане--}}
			                            {{--</label>--}}
			                            {{--<label class="radio">--}}
				                            {{--<input id="radio2" type="radio" name="location" value="1" {{ isset($training) && $training->location ? 'checked' : '' }}><span class="outer"><span class="inner"></span></span>--}}
				                            {{--За рубежом--}}
			                            {{--</label>--}}
		                            {{--</div>--}}
	                            {{--</div>--}}

	                            <div class="clearfix"></div>

                                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                                    <label for="">Название:</label>
                                    <input name="title" value="{{ isset($training) ? $training->title : old('title') }}" class="form-control" type="text" placeholder="введите названия Вашего курса или тренинга">
                                    @if($errors->has('title'))
                                        <span class="help-block">{{ $errors->first('title') }}</span>
                                    @endif
                                </div>
	                            <div class="form-group {{ $errors->has('coordinator') ? 'has-error' : '' }}">
		                            <label for="">Название компании:</label>
		                            <input name="coordinator" value="{{ isset($training) ? $training->coordinator : old('coordinator') }}" class="form-control" type="text" placeholder="Укажите названия компании или учебного центра">
		                            @if($errors->has('coordinator'))
			                            <span class="help-block">{{ $errors->first('coordinator') }}</span>
		                            @endif
	                            </div>
                                <div class="form-group {{ $errors->has('category_id') ? 'has-error' : '' }}">
                                    <label for="">Категория:</label>
	                                @include('partials._training_categories')
	                                @if($errors->has('category_id'))
		                                <span class="help-block">{{ $errors->first('category_id') }}</span>
	                                @endif
                                </div>
	                            <div class="form-group {{ $errors->has('price') ? 'has-error' : '' }}">
		                            <label for="">Стоимость:</label>
		                            <input name="price" value="{{ isset($training) ? $training->price : old('price') }}" class="form-control" type="text" placeholder="укажите стоимость Вашего курса или тренинга">
		                            @if($errors->has('price'))
			                            <span class="help-block">{{ $errors->first('price') }}</span>
		                            @endif
	                            </div>
	                            <div class="form-group {{ $errors->has('start_date') ? 'has-error' : '' }}">
		                            <label for="">Дата начала:</label>
		                            <input name="start_date" value="{{ isset($training) ? $training->start_date : old('start_date') }}" class="form-control" type="text" placeholder="укажите дату начала курса. Например, «18 октября 2016 года», либо «по мере набора»">
		                            @if($errors->has('start_date'))
			                            <span class="help-block">{{ $errors->first('start_date') }}</span>
		                            @endif
	                            </div>
	                            <div class="form-group {{ $errors->has('duration') ? 'has-error' : '' }}">
		                            <label for="">Продолжительность:</label>
		                            <input name="duration" value="{{ isset($training) ? $training->duration : old('duration') }}" class="form-control" type="text" placeholder="укажите продолжительность курса/тренинга. Например, «3 недели»">
		                            @if($errors->has('duration'))
			                            <span class="help-block">{{ $errors->first('duration') }}</span>
		                            @endif
	                            </div>
	                            <div class="form-group {{ $errors->has('schedule') ? 'has-error' : '' }}">
		                            <label for="">Время проведения занятий:</label>
		                            <input name="schedule" value="{{ isset($training) ? $training->schedule : old('schedule') }}" class="form-control" type="text" placeholder="укажите Время проведения занятий. Например, «с 18.00 по 20.00»">
		                            @if($errors->has('schedule'))
			                            <span class="help-block">{{ $errors->first('schedule') }}</span>
		                            @endif
	                            </div>
	                            <div class="form-group {{ $errors->has('place') ? 'has-error' : '' }}">
		                            <label for="">Место проведения занятий:</label>
		                            <input name="place" value="{{ isset($training) ? $training->place : old('place') }}" class="form-control" type="text" placeholder="укажите город, в котором проводится курс/тренинг">
		                            @if($errors->has('place'))
			                            <span class="help-block">{{ $errors->first('place') }}</span>
		                            @endif
	                            </div>
	                            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
		                            <label for="">Описание курса/тренинга:</label>
		                            <textarea name="description" class="form-control ckeditor" id="description" placeholder="">{{ isset($training) ? $training->description : old('description') }}</textarea>
		                            @if($errors->has('description'))
			                            <span class="help-block">{{ $errors->first('description') }}</span>
		                            @endif
	                            </div>
	                            <div class="form-group {{ $errors->has('expires_at') ? 'has-error' : '' }}">
		                            
		                            <div class='input-group ' >
		                            <label for="">Крайний срок подачи заявок:</label>
			                            <input class="date datepicker" id='datetimepicker5' name="expires_at" value="{{ isset($training) && $training->expires_at ? $training->expires_at->format('d.m.Y') : old('expires_at') }}" id="date" class="form-control" type="text">
			                            <span class="input-group-addon" style="	right: -2px;top: 0px;width: 40px;height: 32px;"><i class="glyphicon glyphicon-calendar"></i></span>
			                            @if($errors->has('expires_at'))
				                            <span class="help-block">{{ $errors->first('expires_at') }}</span>
			                            @endif
	                                </div>
	                            </div>
                            </div> <!-- end big -->
                        </div> <!-- end contentform -->

	                    <div class="col-md-3">
		                    <h2>Чтобы привлечь внимание Вашей целевой аудитории, советуем при заполнении форм следовать нашим подсказкам.</h2>
		                    <p>Выберите, где будет проходить ваш курс/тренинг: в Кыргызстане или за рубежом.</p>
		                    <p>Картинка как нельзя лучше даст представление о Вашем курсе/тренинги и сделает информацию более запоминаемой. Разместите фотографию Вашего учебного центра, аудитории, процесса обучения и т.д. Либо Вы можете разместить изображение близкое по тематике Вашему курсу/тренингу.</p>
		                    <p>Чтобы ваши потенциальные слушатели получили наиболее полное представление о Вашем курсе/тренинге,  позаботьтесь о детальном заполнении каждого пункта.</p>
		                    <p>Мы постарались включить все возможные категории, однако, если Вы не нашли свою категорию, сообщите нам по электронному адресу: <i>info@employment.kg</i>, и мы сразу же включим ее.</p>
		                    <p>Почему потенциальные слушатели должны пройти именно Ваш курс/тренинг? Расскажите об этом в поле «Описание», где Вы можете указать уникальные черты и преимущества Вашей обучающей программы, дать информацию о преподавателях, дальнейших перспективах для тех, кто пройдет Ваш курс/тренинг.</p>
		                    <p>В разделе «Контакты» укажите способы, по которым с Вами можно связаться. Примите во внимание, что некоторые Пользователи предпочитают связываться по телефону, тогда как некоторые оставят заявку на нашем сайте, которую Вы получите на свой электронный адрес, указанный в данном разделе.</p>
	                    </div>
                    </div> <!-- end formcontainer -->

	                <!-- второе поле заполнение -->
	                <div class="formcontainer">
		                <div class="contentform col-md-9">

                            <div class="about">
	                            <h2>Контакты</h2>
                            </div> <!-- end about -->
			                <div class="big">
	                            <div class="form-group {{ $errors->has('contacter') ? 'has-error' : '' }}">
		                            <label for="">Контактное лицо:</label>
		                            <input name="contacter" value="{{ old('contacter') ? old('contacter') : (isset($training) ? $training->contacter : (isset($company) ? $company->fio : '')) }}" class="form-control" type="text" placeholder="Вы можете указать контактное лицо">
		                            @if($errors->has('contacter'))
			                            <span class="help-block">{{ $errors->first('contacter') }}</span>
		                            @endif
	                            </div>
	                            <div class="form-group {{ $errors->has('coach') ? 'has-error' : '' }}">
		                            <label for="">Тренер:</label>
		                            <input name="coach" value="{{ old('coach') ? old('coach') : (isset($training) ? $training->coach : '') }}" class="form-control" type="text" placeholder="Вы можете указать тренера">
		                            @if($errors->has('coach'))
			                            <span class="help-block">{{ $errors->first('coach') }}</span>
		                            @endif
	                            </div>
	                            <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
		                            <label for="">Телефон:</label>
		                            <input name="phone" value="{{ old('phone') ? old('phone') : (isset($training) ? $training->phone : (isset($company->phone) ? $company->phone : '')) }}" class="form-control" type="text" placeholder="Вы можете указать контактный телефон">
		                            @if($errors->has('phone'))
			                            <span class="help-block">{{ $errors->first('phone') }}</span>
		                            @endif
	                            </div>
	                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
		                            <label for="">Email:</label>
		                            <input name="email" value="{{ old('email') ? old('email') : (isset($training) ? $training->email : (isset($company->email) ? $company->email : '')) }}" class="form-control" type="text" placeholder="укажите электронный адрес, на который Вы хотите получать заявки">
		                            @if($errors->has('email'))
			                            <span class="help-block">{{ $errors->first('email') }}</span>
		                            @endif
	                            </div>
	                            <div class="form-group {{ $errors->has('site') ? 'has-error' : '' }}">
		                            <label for="">Сайт:</label>
		                            <input name="site" value="{{ old('site') ? old('site') : (isset($training) ? $training->site : (isset($company) ? $company->site : '')) }}" class="form-control" type="text" placeholder="Укажите сайт">
		                            @if($errors->has('site'))
			                            <span class="help-block">{{ $errors->first('site') }}</span>
		                            @endif
	                            </div>
	                            <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
		                            <label for="">Адрес:</label>
		                            <input name="address" value="{{ old('address') ? old('address') : (isset($training) ? $training->address : (isset($company) ? $company->address : '')) }}" class="form-control" type="text" placeholder="Укажите адрес">
		                            @if($errors->has('address'))
			                            <span class="help-block">{{ $errors->first('address') }}</span>
		                            @endif
	                            </div>
	                            <div class="form-group">
		                            <div id="ToToggle" class="trenneng-maps">
			                            <?php isset($training) ? $instance = $training : '' ?>
			                            @include('admin.contacts.map')
			                            <input name="google_map_code" value="{{ old('google_map_code') ? old('google_map_code') : (isset($training) ? $training->google_map_code : '') }}" type="hidden" class="form-control">
		                            </div>
	                            </div>
                            </div> <!-- end big -->
                        </div> <!-- end contentform -->

                        <div class="col-md-3">
                            <p>В разделе «Контакты» Вы можете указать нужные каналы связи, по которым с Вами будут связываться заинтересованные пользователи. Имейте ввиду, что некоторые Пользователи перезванивают на указанные телефоны, а некоторые оставляют заявку, которые Вы будете получать на электронный адрес, указанный в этом разделе. </p>
                        </div>

                    </div> <!-- end formcontainer -->

                    <!-- третье поле  -->
                    <div class="formcontainer">
                        <div class="contentform col-md-9">
                            <div class="big">
	                            @if(!isset($training))
		                            <div class="bottomline text-center">
	                                    <h3>Публикуя курс/тренинг, Вы соглашаетесь с <a target="_blank" href="{{ \App\Models\Page::whereId(12)->count() > 0 ? route('page', \App\Models\Page::whereId(12)->first()->slug) : '' }}">условиями размещению курсов и тренингов</a></h3>
		                                <input name="submit" value="РАЗМЕСТИТЬ НА САЙТЕ" type="submit" id="blues">
	                                    {{--<p>Тренинг будет опубликован на сайте после модерации</p>--}}
	                                </div>
	                            @else
		                            <div class="bottomline text-center b-no_background">
		                                <input name="submit" value="Сохранить" type="submit" id="blues">
		                            </div>
	                            @endif
                            </div> <!-- end big -->
                        </div> <!-- end contentform -->

                        {{--<div class="col-md-3">--}}
                            {{--<p>Первый визуальный контакт играет очень важную роль, поэтому резюме с фотографией имеет большое влияние на заинтересованность работодателя.--}}
                            {{--</p>--}}
                            {{--<p>Указывайте как можно больше точной информации. Открытость располагает к желанию узнать о кандидате больше.--}}
                            {{--</p>--}}
                        {{--</div>--}}

                    </div> <!-- end formcontainer -->

                </div> <!-- end bg -->
                {!! Form::close() !!}
            </div> <!-- end row   -->
        </div>
    </div> <!-- end rezumenew -->
@endsection