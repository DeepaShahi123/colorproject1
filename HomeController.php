<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use App\Models\user;

use App\Models\bookdata;

use App\Models\Cart;

use App\Models\Order;

class HomeController extends Controller
{
    public function redirect()
    {
        $usertype=Auth::user()->usertype;
       

        if($usertype=='1')
        {
            
            
            return view('admin.home');
        }

        else
        {
            $data = bookdata::paginate(2);

            $user=auth()->user();

            $count =cart::where('phone',$user->phone)->count();

            return view('user.home',compact('data','count'));
            
           
        }

    }
      public function index()
      {

        if(Auth::id())
        {
            return redirect('redirect');
        }
        else
        {
            $data = bookdata::paginate(2);
            return view('user.home',compact('data'));
        }
        
      }
       
      public function search(Request $request)
      {
        $search=$request->search;
        
       if($search=='')
        {
          $data = bookdata::paginate(2);
          return view('user.home',compact('data'));
        }
        $data=bookdata::where('title','Like','%' .$search.'%')->get();

        return view('user.home',compact('data'));

          }

      public function addcart (Request $request ,$id)
      {
         if(Auth::id())
         {
            $user=auth()->user();
            $bookdata=bookdata::find($id);
            
            $cart=new cart;
            $cart->name=$user->name;
            $cart->phone=$user->phone;
            


            $cart->product=$bookdata->title;
           // $cart->author=$bookdata->author;
           // $cart->description=$bookdata->discription;
            $cart->quantity=$request->quantity;
            $cart->save();
         return redirect()->back()->with('message','Books Added Successfully'); 
         }
        else
        {
        return redirect('login');
         }
          }

          public function showcart()
          {

            $user=auth()->user();

            $cart=cart::where('phone',$user->phone)->get();

            $count =cart::where('phone',$user->phone)->count();

            return view('user.Showcart',compact('count','cart'));
            

            
          }
          
          
          public function deletecart($id)
          {

            $data=cart::find($id);

            $data->delete();
            return redirect()->back()->with('message','Books remove Successfully');
          }

          public function confirmorder(Request $request)
          {

            $user=auth()->user();

            $name=$user->name;

            $phone=$user->phone;

            $address=$user->address;

            foreach ($request->bookname as $key=>$bookname)
            {

              $order=new order;
              $order->book_name=$request->bookname[$key];

              

              $order->name=$name;
              $order->phone=$phone;
              $order->address=$address;
              $order->status='not deliverd';

              $order->save();
            }

            DB::table('carts')->where('phone',$phone)->delete();
            return redirect()->back()->with('message','Books ordered Successfully');;

          }
        }

