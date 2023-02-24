<?php

namespace App\Imports;

use App\Models\City;
use App\Models\Country;
use App\Models\Doctor;
use App\Models\State;
use App\Models\User;
use App\Rules\Country_State_City;
use Illuminate\Validation\Rules\Exists;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;  

use Throwable;
use ZipStream\Option\Method;
use Illuminate\Validation\Rule;
class UsersImport implements ToModel,WithHeadingRow,WithValidation,SkipsOnError,
SkipsOnFailure,WithChunkReading,WithEvents,ShouldQueue
{

    use SkipsErrors,Importable, SkipsFailures,RegistersEventListeners;     
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */


    public $country_code;
    public $state_id;
    public $state_name;
    public function model(array $row)
    {       

        // Start For Get Country code
        
        $country = Country::where('phonecode',$row['country_code'])->first();
        $state = State::where('name', $row['state'])->where('country_id', $country->id)->first();
        if( !$state){
            $city = City::where('name', $row['city'])->where('state_id', $state->id)->first();
            if(! $city){
            }
        }else{
            //exception
        }
        

        // End For Get Country code
        return new Doctor(
            [
                'id' => $row['no'],
                'name' => $row['name'],
                'email' => $row['email'],
                'phone_number' => $row['phone_number'],
                'city' => $row['city'],
                'state' => $row['state'],
                'country_code' => $row['country_code'],
                'username' => $row['username'],
                'password' => Hash::make('password'),
            ]

        );
    }
    public function __construct() {
    }
	/**
	 * @return array
	 */
    
	public function rules(): array {

        return [

            //Id Validation
            'id' => 'unique:doctor',
            '*.id' => 'unique:doctor',

            //Name validation
            'name' => 'required|regex:/^[a-zA-Z]+\.?[a-zA-Z]+$/',
            '*.name' => 'required|regex:/^[a-zA-Z]+\.?[a-zA-Z]+$/',

            //Email validation
            'email' => function ($attribute, $value, $onFailure) {
                if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                } else {
                    $onFailure("$value is not a valid email address\n");
                }
            },
            '*.email' => 'required|email|unique:doctor',
            'phone_number' => 'required|unique:doctor|digits_between:10,10|regex:/^\\+?\\d{1,4}?[-.\\s]?\\(?\\d{1,3}?\\)?[-.\\s]?\\d{1,4}[-.\\s]?\\d{1,4}[-.\\s]?\\d{1,9}$/',
            '*.phone_number' => 'required|unique:doctor|digits_between:10,10|regex:/^\\+?\\d{1,4}?[-.\\s]?\\(?\\d{1,3}?\\)?[-.\\s]?\\d{1,4}[-.\\s]?\\d{1,4}[-.\\s]?\\d{1,9}$/',

      
            //country Validation
            'country_code' => 'required|numeric|exists:countries,phonecode',
            '*.country_code' => function ($value, $attribute, $onFailure) {
                $this->country_code = $attribute;
                $country=Country::where('phonecode',$attribute)->first();
                if(!$country){
                    $onFailure("$attribute is not a valid country Code");  
                }
                else if($country->phonecode != $attribute){
                    $onFailure("$country->phonecode is not a valid country Code");
                }
            },

            //state Validation
            'state' => 'required|exists:states,name',
            '*.state' => function ($value, $attribute, $onFailure) {
                $country_code = $this->country_code;
                $this->state_name=$attribute;
                $country = Country::where('phonecode', $country_code)->first();
                if ($country) {
                    $state = State::where('name', $attribute)->where('country_id', $country->id)->first();
                    if (!$state) {
                        $onFailure("$attribute is not a state");
                    } else if ($state->name != $attribute) {
                        $onFailure("$state->name is not a state");
                    }
                }
                else{
                    $onFailure("Country Code is invalid");
                }
            },

            
            //City Validation
            'city' => 'required|regex:/^[a-zA-Z]+\.?[a-zA-Z]+$/|exists:cities,name',
            '*.city' => function ($value, $attribute, $onFailure) {
                $state_name=$this->state_name;
                $country_code = $this->country_code;
                $country = Country::where('phonecode', $country_code)->first();
                if ($country) {
                    $state = State::where('name',   $state_name)->where('country_id', $country->id)->first();
                    if ($state) {
                        $city = City::where('name', $attribute)->where('state_id', $state->id)->first();
                         if(!$city){
                            $onFailure("$attribute is not a city");
                            }
                            else if ($city->name != $attribute) {
                                $onFailure("$city->name is not a City");
                            }
                    } 
                    else{
                        $onFailure(" $attribute is not a valid city");
                    }
                
                }
                else{
                    $onFailure("Country Code is invalid");
                }
            },

            //Username
            'username' => 'required',
            '*.username'=>'required',
            
            //Password
            'password' => 'required',
            '*.password'=>'required',
        ];
	}

    public function customValidationMessages()
{
    return [
        // 'phone_number' =>"Only 10 Digits are allowed"
    ];
}

  
    // public function batchSize(): int{
    //     return 100;
    // }

        public function chunkSize(): int
        {
            return 100;
        }

        public static function afterImport(AfterImport $event)
        {
            if($event== true){
                    return "data Imported Successfully";
            }else{
                    return "Data not Imported";
            }
        }

     public function onFailure(Failure ...$failure){

     }

}

