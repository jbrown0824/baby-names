<!doctype html>
<html>
<head>
	<title>Baby Names</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/app.css" rel="stylesheet">
</head>
<body>

<h2>Names</h2>
<table>
	<tr>
		<th>Rank</th>
		<th></th>
		<th>Name</th>
		<th>Nickname(s)</th>
		<th># Votes</th>
	</tr>
	@foreach ($names as $rank => $name)
		<tr>
			<td width="3%">{{$rank+1}}</td>
			<td width="20">
				@if ($name->rank_last_week == ($rank+1))
					-
				@elseif ($name->rank_last_week < ($rank+1))
					<img alt="{{$name->rank_last_week}}"
						 src="/images/{{($rank+1) - $name->rank_last_week > 2 ? 'downdown' : 'down'}}.png"/>
				@else
					<img alt="{{$name->rank_last_week}}"
						 src="/images/{{$name->rank_last_week - ($rank+1) > 2 ? 'upup' : 'up'}}.png"/>
				@endif
			</td>
			<td>{{$name->name}}</td>
			<td>{{$name->nickname}}</td>
			<td>{{$name->votes}}</td>
		</tr>
	@endforeach
</table>

<br>
<hr>
<br>

<div id="app">
	<graphs :weekly-data="{{ json_encode($weeklyVotes) }}" :names="{{ json_encode($names) }}"></graphs>
</div>

<br>
<hr>
<br>

<h2>Voters</h2>
<table>
	<tr>
		<th>Name</th>
		<th>Votes Available</th>
	</tr>
	@foreach ($voters as $voter)
		<tr>
			<td>{{$voter->name}}</td>
			<td>{{$voter->votes_available}}</td>
		</tr>
	@endforeach
</table>

<br>
<hr>
<br>

<form action="/add_votes">
	<h2>Vote for Name</h2>
	Who are you: @include('partials.voters')<br>
	Name to Add: <select name="name_id">
		@foreach ($names as $rank => $name)
			<option value="{{$name->id}}">{{$name->name}}</option>
		@endforeach
	</select><br>
	Number of Votes to Use: <input name="num_votes"><br>
	<input type="submit" value="Vote for Name">
</form>

<br>
<hr>
<br>

<form action="/add_name">
	<h2>Add Name</h2>
	Who are you: @include('partials.voters')<br>
	Name to Add: <input name="name"><br>
	Nickname(s): <input name="nickname"><br>
	Cost: 3 votes<br>
	<input type="submit" value="Add name">
</form>

<br>
<hr>
<br>

<h2>History</h2>
<table>
	<tr>
		<th>Who</th>
		<th>Action Taken</th>
		<th>For:</th>
		<th>Votes Used</th>
		<th>Date</th>
	</tr>
	@foreach ($votes as $vote)
		<tr>
			<td>{{$vote->voter->name}}</td>
			<td>{{$vote->note}}</td>
			<td>{{$vote->name->name}}</td>
			<td>{{$vote->num_votes_used}}</td>
			<td>{{date('F d, Y h:i:s a', strtotime($vote->created_at))}}</td>
		</tr>
	@endforeach
</table>

<script src="js/app.js"></script>

</body>
</html>
