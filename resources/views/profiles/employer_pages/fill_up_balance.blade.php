@extends('layouts.app_no_banner')
@include('profiles.partials._employer_assets')
@section('content')
	<div class="room">
		<div class="container">
			<div class="row">
				@include('profiles.partials._company_block')
				<div class="col-md-9">
					<!-- Tab panes -->
					<div class="fill_up">
						<div role="tabpanel">
							<?php Request::get('lang') ? $lang = Request::get('lang') : $lang = 0 ?>
							<h2>Выбрать способ пополнения баланса</h2>
							<ul class="fill_up">
								<li @if($lang == 1) class="active" @endif>
									<div class="img-wrapper">
										<img src="/img/one1.png" alt="">
									</div>
									<a class="lang" name="lang"
									   data-target-page="{{ route('employers.profile.fill_up_balance', ['lang' => '1']) }}"></a>

									<p>Mobilnik.kg</p>
								</li>
								<li @if($lang == 2) class="active" @endif>
									<div class="img-wrapper">
										<img src="/img/two2.png" alt="">
									</div>
									<a class="lang" name="lang"
									   data-target-page="{{ route('employers.profile.fill_up_balance', ['lang' => '2']) }}"></a>

									<p>Безналичный платёж</p>
								</li>
								<li @if($lang == 3) class="active" @endif>
									<div class="img-wrapper">
										<img src="/img/tree3.png" alt="">
									</div>
									<a class="lang" name="lang"
									   data-target-page="{{ route('employers.profile.fill_up_balance', ['lang' => '3']) }}"></a>

									<p>Наличный расчёт</p>
								</li>
							</ul>
							<div class="clearfix"></div>
							@if($lang == 1)
							<h4>Ваш лицевой счет пополниться моментально после оплаты.</h4>
									<!-- Nav tabs -->
							<ul class="nav nav-tabs cash" role="tablist">
								<li role="presentation" class="active">
									<a href="#home" aria-controls="home" role="tab" data-toggle="tab">
										<div class="ones">
											<h5>Через сайт мобильник</h5>
										</div>
									</a>
								</li>
								<li role="presentation">
									<a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">
										<div class="ones">
											<h5>Через терминал</h5>
										</div>
									</a>
								</li>
							</ul>

							<!-- Tab panes -->
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane active" id="home">
									<div class="ones">
										<p><span>1</span>Зайти на сайт<a href="https://www.mobilnik.kg" target="_blank">mobilnik.kg</a></p>

										<p><span>2</span>Выбрать в боковом меню раздел кошелек</p>

										<p><span>3</span>Найти раздел обьявления</p>
										<img src="/img/jpg/mobilnik/mobilnik_1.jpg" alt="/img/jpg/mobilnik/mobilnik_1.jpg">

										<p><span>4</span>Выбрать employment.kg</p>
										<img src="/img/jpg/mobilnik/mobilnik_2.jpg" alt="/img/jpg/mobilnik/mobilnik_2.jpg">

										<p><span>5</span>Ввести ваш лицевой счет(реквизит) и оплатить</p>
										<img src="/img/jpg/mobilnik/mobilnik_3.jpg" alt="/img/jpg/mobilnik/mobilnik_3.jpg">
										<img src="/img/jpg/mobilnik/mobilnik_4.jpg" alt="/img/jpg/mobilnik/mobilnik_4.jpg">
									</div>
								</div>
								<div role="tabpanel" class="tab-pane" id="profile">
									<div class="ones">
										<p><span>1</span>Найти ближащий терминал<a href="https://www.mobilnik.kg/#/map" target="_blank">Показать на карте</a></p>

										<p><span>2</span>Выбрать раздел "Объявления"</p>
										<img src="/img/jpg/terminal/1.jpg" alt="/img/jpg/terminal/1.jpg">
										<img src="/img/jpg/terminal/2.jpg" alt="/img/jpg/terminal/2.jpg">

										<p><span>3</span>Найти employment.kg</p>
										<img src="/img/jpg/terminal/3.jpg" alt="/img/jpg/terminal/3.jpg">
										<img src="/img/jpg/terminal/4.jpg" alt="/img/jpg/terminal/4.jpg">

										<p><span>4</span>Ввести оплату на свой лицевой счет</p>
										<img src="/img/jpg/terminal/5.jpg" alt="/img/jpg/terminal/5.jpg">
									</div>
								</div>
							</div>

							@elseif($lang == 2 || $lang == 3)
							@if($lang == 2)
								<h4>Ваш лицевой счет пополниться в течение 1 рабочего дня после оплаты.</h4>
							@else
								<h4>Ваш лицевой счет пополниться моментально после оплаты.</h4>
							@endif
								<div class="tows">
									<h5>Заполните реквизиты вашей организации:</h5>
									<p>Мы выставим Вам счет на оплату указывая ваши реквизиты и незамедлительно отправим
										отсканированный вариант счета на Ваш электронный адрес</p>

									{!! Form::open(['route' => ['payments.store', 'type' => $lang], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
										<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
											<label for="" class="col-sm-3 control-label">Название юридич. лица</label>
											<div class="col-sm-9">
												<input name="name" value="{{ isset($payment) ? $payment->name : old('name') }}" class="form-control" type="text" placeholder="Введите название">
											</div>
											@if($errors->has('name'))
												<span class="help-block">{{ $errors->first('name') }}</span>
											@endif
										</div>
										<div class="form-group {{ $errors->has('post') ? 'has-error' : '' }}">
											<label for="" class="col-sm-3 control-label">Почтовый адрес</label>
											<div class="col-sm-9">
												<input name="post" value="{{ isset($payment) ? $payment->post : old('post') }}" class="form-control" type="text" placeholder="Ваша почта">
											</div>
											@if($errors->has('post'))
												<span class="help-block">{{ $errors->first('post') }}</span>
											@endif
										</div>
										<div class="form-group {{ $errors->has('INN') ? 'has-error' : '' }}">
											<label for="" class="col-sm-3 control-label">ИНН</label>
											<div class="col-sm-9">
												<input name="INN" value="{{ isset($payment) ? $payment->INN : old('INN') }}" class="form-control" type="text" placeholder="Введите ИНН">
											</div>
											@if($errors->has('INN'))
												<span class="help-block">{{ $errors->first('INN') }}</span>
											@endif
										</div>
										<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
											<label for="" class="col-sm-3 control-label">E-mail (для отправки счетов)</label>
											<div class="col-sm-9">
												<input name="email" value="{{ isset($payment) ? $payment->email : old('email') }}" class="form-control" type="text" placeholder="Введите E-mail">
											</div>
											@if($errors->has('email'))
												<span class="help-block">{{ $errors->first('email') }}</span>
											@endif
										</div>
										<div class="form-group {{ $errors->has('sum') ? 'has-error' : '' }}">
											<label for="" class="col-sm-3 control-label">Сумма</label>
											<div class="col-sm-9">
												<input name="sum" value="{{ isset($payment) ? $payment->sum : old('sum') }}" class="form-control" type="text" placeholder="Введите сумму">
											</div>
											@if($errors->has('sum'))
												<span class="help-block">{{ $errors->first('sum') }}</span>
											@endif
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-sm-10">
												<button type="submit" class="btn btn-default">Отправить</button>
											</div>
										</div>
									{!! Form::close() !!}
								</div>
							{{--@elseif($lang == 3)--}}
								{{--<div class="ones">--}}
									{{--<h5>Как пополнить через наличку</h5>--}}

									{{--<p><span>1</span>Зайти на сайт<a href="">а вот фиг его как?</a></p>--}}

									{{--<p><span>2</span>Выбрать в боковом меню раздел кошелек</p>--}}

									{{--<p><span>3</span>Найти раздел обьявления</p>--}}

									{{--<p><span>4</span>Выбрать employment.kg</p>--}}

									{{--<p><span>5</span>Ввести ваш лицевой счет(реквизит) и оплатить</p>--}}
								{{--</div>--}}
							@endif
						</div>
						<!-- dsfsdf -->
					</div>
				</div>
			</div>
		</div>
	</div><!--  end room -->

@stop