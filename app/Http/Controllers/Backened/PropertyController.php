<?php

namespace App\Http\Controllers\Backened;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\MultiImage;
use App\Models\Facility;
use App\Models\Amentesis;
use App\Models\PropertyType;
use App\Models\User;
use Intervention\Image\Facades\Image;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Carbon\carbon;


class PropertyController extends Controller
{
    public function AllProperty(){
        $property=property::latest()->get();
        return view('backend.property.all_property',compact('property'));
    }//end method
    public function AddProperty(){
        $propertytype=PropertyType::latest()->get();
        $amenities=Amentesis::latest()->get();
        $activeAgent=User::where('status','active')->where('role','agent')->latest()->get();
        return view('backend.property.add_property',compact('propertytype','amenities','activeAgent'));
    }//end method
    public function StoreProperty(Request $request){
        $amen=$request->amenitis_id;
        $amenities=implode(",",$amen);
        $pcode = IdGenerator::generate(['table' => 'properties','field' => 'property_code','length' => 5, 'prefix' => 'PC' ]);

        $image = $request->file('property_thambnail');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(370,250)->save('upload/property/thambnail/'.$name_gen);
        $save_url = 'upload/property/thambnail/'.$name_gen;
        $property_id=Property::insertGetId([
            'ptype_id'=>$request->ptype_id,
            'amenitis_id'=>$amenities,
            'property_name'=>$request->property_name,
            'property_slug' => strtolower(str_replace(' ', '-', $request->property_name)),
            'property_code'=>$pcode,
            
            'property_status'=>$request->property_status,
            'lowest_price'=>$request->lowest_price,
            'max_price'=>$request->max_price,
            'short_descp'=>$request->short_desc,
            'long_descp'=>$request->short_desc,
            'bedrooms'=>$request->bedrooms,
            'bathrooms'=>$request->bathrooms,
            'garage'=>$request->garage,

            'garage_size'=>$request->garage_size,
            'property_size'=>$request->property_size,
            'address'=>$request->address,
            'latitude'=>$request->latitude,
            'longtude'=>$request->longtude,
            'city'=>$request->city,
            'postal_code' => $request->postal_code,
            'state'=>$request->state,
            'property_video'=>$request->property_video,
            'neighborhood'=>$request->neighborhood,
            'featured'=>$request->featured,
            'hot'=>$request->hot,
            'agent_id'=>$request->agent_id,
            'status'=>1,
            'property_thambnail' => $save_url,
            'created_at'=>Carbon::now(),

        ]);
 /// Multiple Image Upload From Here ////

        $images = $request->file('multi_img');
        foreach($images as $img){

        $make_name = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
        Image::make($img)->resize(770,520)->save('upload/property/multi-image/'.$make_name);
        $uploadPath = 'upload/property/multi-image/'.$make_name;

        MultiImage::insert([

            'property_id' => $property_id,
            'photo_name' => $uploadPath,
            'created_at' => Carbon::now(), 

        ]); 
        } // End Foreach
         ///////end  for multiImage/////
         ////facilities add form/////
         $facilities=count($request->facility_name);
         if($facilities!=null){
            for($i=0;$i<$facilities;$i++){
                $fcount=new Facility();
                $fcount->property_id=$property_id;
                $fcount->facility_name=$request->facility_name[$i];
                $fcount->distance=$request->distance[$i];
                $fcount->save();

            }

         }//end facility
         $notification=array(
            'message'=>'property added Succesfully',
            'alert-type'=>'success'
        );
        return redirect()->route('all.property')->with($notification);

    }//end method

    public function EditProperty($id){
        $property=Property::findOrFail($id);
        $propertytype=PropertyType::latest()->get();
        $amenities=Amentesis::latest()->get();
        $type=$property->amenitis_id;
        $facilities = Facility::where('property_id',$id)->get();
        $property_ami=explode(',',$type);
        $multiImage = MultiImage::where('property_id',$id)->get();
        $activeAgent=User::where('status','active')->where('role','agent')->latest()->get();
        return view('backend.property.edit_property',compact('propertytype','property','amenities','activeAgent','property_ami','multiImage','facilities'));

    }// end method
    
        public function UpdateProperty(Request $request){

        $amen=$request->amenitis_id;
        $amenities=implode(",",$amen);
        $property_id = $request->id;

        Property::findOrFail($property_id)->update([

            'ptype_id' => $request->ptype_id,
            'amenitis_id'=>$amenities,
            'property_name' => $request->property_name,
            'property_slug' => strtolower(str_replace(' ', '-', $request->property_name)), 
            'property_status' => $request->property_status,

            'lowest_price' => $request->lowest_price,
            'max_price' => $request->max_price,
            'short_descp' => $request->short_descp,
            'long_descp' => $request->long_descp,
            'bedrooms' => $request->bedrooms,
            'bathrooms' => $request->bathrooms,
            'garage' => $request->garage,
            'garage_size' => $request->garage_size,

            'property_size' => $request->property_size,
            'property_video' => $request->property_video,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,

            'neighborhood' => $request->neighborhood,
            'latitude' => $request->latitude,
            'longtude'=>$request->longtude,
            'featured' => $request->featured,
            'hot' => $request->hot,
            'agent_id' => $request->agent_id, 
            'updated_at' => Carbon::now(), 

        ]);

         $notification = array(
            'message' => 'Property Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.property')->with($notification); 

    }// End Method 
    public function DeleteProperty($id){
        Property::findOrFail($id)->delete();
                 $notification = array(
            'message' => 'Property deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.property')->with($notification);
    }//end method
  
         public function UpdatePropertyThambnail(Request $request){

        $pro_id = $request->id;
        $oldImage = $request->old_img;

        $image = $request->file('property_thambnail');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(370,250)->save('upload/property/thambnail/'.$name_gen);
        $save_url = 'upload/property/thambnail/'.$name_gen;

        if (file_exists($oldImage)) {
            unlink($oldImage);
        }

        Property::findOrFail($pro_id)->update([

            'property_thambnail' => $save_url,
            'updated_at' => Carbon::now(), 
        ]);

         $notification = array(
            'message' => 'Property Image Thambnail Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 
        
    }//end method
     public function UpdatePropertyMultiimage(Request $request){

        $imgs = $request->multi_img;

        foreach($imgs as $id => $img){
            $imgDel = MultiImage::findOrFail($id);
            unlink($imgDel->photo_name);

    $make_name = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
    Image::make($img)->resize(770,520)->save('upload/property/multi-image/'.$make_name);
        $uploadPath = 'upload/property/multi-image/'.$make_name;

        MultiImage::where('id',$id)->update([

            'photo_name' => $uploadPath,
            'updated_at' => Carbon::now(),
 
        ]);

        } // End Foreach 


         $notification = array(
            'message' => 'Property Multi Image Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 


    }// End Method 

     public function PropertyMultiImageDelete($id){

        $oldImg = MultiImage::findOrFail($id);
        unlink($oldImg->photo_name);

        MultiImage::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Property Multi Image Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 

    }// End Method 


    public function StoreNewMultiimage(Request $request){

        $new_multi = $request->imageid;
        $image = $request->file('multi_img');

     $make_name = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
    Image::make($image)->resize(770,520)->save('upload/property/multi-image/'.$make_name);
        $uploadPath = 'upload/property/multi-image/'.$make_name;

        MultiImage::insert([
            'property_id' => $new_multi,
            'photo_name' => $uploadPath,
            'created_at' => Carbon::now(), 
        ]);

    $notification = array(
            'message' => 'Property Multi Image Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 
    }// End Method


    

}
