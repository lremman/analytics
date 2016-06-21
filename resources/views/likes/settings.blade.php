<div class="well bs-component">
	<form class="form-horizontal" method="GET" action="{{ route('likes_ajax') }}">
		<fieldset>

			<legend>Налаштування</legend>

			<input type="hidden" name="page" value="1">

			<div class="form-group">
			<label for="select" class="col-lg-2 control-label">Друг</label>
			<div class="col-lg-10">
				<select class="form-control" id="select" name="owner">
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
						<label class="disabled">
							<input type="hidden" name="profile_photos" value="0" disabled>
							<input name="profile_photos" value="1" type="checkbox" checked disabled> Аналізувати фотографії профіля
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="hidden" name="wall_photos" value="0">
							<input name="wall_photos" value="1" type="checkbox"> Аналізувати фотографії на стіні
						</label>
					</div>

					<div class="checkbox">
						<label>
							<input type="hidden" name="posts" value="0">
							<input name="posts" value="1" type="checkbox"> Аналізувати записи на стіні
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="hidden" name="rating" value="1">
							<input name="rating" value="50" type="checkbox"> Враховувати час
						</label>
					</div>
				</div>
			</div>

			<div class="form-group">
                <label class="col-lg-2 control-label">Стать</label>
	            <div class="col-lg-10">
	            	<div class="radio">
		                <label>
			                <input type="radio" name="filters[sex]"  value="*"checked>
			                будь-яка
		                </label>
		            </div>
		            <div class="radio">
		                <label>
			                <input type="radio" name="filters[sex]"  value="2">
			                чоловіча
		                </label>
		            </div>
		            <div class="radio">
		                <label>
		                	<input type="radio" name="filters[sex]" value="1">
		                	жіноча
		                </label>
		            </div>
		        </div>
            </div>

            <div class="form-group">
                <label class="col-lg-2 control-label">Онлайн</label>
	            <div class="col-lg-10">
	            	<div class="radio">
		                <label>
			                <input type="radio" name="filters[status]"  value="*"checked>
			                не важливо
		                </label>
		            </div>
		            <div class="radio">
		                <label>
			                <input type="radio" name="filters[status]"  value="1">
			                так
		                </label>
		            </div>
		            <div class="radio">
		                <label>
		                	<input type="radio" name="filters[status]" value="0">
		                	ні
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