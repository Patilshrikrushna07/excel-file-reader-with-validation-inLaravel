<?php
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;


use Validator;
use Response;
use Redirect;
use App\Models\{Country, State, City};
class DropdownController extends Controller
{
    public function getcountries()
    {
        $countries = DB::table('countries')->get();
        return $countries;

    }

    
    public function getStates(Request $request)
    {    
        $states = DB::table('states')
            ->where('country_id', $request->country_id)
            ->get();
        
        if (count($states) > 0) {
            return response()->json($states);
        }
    }

    public function getCities(Request $request)
    {
        $cities = DB::table('cities')
            ->where('state_id', $request->state_id)
            ->get();
        
        if (count($cities) > 0) {
            return response()->json($cities);
        }
    }

   
}