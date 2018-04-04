@extends('layouts.app_no_banner')
@include('profiles.partials._employer_assets')
@section('content')
    <div class="room">
        <div class="container">
            <div class="row">
                @include('profiles.partials._company_block')
                <div class="col-md-9">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="save">
                            <div class="title">
                                <h2>Сохраненные резюме</h2>
                            </div>
                            {{--<form action="">--}}
                            {{--<div class="form-group">--}}
                            {{--<label for="">Ищите по ключевому слову</label>--}}
                            {{--<input class="form-control" type="text" placeholder="Ключевое слово">--}}
                            {{--</div>--}}
                            {{--<div class="form-group">--}}
                            {{--<label for="">Откликнувшиеся на вакансию</label>--}}
                            {{--<select class="form-control">--}}
                            {{--<option>1</option>--}}
                            {{--<option>2</option>--}}
                            {{--</select>--}}
                            {{--</div>--}}
                            {{--<div class="form-group">--}}
                            {{--<label for="">Искать по категории</label>--}}
                            {{--<select class="form-control">--}}
                            {{--<option>1</option>--}}
                            {{--<option>2</option>--}}
                            {{--</select>--}}
                            {{--</div>--}}
                            {{--<div class="form-group" id="years">--}}
                            {{--<label for="">Искать по возрасту:</label>--}}
                            {{--<select class="form-control">--}}
                            {{--<option>От 18</option>--}}
                            {{--<option>2</option>--}}
                            {{--</select>--}}
                            {{--<select class="form-control">--}}
                            {{--<option>До 90</option>--}}
                            {{--<option>2</option>--}}
                            {{--</select>--}}
                            {{--</div>--}}
                            {{--</form>--}}

                            {{--<h3>Национальный консультант — руководитель руппы <span>(3)</span></h3>--}}

                            <ul>
                                @foreach($savedResumes as $resume)
		                            @include('partials._resumesBlock')
                                @endforeach
                            </ul>
                        </div> <!-- asdasd -->
                    </div>
                </div>
            </div>
        </div>
    </div><!--  end room -->
@stop