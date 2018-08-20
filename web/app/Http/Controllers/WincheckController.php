<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;
use App\Http\Controllers\FunctionController;

class WincheckController extends Controller
{
    public function show(FunctionController $Function,$wins,$id){
      //cek key
      $key = $Function->key($id);

      if ($key==false) {
        return response()->json($key, 401);
      }else{
        $id_part = $key[0]->kode;

        $get_id_user = DB::connection('mysql')->select("SELECT id_retailer
          FROM t4t_wins WHERE id_part=? AND wins=? LIMIT 1",[$id_part,$wins]);

            $id_user = $get_id_user[0]->id_retailer;

            if ($id_user==false) {
              return response()->json('Wins Not Found',404);
            }else{

            $general = DB::connection('mysql')->select("SELECT
              substring(a.date_join,1,4) as member_since ,
              b.qty_trees as total_trees
              from t4t_participant a join t4t_web.`participants` b
              on a.id=b.id_part where id_part=?",[$id_user]);
            //dd($general);
            // $data = DB::connection('mysql')->select("SELECT * FROM t4t_htc WHERE no_shipment IN
            //   (
            //    SELECT no_shipment FROM t4t_wins WHERE id_part=? AND wins=?
            //  )",[$id_part,$wins]);
            $data = DB::connection('mysql')->select("SELECT * FROM t4t_htc WHERE bl='RT004-1'");

              $x = array(
                "member_since"=> $general[0]->member_since,
                "total_trees"=> $general[0]->total_trees,
                "trees"=> $data
              );
              // dd($x);
              // die();
            return response()->json($x);
          }

      }
    }
}
