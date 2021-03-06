<?php

namespace App\Http\Controllers;

use App\Names;
use App\Voters;
use App\Votes;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index() {
        $this->checkWeekEnded();

        $voters = Voters::get();
        $names = Names::orderBy('votes', 'desc')->get();
        $votes = Votes::with('voter', 'name')->orderBy('id', 'desc')->get();

        $weeklyVotes = [];

        foreach ($votes as $vote) {
        	$week = Carbon::parse($vote->created_at)->format('W/Y');
        	$weeklyVotes[ $week ] = $weeklyVotes[ $week ] ?? [];

			$weeklyVotes[ $week ][] = $vote;
		}

		DB::statement('SET sql_mode="STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"');
        $weeklyVotes = collect(DB::select('SELECT SUM(num_votes_used) as votes, names.name, CONCAT(WEEK(votes.created_at), "/", YEAR(votes.created_at)) as weekname FROM votes INNER JOIN names on votes.name_id = names.id GROUP BY weekname, name_id ORDER BY votes.created_at'))->groupBy('weekname');

        return view('index', compact('voters', 'names', 'votes', 'weeklyVotes'));
    }

    public function add_votes(Request $request) {
        $input = $request->all();
        $voter = Voters::find($input['voter_id']);
        $name = Names::find($input['name_id']);
        $absNumVotes = abs($input['num_votes']);
        $numVotes = (int) $absNumVotes > $voter->votes_available ? $voter->votes_available : $absNumVotes;

        if (!$name) {
            dd('could not find name to vote for');
        }

        if ($voter->votes_available == 0) {
            dd('not enough votes', $voter->toArray());
        }

        //Add Vote
        $vote = new Votes([
            'name_id' => $name->id,
            'voter_id' => $voter->id,
            'num_votes_used' => $numVotes,
            'note' => 'Voted'
        ]);

        $vote->save();

        //Update Name
        $name->votes = $name->votes + $numVotes;
        $name->save();

        //Update Voter Votes Remaining
        $voter->votes_available = $voter->votes_available - $numVotes;
        $voter->save();

        return redirect('/');
    }

    public function add_name(Request $request) {
        $input = $request->all();
        $voter = Voters::find($input['voter_id']);
        $names = Names::get();
        $numVotes = 3;

        if ($voter->votes_available < $numVotes) {
            dd('not enough votes', $voter->toArray(), $numVotes);
        }

        //Create Name
        $name = new Names([
            'name' => $input['name'],
            'nickname' => $input['nickname'],
            'votes' => 3,
            'rank_last_week' => count($names),
            'last_rank_update' => date("Y-m-d 00:00:00")
        ]);
        $name->save();

        //Add Vote
        $vote = new Votes([
            'name_id' => $name->id,
            'voter_id' => $voter->id,
            'num_votes_used' => $numVotes,
            'note' => 'Added Name'
        ]);

        $vote->save();

        //Update Voter Votes Remaining
        $voter->votes_available = $voter->votes_available - $numVotes;
        $voter->save();

        return redirect('/');
    }

    protected function checkWeekEnded() {
        $voters = Voters::get();
        $updateRequired = false;

        foreach ($voters as $voter) {
            $thisWeek = strtotime('this sunday');
            if (date('N') == 7) {
                $thisWeek = strtotime('today');
            }

            if (strtotime($voter->refills_on) <= time()) {
                $voter->refills_on = date("Y-m-d 00:00:00", strtotime('next sunday'));
                $voter->votes_available = 10;
                $voter->save();
                $updateRequired = true;
            }
        }

        if ($updateRequired) {
            // Update ranks of names for last week
            $names = Names::orderBy('votes', 'desc')->get();
            foreach ($names as $rank => $name) {
                $name->rank_last_week = $rank+1;
                $name->save();
            }
        }
    }
}
