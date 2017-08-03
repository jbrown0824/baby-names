<select name="voter_id">
    <option value="">Select</option>
    @foreach ($voters as $voter)
    <option value="{{$voter->id}}">{{$voter->name}} (Num Votes: {{$voter->votes_available}})</option>
    @endforeach
</select>