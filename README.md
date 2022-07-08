<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>



## Descripción

Prueba tecnica para postulante

[Documentacion API - https://documenter.getpostman.com/view/5024904/UzJPKuZM](https://documenter.getpostman.com/view/5024904/UzJPKuZM)

## Sobre el limite el solicitudes

Al consultar información sobre una competencia en particular, se consultaba los equipos y jugadores, estos se registran en la base de datos, para en las siguientes consultas, primero verificar si existen en la base de datos, si este el caso retornamos estos registros locales, de lo contrario se vuelve a consultar al api de football.org, y si aún asi no encuentra registros, retornamos un valor vacio o una validacion segun sea el tipo de solicitud.

````php
$response = $this->httpGet('v4/competitions/' . $competition_id, true);
$competition = $response->collect();

$teams = Team::with('players')->where('competition_id', $competition_id)->get();

if (count($teams) === 0) {
    $teams = $this->findTeamsByCompetitionId($competition_id, true);
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
$competition['teams'] = $teams;
````


## Sobre los mensajes de validación y respuestas

He creado una clase Helper para poder reducir el código del controlador y tener un mayor control de las excepciones que puedan ocurrir.

````app/Helpers/ResponseHelper.php````

La clase ``HttpHelper.php`` permite realizar las consultas al api de football.org, capturando los errores en excepciones que en conjunto con las funciones de ``ResponseHelper``, permiten mostrar al usuario los errores de ambos lados de la aplicación. 


## Demo
<p><a href="https://ibb.co/zZMg3Tr"><img src="https://i.ibb.co/rHjhBRm/1.png" alt="1" border="0" /></a></p>
<p><a href="https://imgbb.com/"><img src="https://i.ibb.co/m9zDdfS/2.png" alt="2" border="0" /></a></p>
<p><a href="https://imgbb.com/"><img src="https://i.ibb.co/H71yszw/3.png" alt="3" border="0" /></a></p>
<p><a href="https://ibb.co/WHpkPQS"><img src="https://i.ibb.co/2FYvswX/4.png" alt="4" border="0" /></a></p>
