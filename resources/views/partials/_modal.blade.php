<section class="b-modals">
	<div class="container">
		<div class="row">
			<div class="modal fade" id="ajaxModal"></div>
		</div>
	</div>
</section>

@if(Request::get('showModal'))
	<script src="{{ asset('libs/js/jquery.min.js') }}"></script>
	<script>$(function () {getAjaxModal($(), {action: "<?= Request::get('action') ?>", type: "<?= Request::get('type') ?>", parameters: "<?= Request::get('parameters') ?>"});})</script>
@endif