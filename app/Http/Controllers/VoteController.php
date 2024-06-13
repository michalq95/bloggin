<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoteRequest;
use App\Http\Resources\ScoreResource;
use App\Models\Score;
use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{

    public function __invoke(VoteRequest $request)
    {
        $score = $request->object['score'];
        if (!$score) {
            $score = Score::create(['scoreable_id' => $request->id, 'scoreable_type' => $request->voteable_type, 'score' => 0]);
        }

        $oldScoreValue = $score['score'];

        $data = $request->validated();
        $vote = Vote::where('user_id', $data['user_id'])->where('voteable_type', $data['voteable_type'])->where('voteable_id', $data['voteable_id'])->first();
        if ($vote) {

            if ($vote['vote'] == $data['vote'])  return new ScoreResource($score);
            $newScoreValue = -$vote['vote'] + $data['vote'];
            $vote->update($data);
        } else {

            $vote = Vote::create($data);
            $newScoreValue = $data['vote'];
        }
        $score->update(['score' => $oldScoreValue + $newScoreValue]);

        return new ScoreResource($score);
    }
}
