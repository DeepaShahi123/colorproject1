<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\bookdata;

use App\Models\Books;

use App\Models\Order;

class AdminController extends Controller
{
    public function Books()
    {
        if(Auth::id())
        {
            if(Auth::user()->usertype=='1')
            {
                return view ('admin.Books'); 
            }
            else
            {
                return redirect()->back(); 
            }
            
        }
       // return view ('admin.Books');
       else
       {
        return  redirect('login');
       }
    }

    public function uploadBooks(Request $request)
    {
        $data=new Bookdata;

        $image=$request->file;

        $imagename =time().'.'.$image-> getclientOriginalExtension();

        $request->file->move('bookimage',$imagename);

        $data->image=$imagename;

        $data->title=$request->title;

        $data->author=$request->author;

        $data->description=$request->des;
        $data->quantity=$request->quantity;
        
          $data->save();
          
          return redirect()->back()->with('message','Books Added Successfully');

        
    }


    public function  ShowBooks()
    {
        $data=bookdata::all();
        return view ('admin.ShowBooks',compact('data'));
    }
    public function deletebook($id)
    {
        $data=bookdata::find($id);
        $data->delete();
        return redirect()->back()->with('message','Books Deleted Successfully');
    }

    public function updateview($id)
    {
        $data=bookdata::find($id);
         return view('admin.updateview',compact('data'));
    }

    public function updatebook(Request $request ,$id)
    {
        $data=bookdata::find($id);
         //return view('admin.updateview',compact('data'));

         $image=$request->file;

         if($image)
         {

         $imagename =time().'.'.$image-> getclientOriginalExtension();
 
         $request->file->move('bookimage',$imagename);
 
         $data->image=$imagename;

    }
 
         $data->title= $request->title;
 
         $data->author=$request->author;
 
         $data->description=$request->des;
         $data->quantity=$request->quantity;
         
           $data->save();
           
           return redirect()->back()->with('message','Book updated Successfully');
    }

    public function showorder()
    {
        $order=order::all();
        return view('admin.showorder',compact('order'));
    }
    
    public function updatestatus($id)
    {
        $order=order::find($id);
        $order->status='delivered';
        $order->save();

        return redirect()->back();

    }

}