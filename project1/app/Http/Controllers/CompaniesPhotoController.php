<?php

namespace App\Http\Controllers;

use App\Models\companies_photo;
use App\Models\Company;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
class CompaniesPhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth:apiCompany');
    }
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    //Define your validation rules here.
    $rules = [
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ];
    //Create a validator, unlike $this->validate(), this does not automatically redirect on failure, leaving the final control to you :)
    $validated = Validator::make($request->all(), $rules);

    //Check if the validation failed, return your custom formatted code here.
    if($validated->fails())
    {
        return response()->json(['status' => 'error', 'messages' => 'The given data was invalid.', 'errors' => $validated->errors()]);
    }
//If not failed, the code will reach here

//save the data to the database
$user_id = auth()->user()->id;
$image=null;
$filename=null;
$save_path=null;
$photo_id_in_company_table = Company::find($user_id)->photo_id ;

            if($request->hasFile('photo')){
              $image = $request->file('photo');
              $filename = time() . '.' . $image->getClientOriginalExtension();
              $save_path=storage_path('\uploadsComp');
              if (!file_exists($save_path)) {
                mkdir($save_path, 666, true);
            }
              Image::make($image)->resize(300, 300)->save( $save_path.'\\' . $filename ) ;

            };
            //we create the photo only when the user dont have an id
            if($photo_id_in_company_table==null){
            $new_photo= companies_photo::create([
                'user name'=>auth()->user()->name,
                'path' => $filename,

            ]);
            $is_created= Company::find($user_id)->update(['photo_id'=>$new_photo->id]);
        }
            else{
                $is_created= companies_photo::find($photo_id_in_company_table)->update(['path'=>$filename,]);


                $new_photo=companies_photo::all()->where('id',$photo_id_in_company_table);
            }



        return response()->json(['new photo'=>$new_photo ,'url' =>$save_path.'\\'.$filename ,'is created'=>$is_created]);
    }



    /**
     * Display the specified resource.
     */
    public function showMine(companies_photo $companies_photo)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(companies_photo $companies_photo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, companies_photo $companies_photo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(companies_photo $companies_photo)
    {
        //
    }
}
