<?php

namespace App\Http\Controllers;

use App\Helpers\FootBallApiTrait;
use App\Helpers\HttpHelper;
use App\Helpers\ResponseHelper;
use App\Models\Player;
use App\Models\Team;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Js;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    use HttpHelper, ResponseHelper;

    public function __construct()
    {

    }

    public function getAllCompetitions()
    {
        try {
            $response = $this->httpGet('v4/competitions', true);
            $competitions = $response->collect('competitions');
            return $this->data($competitions);
        } catch (\Exception $exception) {
            return $this->error($exception);
        }
    }

    public function getAllPlayers()
    {
        $players = Player::all();
        return $this->data($players);
    }


    public function getFindCompetitionById($competition_id)
    {
        try {
            $response = $this->httpGet('v4/competitions/' . $competition_id, true);
            $competition = $response->collect();

            $teams = Team::with('players')->where('competition_id','=', $competition_id)->get();
            if (count($teams) === 0) {
                $response = $this->httpGet('v4/competitions/' . $competition_id . '/teams', true);
                $data = $response->collect();
                $teams = $data['teams'];
                foreach ($teams as $value) {
                    $t = DB::table('teams')->where('id', '=', $value['id'])->first();
                    if ($t === null) {
                        $team = Team::create([
                            'id' => $value['id'],
                            'competition_id' => $competition_id,
                            'name' => $value['name'],
                            'shortName' => $value['shortName'], // password
                            'crest' => $value['crest'],
                            'clubColors' => $value['clubColors'],
                            'founded' => $value['founded'],
                        ]);

                        $squad = $value['squad'];

                        if ($squad !== null && count($squad) > 0) {
                            foreach ($squad as $val) {
                                $p = Player::find($val['id']);
                                if ($p === null) {
                                    Player::create([
                                        'id' => $val['id'],
                                        'team_id' => $value['id'],
                                        'name' => $val['name'],
                                        'position' => $val['position'],
                                        'shirtNumber' => null
                                    ]);
                                }

                            }
                        }

                    }

                }
                $teams = Team::with('players')->where('competition_id', $competition_id)->get();
            }
            $competition['teams'] = $teams;
            return $this->data($competition, 200);

        } catch (\Exception $exception) {
            return $this->error($exception);
        }
    }

    public function allTeams()
    {

        try {
            $teams = Team::all();
            return $this->data($teams);
        } catch (\Exception $exception) {
            return $this->error($exception);
        }
    }


    public function getTeamById($team_id)
    {

        try {
            if (!isset($team_id)) {
                throw new \RuntimeException("The identifier is required", 422);
            }
            $team = Team::with('players')->where('id', $team_id)->first();
            if ($team === null) {
                return $this->warning("Team not found", null, 404);
            }
            return $this->data($team);
        } catch (\Exception $exception) {
            return $this->error($exception);
        }
    }

    public function getTeamsByCompetitionId($competition_id)
    {

        try {
            if (!isset($competition_id)) {
                throw new \RuntimeException("The identifier is required", 422);
            }


            $teams = Team::with('players')->where('competition_id', '=', $competition_id)->get();
            if (count($teams) === 0) {
                $response = $this->httpGet('v4/competitions/' . $competition_id . '/teams', true);
                $data = $response->collect();
                $teams = $data['teams'];
                foreach ($teams as $value) {
                    $t = DB::table('teams')->where('id', '=', $value['id'])->first();
                    if ($t === null) {
                        $team = Team::create([
                            'id' => $value['id'],
                            'competition_id' => $competition_id,
                            'name' => $value['name'],
                            'shortName' => $value['shortName'],
                            'crest' => $value['crest'],
                            'clubColors' => $value['clubColors'],
                            'founded' => $value['founded'],
                        ]);

                        $squad = $value['squad'];

                        if ($squad !== null && count($squad) > 0) {
                            foreach ($squad as $val) {
                                $p = Player::find($val['id']);
                                if ($p === null) {
                                    Player::create([
                                        'id' => $val['id'],
                                        'team_id' => $value['id'],
                                        'name' => $val['name'],
                                        'position' => $val['position'],
                                        'shirtNumber' => null
                                    ]);
                                }
                            }
                        }
                    }
                }
                $teams = Team::with('players')->where('competition_id', $competition_id)->get();
            }
            return $this->data($teams);
        } catch (\Exception $exception) {
            return $this->error($exception);
        }
    }

    public function findPersonById($id)
    {

        try {
            $response = $this->httpGet('/v4/persons/' . $id, true);
            return $this->data($response->collect());
        } catch (\Exception $exception) {
            return $this->error($exception);
        }
    }


}
