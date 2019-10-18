<?php

namespace App\Http\Controllers;

use App\Players;
use App\Teams;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;
use Throwable;

class CompetitionsController extends Controller
{
    private $url = 'https://api.football-data.org/v2/competitions/';
    private $authorization = "x-auth-token: 93b009de6f9544b0b15a22b2b2f987cf";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function list()
    {
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', $this->authorization));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $value = json_decode($result, true);

        for ($i = 0; $i < count($value['competitions']); $i++) {
            $array[] = $value['competitions'][$i];
        }

        return $array;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ch = curl_init($this->url . $id);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', $this->authorization));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $value = json_decode($result, true);
        $this->teams($id);
        return $value;
    }

    private function teams($id)
    {
        try {
            $ch = curl_init('https://api.football-data.org/v2/competitions/' . $id . '/teams');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', $this->authorization));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);

            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpcode == 400) {
                throw new Exception('Solicitud incorrecta');
            } elseif ($httpcode == 403) {
                throw new Exception('Recurso restringido');
            } elseif ($httpcode == 404) {
                throw new Exception('Recurso no encontrado');
            } elseif ($httpcode == 429) {
                throw new Exception('Demasiadas solicitudes');
            }

            curl_close($ch);
            $value = json_decode($result, true);

            for ($i = 0; $i < $value['count']; $i++) {
                $validate = Teams::find($value['teams'][$i]['id']);
                if ($validate == null) {
                    $team = new  Teams();
                    $team->codigo = $value['teams'][$i]['id'];
                    $team->name = $value['teams'][$i]['name'];

                    DB::transaction(function () use ($value, $team) {
                        $team->save();
                    });
                }
            }

            $this->players();

            $r['mensaje'] = 'ok';
            $r['datos'] = $value;

        } catch (Exception $e) {
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    private function players()
    {

        $teams = Teams::all();
        foreach ($teams as $team) {
            $squads = null;
            $ch = curl_init('http://api.football-data.org/v2/teams/' . $team->id);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', $this->authorization));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);

            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpcode == 400) {
                throw new Exception('Solicitud incorrecta');
            } elseif ($httpcode == 403) {
                throw new Exception('Recurso restringido');
            } elseif ($httpcode == 404) {
                throw new Exception('Recurso no encontrado');
            } elseif ($httpcode == 429) {
                throw new Exception('Demasiadas solicitudes');
            }
            curl_close($ch);

            $value = json_decode($result);
            $squads = collect($value->squad);

            foreach ($squads as $squad) {
                $validate = Players::find($squad->id);
                if ($validate == null) {
                    $player = new  Players();
                    $player->codigo = $squad->id;
                    $player->name = $squad->name;
                    $player->position = $squad->position;
                    $player->shirt = $squad->shirtNumber;
                    $player->team_id = $team->id;

                    DB::transaction(function () use ($value, $player) {
                        $player->save();
                    });
                }
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
