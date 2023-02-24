<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Dotenv\Store\File\Reader;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use CountryState;  


class FileController extends Controller
{
    function upload(Request $req){
        $result = $req->file('file')->store('apidocs');
        return ["result" => $result];
    }

    function list_user(Request $req){
        $users = Doctor::all();
        // $users = ["hey shri","hey bro"];
        return ['users' => $users];
    }

}
