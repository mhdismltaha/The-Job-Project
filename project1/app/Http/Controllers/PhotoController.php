<?php

namespace App\Http\Controllers;

use Intervention\Image\ImageManagerStatic as Image;
use App\Models\photo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;




class PhotoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }


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
$user_id = Auth::user()->id;
$image=null;
$filename=null;
$save_path=null;
$photo_id_in_user_table = User::find($user_id)->photo_id ;

            if($request->hasFile('photo')){
              $image = $request->file('photo');
              $filename = time() . '.' . $image->getClientOriginalExtension();
              $save_path=storage_path('\uploads');
              if (!file_exists($save_path)) {
                mkdir($save_path, 666, true);
            }
              Image::make($image)->resize(300, 300)->save( $save_path.'\\' . $filename ) ;

            };
            //we create the photo only when the user dont have an id
            if($photo_id_in_user_table==null){
            $new_photo= photo::create([
                'user name'=>Auth::user()->name,
                'path' => $filename,

            ]);
            $is_created= User::find($user_id)->update(['photo_id'=>$new_photo->id]);
        }
            else{
                $is_created= photo::find($photo_id_in_user_table)->update(['path'=>$filename,]);


                $new_photo=photo::all()->where('id',$photo_id_in_user_table);
            }



        return response()->json(['new photo'=>$new_photo ,'url' =>$save_path.'\\'.$filename ,'is created'=>$is_created]);
    }


    public function showMine(photo $photo)
    {
        $photo_id=Auth::user()->photo_id;
        $photo_obj = photo::find($photo_id);
        $path=storage_path('\uploads\\');
        $name_from_DB = $photo_obj->path;
        $headers = ['Content-Type' => 'image/png'];






        //return response()->json(['s'=>$photo_obj->path]);
        return response()->file($path.$name_from_DB , $headers);
    }




    public function edit(photo $photo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, photo $photo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(photo $photo)
    {
        //
    }
}
