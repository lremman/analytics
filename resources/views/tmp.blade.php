<table>
<?php $count = 0;?>
@foreach($friends_groups as $group)
	<?php $count++;?>
	<br><tr><strong><<*^*>></strong></tr><br>
	@foreach($group as $key => $friend)
	<tr>{{$key+1}}. {{$friend}}</tr><br>
	@endforeach
@endforeach

</table>
