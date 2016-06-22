<div class="well bs-component">
	<form class="form-horizontal" method="GET" action="{{ route('audio_ajax') }}">
		<fieldset>

			<legend>Налаштування</legend>

			<input type="hidden" name="page" value="1">

			<div class="form-group">
				<label for="select" class="col-lg-2 control-label">Друг</label>
				<div class="col-lg-10">
					<select class="form-control" id="select" name="owner[]">
						<option value="{{ \Session::get('vk_user_id') }}"> Моя сторінка </option>
					@foreach($friends as $friend)
						<option value="{{ $friend['uid'] }}">{{ $friend['first_name'] }} {{ $friend['last_name'] }}</option>
					 @endforeach
					</select>
					<br>
				</div>
			</div>
			
			<div class="form-group">
				<label for="textArea" class="col-lg-2 control-label">Додатково</label>
				<div class="col-lg-10">
					<div class="checkbox">
						<label>
							<input type="hidden" name="is_wall" value="0">
							<input name="is_wall" value="1" type="checkbox"> Аналізувати записи на стіні
						</label>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="col-lg-10 col-lg-offset-2">
					<button type="reset" class="btn btn-default">Очистити налаштування</button>
					<button type="submit" id="build-rating" class="btn btn-primary">Побудувати рейтинг</button>
				</div>
			</div>

		</fieldset>
	</form>
</div>