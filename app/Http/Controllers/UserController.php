<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Exports\ExportUser;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\Failure;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpCache\Store;

class UserController extends Controller
{
     //Fetch all the user
     public function list()
     {
          $users = Doctor::get();
          // return view('users', ['users' => $users]);
          return view('users', ['users' => $users]);
     }

     //it for importview
     public function importView(Request $request)
     {
          return view('importFile');
     }

     //For Download a File
     public function exportUsers(Request $request)
     {
          return Excel::download(new ExportUser, 'Doctors_Info.xlsx');

     }


     //For Storing a File
     public function store()
     {
          return view('users.import');
     }




     public function import_user(Request $request, Response $responce)
     {
          $request->validate(
               [
                    'excel_file' => 'required'
               ]
          );

          //Storing And Reading Excel File
          $file = $request->file('excel_file')->store('import');
          $import = (new UsersImport);
          $import->import($file);

          
          //Storing a Errors File on localStorage
          $data = $import->failures();
          $file = fopen("data.txt", "w");
          fwrite($file, $data);
          fclose($file);
          Storage::put('data.txt',$data);



          // dd($import->errors());
          // dd($import->failures());

          //Printing a Output And Storing a Data into a Database
          if ($import->failures()->isNotEmpty()) {
               Log::channel('custom')->info($import->failures());


              // It is For JSON Responce
               // return response()->json([
               //      "status" => false,
               //      "message"=>$import->failures(),
               //      // "message" => [
               //      //      "Row" => $row,
               //      //      "attribute" => $attribute,
               //      //      "Error" => $error,
               //      //      "Values" => $value,
               //      // ]
               // ]);

               return back()->withFailures($import->failures());   

          }

          //it is for JSON Responce;      
          // else {
          //      return response()->json([
          //           "status" => true,
          //           "message" => "Import in Queue"
          //      ]);
          // }
          return redirect()->back()->with('success', 'File Successfully Imported');

     }


}

