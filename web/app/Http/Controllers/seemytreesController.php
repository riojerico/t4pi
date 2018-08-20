<?php

namespace App\Http\Controllers;
use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\FunctionController;
use Illuminate\Support\Facades\Hash;
use Input as Input;

class seemytreesController extends Controller
{
    public function Register(Request $request, FunctionController $Function)
    {
      $get_id_user      = $Function->createID_('CR');

      if (isset($get_id_user[0]->nomor)) {
        $get_id_user_pls  = $get_id_user[0]->nomor+1;
      }else{
        $get_id_user_pls  = 1;
      }
      $id_user          = "CR".sprintf("%08d", $get_id_user_pls);

      $validator = Validator::make($request->all(),[
        'name' => 'required',
        'address' => 'required',
        'phone' => 'required',
        'email' => 'required|email|max:50',
        'password' => 'required|min:6',
        'photo' => 'required|image|mimes:jpeg,bmp,png,gif|max:1000'
      ]);

      if ($validator->fails()) {
          $response = array('response' => $validator->messages(), 'success' => false);
          return $response;
      } else {
        $t4t_part = DB::table('t4t_t4t.t4t_participant')->insert(
          [
            'id' => $id_user,
            'type' => 'Donor',
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'phone' => $request->input('phone'),
            'fax' => '-',
            'director' => '-',
            'pic' => '-',
            'product' => '-',
            'outlet_qty' => '0',
            'material' => '-',
            'date_join' => date("Y-m-d H:i:s"),
            'email' => $request->input('email')
          ]
        );
        ####
        $web_part = DB::table('t4t_web.participants')->insert(
          [
            'id_part' => $id_user
          ]
        );
        ###
        $t4t_oten = DB::table('t4t_t4t.otenuser')->insert(
          [
            'uname' => $request->input('email'),
            'passwd' => Hash::make($request->input('password')),
            'id_grup' => 'customer',
            'level' => 9,
            'status' => 'AKTIF',
            'lastlogin' => date("Y-m-d H:i:s"),
            'digawe' => date("Y-m-d H:i:s"),
            'kode' => $id_user,
            'active' => 1,

          ]
        );
        ###
        if ($request->file('photo')!='') {
          $file       = $request->file('photo');
          $fileName   = $file->getClientOriginalName();
          $request->file('photo')->move("../../tracking-apps/", date('Ymd-His').'-'.$fileName);
        }
        $drupal_part = DB::table('t4t_drupal_example.participant')->insert(
          [
            'id_part' => $id_user,
            'name' => $request->input('name'),
            'photo' => date('Ymd-His').'-'.$fileName

          ]
        );

        //return response()->json(array('1'=>$t4t_part,'2'=>$web_part,'3'=>$t4t_oten,'4'=>$drupal_part));
        $response = array('success' => true);
        return $response;
      }
    }

    public function ListUser()
    {
      $data = DB::connection('mysql')->select("SELECT * from t4t_t4t.t4t_participant where id like ?",['CR%']);

      return response()->json($data);
    }


}
