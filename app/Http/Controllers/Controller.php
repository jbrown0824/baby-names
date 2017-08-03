<?php

namespace App\Http\Controllers;

use App\Names;
use App\Voters;
use App\Votes;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index() {
        $this->checkWeekEnded();

        $voters = Voters::get();
        $names = Names::orderBy('votes', 'desc')->get();
        $votes = Votes::with('voter')->orderBy('id', 'desc')->get();

        return view('index', compact('voters', 'names', 'votes'));
    }

    public function add_votes(Request $request) {
        $input = $request->all();
        $voter = Voters::find($input['voter_id']);
        $name = Names::find($input['name_id']);
        $numVotes = (int) $input['num_votes'] > $voter->votes_available ? $voter->votes_available : $input['num_votes'];

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
                dd('gonna update');
                $voter->refills_on = date("Y-m-d 00:00:00", strtotime('next sunday'));
                $voter->votes_available = 10;
                $voter->save();
                $updateRequired = true;
            }
        }

        if ($updateRequired) {
            dd('updating');
            // Update ranks of names for last week
            $names = Names::orderBy('votes')->get();
            foreach ($names as $rank => $name) {
                $rank++;
                $name->rank_last_week = $rank;
                $name->save();
            }
        }
    }
}
