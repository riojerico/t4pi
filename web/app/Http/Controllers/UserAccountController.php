<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;
use App\Http\Controllers\FunctionController;

class UserAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FunctionController $FunctionController)
    {
         $get = $FunctionController->createID();
         echo $nomor = $get[0]->nomor;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id,Request $request,FunctionController $Function)
    {
      //cek ID
      // $get_id_user      = $Function->createID();
      // $get_id_user_pls  = $get_id_user[0]->nomor+1;
      // $id_user          = "MF".$get_id_user_pls;
      $get_id_user      = $Function->createID_('MC');

      if (isset($get_id_user[0]->nomor)) {
        $get_id_user_pls  = $get_id_user[0]->nomor+1;
      }else{
        $get_id_user_pls  = 1;
      }
      $id_user          = "MC".sprintf("%08d", $get_id_user_pls);

      //cek key
      $key = $Function->key($id);


      if ($key==false) {
        return response()->json($key, 401);
      }else{
        $id_part = $key[0]->kode;

        //cek repeatID
        $get_repeat_id = $Function->repeatID($id_part);

        if ($get_repeat_id==false) {
          $repeat_id = 1;
        }else{
          $repeat_id = $get_repeat_id[0]->repeat_id+1;
        }




        $validator = Validator::make($request->all(),[
          'name' => 'required',
          'address' => 'required',
          'phone' => '',
          'email' => 'required|email|unique:t4t_participant',
          'photo' => 'image|mimes:jpeg,bmp,png,gif|max:1000'
        ]);

        if ($validator->fails()) {
            $response = array('response' => $validator->messages(), 'success' => false);
            return $response;
        } else {
          $t_part = DB::table('t4t_t4t.t4t_participant')->insert(
            [
              'id' => $id_user,
              'type' => 'Donor',
              'name' => $request->input('name'),
              'address' => $request->input('address'),
              'phone' => '-',
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
          $t_relation = DB::table('t4t_t4t.t4t_idrelation')->insert(
            [
              'id_part' => $id_part,
              'related_part' => $id_user,
              'repeat_id' => $repeat_id
            ]
          );
          $w_part = DB::table('t4t_web.participants')->insert(
            [
              'id_part' => $id_user
            ]
          );

          if ($request->file('photo')!='') {
            $file       = $request->file('photo');
            $fileName   = $file->getClientOriginalName();
            $request->file('photo')->move("../tester-trees4trees/", date('Ymd-His').'-'.$fileName);
          }else{
            $fileName   = '';
          }
          $drupal_part = DB::table('t4t_drupal_example.participant')->insert(
            [
              'id_part' => $id_user,
              'name' => $request->input('name'),
              'photo' => date('Ymd-His').'-'.$fileName

            ]
          );

          //return response()->json(array('participant'=>$t_part,'idrelation'=>$t_relation,'web_part'=>$w_part));
          //return response()->json(array('success'=>$t_part));
          $response = array('success' => true,
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'email' => $request->input('email'),
            'photo' => $fileName,
            'id_user' => $id_user
          );
          return $response;
        }


      }//end if



    }//end function

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,FunctionController $Function)
    {
        if ($id=='SALT') {
          $response = array('success' => false);
          return response()->json($response, 401);
        }else {
          $key = $Function->key($id);


          if ($key==false) {
            return response()->json($key, 404);
          }else{
            $kode = $key[0]->kode;

            $list_user = DB::connection('mysql')->select("SELECT a.repeat_id,
              b.name,
              b.address,
              b.date_join,
              b.email,
              c.photo,
              d.wins,
              b.id as id_user
              FROM t4t_idrelation a join t4t_participant b
              on a.related_part=b.id left join t4t_drupal_example.`participant` c
              on b.id=c.id_part left join t4t_wins d
              on b.id=d.id_retailer
              where a.id_part=? ORDER BY a.repeat_id*1",[$kode]);

              return response()->json($list_user);

          }
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
