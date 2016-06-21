@foreach($genres as $user_genre)
	
	<?php $user = $users->get($user_genre[0]);?>

	@if($user)

		<div class="panel panel-success">
			<div class="panel-heading">
			  <h3 class="panel-title">{{ $user['first_name'] }} {{ $user['last_name'] }}</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						<img src="{{ $user['photo_50'] }}">
					</div>
					<div class="col-md-6">
					</div>
				</div>

				<table class="table table-striped table-hover ">
					<thead>
						<tr>
							<th>Жанр</th>
							<th>Рейтинг</th>
						</tr>
					</thead>
					<tbody>
					<?php $counter = 0; ?>
						@foreach($user_genre[1] as $genre => $count)
							@if($counter < 10)
								<tr>
									<td>{{$genre}}</td>
									<td>{{$count}}</td>
								</tr>
							@endif
							<?php $counter++; ?>
						@endforeach
					</tbody>
				</table>

			</div>
		</div>

	@endif

@endforeach