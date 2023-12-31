<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plant;
use DB;

class PlantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Plant::all();
    }

    
    public function searchText($searchTerm) {
        // return Plant
        // $plant = Plant::where('title', 'like',  '%' . $searchTerm . '%')->get(); //works
        $plant = Plant::where(DB::raw('LOWER(title)'), 'like',  '%' . strtolower($searchTerm) . '%')->orWhere(DB::raw('LOWER(description)'), 'like',  '%' . strtolower($searchTerm) . '%')->get();
                
        if ($plant == null) {
            return response()->json([
                'No item matching this search term was found.'
            ], 404);
        }

        return $plant;
    }

    /**
     * Returns resource with quantity under a certain value
     */
    public function lowStock($quantity) {
        $plant = Plant::where('quantity', '<',  $quantity)->get();
                
        return $plant;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // --validate incoming data
        $request->validate([
            'title'=> 'required',
            'description'=> 'required',
            'price'=> 'required',
            'photo_link'=> 'required',
            'quantity'=> 'required'
        ]);

        return Plant::create($request->all());
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $plant = Plant::find($id);

        if ($plant == null) {
            return response()->json([
                'Article Id ' . $id . ' not found.'
            ], 404);
        }

        return $plant;
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
        // return $plant->update($request->all());
        $plant = Plant::find($id);

        if ($plant == null) {
            return response()->json([
                'Plant Id ' . $id . ' not found.'
            ], 404);
        } else {
            $plant->update($request->all());
            return $plant;
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
        // return $plant->delete();
        $plant = Plant::find($id);

        if($plant != null){
            $plant->delete($id);
            return response()->json(['Plant deleted.']);
        } else {
            return response()->json(['Plant not found.'], 404);
        }
    }

}
