<?php

namespace App\Helpers;

trait FootBallApiTrait
{
    use HttpHelper, ResponseHelper;

    public function allCompetitions()
    {
        try {
            $response = $this->httpGet('v4/competitions', true);
            $competitions = $response->collect('competitions');
            return $this->data($competitions);
        } catch (\Exception $exception) {
            return $this->error($exception);
        }
    }


    public function findCompetitionById($competition_id)
    {

        try {
            if (!isset($competition_id)) {
                throw new \RuntimeException("The identifier is required", 422);
            }
            $response = $this->httpGet('v4/competitions/' . $competition_id, true);
            return $this->data($response->collect());
        } catch (\Exception $exception) {
            return $this->error($exception);
        }
    }

    public function allTeams()
    {

        try {
            $response = $this->httpGet('/v4/teams/', true);
            return $this->data($response->json());
        } catch (\Exception $exception) {
            return $this->error($exception);
        }
    }


    public function findTeamById($team_id, $array = false)
    {

        try {
            if (!isset($team_id)) {
                throw new \RuntimeException("The identifier is required", 422);
            }

            $response = $this->httpGet('v4/teams/' . $team_id, true);
            if ($array) {
                return $response->collect();
            }
            return $this->data($response->collect());
        } catch (\Exception $exception) {
            return $this->error($exception);
        }
    }

    public function findTeamsByCompetitionId($competition_id, $array = false)
    {

        try {
            if (!isset($competition_id)) {
                throw new \RuntimeException("The identifier is required", 422);
            }
            $response = $this->httpGet('/v4/competitions/' . $competition_id . '/teams', true);

            if ($array) {
                return $response->collect('teams');
            }
            return $this->data($response->collect('teams'));
        } catch (\Exception $exception) {
            return $this->error($exception);
        }
    }

    public function findPersonById($id)
    {

        try {
            $response = $this->httpGet('/v4/persons/' . $id, true);
            return $this->data($response->json());
        } catch (\Exception $exception) {
            return $this->error($exception);
        }
    }
}
