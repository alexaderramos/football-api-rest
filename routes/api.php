<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('competitions/{competition_id}/teams', [ApiController::class, 'getTeamsByCompetitionId'])->where('competition_id', '[0-9]+');


Route::get('competitions', [ApiController::class, 'getAllCompetitions']);
Route::get('competitions/{competition_id}', [ApiController::class, 'getFindCompetitionById'])->where('competition_id', '[0-9]+');;
Route::get('team', [ApiController::class, 'allTeams']);
Route::get('team/{team_id}', [ApiController::class, 'getTeamById']);
Route::get('players', [ApiController::class, 'getAllPlayers']);
