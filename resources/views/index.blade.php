<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        text-align: left;
        padding: 8px;
    }

    tr:nth-child(even){background-color: #f2f2f2}

    th {
        background-color: #4CAF50;
        color: white;
    }

    input[type=text], select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type=submit] {
        width: 100%;
        background-color: #4CAF50;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type=submit]:hover {
        background-color: #45a049;
    }

    div {
        border-radius: 5px;
        background-color: #f2f2f2;
        padding: 20px;
    }
</style>

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
                    <img alt="{{$name->rank_last_week}}" src="/images/{{($rank+1) - $name->rank_last_week > 2 ? 'downdown' : 'down'}}.png" />
                @else
                    <img alt="{{$name->rank_last_week}}" src="/images/{{$name->rank_last_week - ($rank+1) > 2 ? 'upup' : 'up'}}.png" />
                @endif
            </td>
            <td>{{$name->name}}</td>
            <td>{{$name->nickname}}</td>
            <td>{{$name->votes}}</td>
        </tr>
    @endforeach
</table>

<br><hr><br>

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

<br><hr><br>

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

<br><hr><br>

<form action="/add_name">
    <h2>Add Name</h2>
    Who are you: @include('partials.voters')<br>
    Name to Add: <input name="name"><br>
    Nickname(s): <input name="nickname"><br>
    Cost: 3 votes<br>
    <input type="submit" value="Add name">
</form>

<br><hr><br>

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