<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Hash;
use Validator;
use Crypt;

class FunctionController extends Controller
{
    public function createID(){
      $id = DB::connection('mysql')->select("SELECT SUBSTRING(id, 3, 4) AS nomor
      FROM t4t_t4t.t4t_participant WHERE id LIKE 'MF%' ORDER BY no DESC LIMIT 1");

      return $id;
    }
    public function createID_($kode){
      $id = DB::connection('mysql')->select("SELECT SUBSTRING(id, 3, 10) AS nomor
      FROM t4t_t4t.t4t_participant WHERE id LIKE ? ORDER BY no DESC LIMIT 1",[$kode.'%']);

      return $id;
    }
    public function key($api){
      $id_part = DB::connection('mysql')->select("SELECT kode from otenuser where API_KEY=?",[$api]);

      return $id_part;
    }

    public function repeatID($id_part){
      $repeat_id = DB::connection('mysql')->select("SELECT repeat_id FROM t4t_idrelation WHERE id_part=? ORDER BY repeat_id*1 DESC LIMIT 1",[$id_part]);

      return $repeat_id;
    }

    public function create_otenuser(Request $request){
      $validator = Validator::make($request->all(),[
        'uname' => 'required',
        'passwd' => 'required',
        'id_grup' => 'required|in:adm,admoff,fc,fin,mkt,part',
        'level' => 'required|in:0,1,2,3,4,5,6,7,8,9',
        'status' => 'required|in:AKTIF,PASIF',
        'API_KEY' => 'required',
        'active' => 'required|in:0,1',
      ]);

      if ($validator->fails()) {
          $response = array('response' => $validator->messages(), 'success' => false);
          return $response;
      } else {
          $create = DB::table('t4t_t4t.otenuser')->insert(
            [
              'uname' => $request->input('uname'),
              'passwd' => hash('md5',$request->input('passwd')),
              'id_grup' => $request->input('id_grup'),
              'level' => $request->input('level'),
              'status' => $request->input('status'),
              'lastlogin' => date("Y-m-d H:i:s"),
              'digawe' => date("Y-m-d H:i:s"),
              'kode' => $request->input('level').''.date("YmdHis"),
              'API_KEY' => hash::make($request->input('API_KEY')),
              'active' => $request->input('active')
            ]
          );

          $response = array('success' => true);
          return $response;
      }
    }

    public function update_otenuser($pilihan, Request $request, $kodeORuname){

      if ($pilihan=='uname' or
          $pilihan=='passwd' or
          $pilihan=='id_grup' or
          $pilihan=='level' or
          $pilihan=='status' or
          $pilihan=='kode' or
          $pilihan=='API_KEY' or
          $pilihan=='active') {
          if ($pilihan=='passwd') {
          $update = DB::table('t4t_t4t.otenuser')
              ->where('kode', $kodeORuname)
              ->orwhere('uname', $kodeORuname)
              ->update([$pilihan => hash('md5',$request->baru)]);
        }elseif($pilihan=='API_KEY'){
          $update = DB::table('t4t_t4t.otenuser')
              ->where('kode', $kodeORuname)
              ->orwhere('uname', $kodeORuname)
              ->update([$pilihan => Crypt::encrypt($request->baru) ]);
        }else {
          $update = DB::table('t4t_t4t.otenuser')
              ->where('kode', $kodeORuname)
              ->orwhere('uname', $kodeORuname)
              ->update([$pilihan => $request->baru]);
        }

        $response = array('success' => true,'field' => $pilihan, 'kodeORuname' => $kodeORuname, 'newvalue' => $request->baru);
        return $response;
      }else{
        $response = array('success' => false,'paramater' => 'field is not valid');
        return $response;
      }

    }
}
