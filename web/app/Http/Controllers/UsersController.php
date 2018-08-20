<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

Use Validator;
use App\Transformers\UserTransformer;
use DB;

class UsersController extends Controller
{
    public function __contruct(){

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $user = DB::connection('mysql')->select('SELECT
          wins,
          time as registered,
          relation as used,
          id_retailer as id_user
         FROM t4t_wins where id_part="MF178"');

      return response()->json($user);
    }

    public function wins($api){
      $id_part = DB::connection('mysql')->select("SELECT kode from otenuser where API_KEY=?",[$api]);
      if ($id_part==false) {
        return response()->json($id_part, 401);
      }else{
        $id_participant = $id_part[0]->kode;
        $user = DB::connection('mysql')->select("SELECT
                a.wins,
                a.time AS registered,
                a.relation AS used,
                a.id_retailer AS id_user,
                a.no_shipment,
                b.geo,
                b.total_trees,
                b.farmer,
                b.species,
                b.area,
                b.village,
                b.district,
                b.municipality,
                b.planting_year
               FROM t4t_wins a LEFT JOIN t4t_web.`planting_maps` b
               ON a.no_shipment=b.id_shipment
               WHERE a.id_part=?",[$id_participant]);

        return response()->json($user);
      }

    }

    public function wins_detail($wins,$api){
      $id_part = DB::connection('mysql')->select("SELECT kode from otenuser where API_KEY=?",[$api]);
      if ($id_part==false) {
        return response()->json($id_part, 401);
      }else{
        $id_participant = $id_part[0]->kode;
        $user = DB::connection('mysql')->select("SELECT
                a.wins,
                a.time AS registered,
                a.relation AS used,
                a.id_retailer AS id_user,
                a.no_shipment,
                b.geo,
                b.total_trees,
                b.farmer,
                b.species,
                b.area,
                b.village,
                b.district,
                b.municipality,
                b.planting_year
               FROM t4t_wins a LEFT JOIN t4t_web.`planting_maps` b
               ON a.no_shipment=b.id_shipment
               WHERE a.id_part=? and wins=? ",[$id_participant,$wins]);

        if ($user==false) {
          return response()->json($user, 404);
        }else{
          return response()->json($user);
        }

      }
    }

}
