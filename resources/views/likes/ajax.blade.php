<table class="table table-striped table-hover" data-legend="{{ $legend }}">

	<input type="hidden" name="pages_all" value="{{ $pages_all }}">

	<thead>
		<tr>
			<th></th>
			<th>Рейтинг</th>
			<th>Им'я і прізвище</th>
			<th>Оцінено записів</th>
			<th width="1"></th>
		</tr>
	</thead>
	<tbody>

		<?php $counter = 0; ?>

		@foreach($likes as $user_id => $data)

			<?php $user = $users->get($user_id);?>
			<?php $counter++; ?>

			@if($user)

				<tr @if($counter < 4) @endif>
					<td><img src="{{ $user['photo_50'] }}"></td>
					<td>{{ $counter }}</td>
					<td>{{ $user['first_name'] }} {{ $user['last_name'] }}</td>
					<td>{{ $data['count'] }} із {{ $all_posts }}</td>
					<td><a class="btn btn-primary" href="https://vk.com/id{{ $user['uid'] }}" target="_blank"><i class="fa fa-vk"></a></td>
				</tr>

			@endif

		@endforeach
	</tbody>
</table> 
