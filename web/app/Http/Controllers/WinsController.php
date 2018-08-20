<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
Use Validator;

class WinsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $by_wins = DB::connection('mysql')->select('SELECT * FROM t4t_wins where id_part="MF004"');
        // $items = Item::all();
        return response()->json($by_wins);
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
          'text' => 'required'
        ]);

        if ($validator->fails()) {
            $response = array('response' => $validator->messages(), 'success' => false);
            return $response;
        } else {
          //crate Item
          $item = new Item;
          $item->text = $request->input('text');
          $item->body = $request->input('body');
          $item->save();

          return response()->json($item);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $items = Item::find($id);
        return response()->json($items);
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
      $validator = Validator::make($request->all(),[
        'text' => 'required'
      ]);

      if ($validator->fails()) {
          $response = array('response' => $validator->messages(), 'success' => false);
          return $response;
      } else {
        //find an item
        $item = Item::find($id);
        $item->text = $request->input('text');
        $item->body = $request->input('body');
        $item->save();

        return response()->json($item);

      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::find($id);
        $item->delete();

        $response = array('response' => 'Item Deleted', 'success' => true);
        return $response;
    }
}
