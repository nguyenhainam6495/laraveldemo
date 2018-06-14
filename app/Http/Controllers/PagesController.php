<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use App\Slide;
use App\TheLoai;
use App\TinTuc;
use App\LoaiTin;
use App\User;


class PagesController extends Controller
{
    
    function __construct()
    {
        
    	$theloai = TheLoai::all();
    	$slide = Slide::all();
    	view()->share('theloai',$theloai);
    	view()->share('slide',$slide);

        if(Auth::check())
        {
            view()->share('nguoidung',Auth::user());
        }
    }

    function trangchu()
    {
        
        $tinnoibat = TinTuc::orderBy('created_at', 'desc')->take(5)->get();
        // dd($tinnoibat->first());
        $tin1 = $tinnoibat->shift();
    	return view('pages.trangchu', compact('tinnoibat','tin1'));
    }

    function lienhe()
    {
    	
    	return view('pages.lienhe');
    }

    function loaitin($id)
    {
        $loaitin = LoaiTin::find($id);
        $tintuc = TinTuc::where('idLoaiTin',$id)->paginate(6);
        return view('pages.loaitin',['loaitin'=>$loaitin],['tintuc'=>$tintuc]);
    }
    function tintuc($id)
    {
        $tintuc = Tintuc::find($id);

        $tinnoibat = Tintuc::where('NoiBat',1)->take(5)->get();

        $tinlienquan = Tintuc::where('idLoaiTin',$tintuc->idLoaiTin)->take(4)->get();

        // dd($tintuc);

        return view('pages.tintuc',['tintuc'=>$tintuc,'tinnoibat'=>$tinnoibat,'tinlienquan'=>$tinlienquan]);
    }

    function getDangNhap()
    {
        return view('pages.dangnhap');
    }

    function postDangNhap(Request $request)
    {
        $this->validate($request,[
            'email'=>'required',
            'password'=>'required|min:6|max:32',
            ],[
            'email.required'=>'Bạn chưa nhập Email',
            'password.required'=>'Bạn chưa nhập Password',
            'password.min'=>'Password không được nhỏ hơn 6 ký tự',
            'password.max'=>'Password không được lớn hơn 32 ký tự',
            ]);
        if (Auth::attempt(['email'=>$request->email,'password'=>$request->password])) 
        {
            return redirect('trangchu');
        }
        else
        {
            return redirect('dangnhap')->with('thongbao','Đăng nhập không thành công');
        }
    }

    function getDangXuat()
    {
        Auth::logout();
        return redirect('trangchu');
    }

    function getNguoidung()
    {
        return view('pages.nguoidung');
    }

    function postNguoidung(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|min:3',
        ],[
            'name.required' =>'Bạn chưa nhập tên người dùng',
            'name.min' =>'Tên người dùng phải có ít nhất 3 ký tự',
            

        ]);

        $user = Auth::user();
        $user->name = $request->name;

        if($request->changePassword  == "on") 
        {
            $this->validate($request,[
            'password' => 'required|min:6|max:32',
            'passwordAgain' => 'required|same:password',
        ],[
            'password.required'=>'Bạn chưa nhập mật khẩu',
            'password.min'=>'Mật khẩu phải có ít nhất 6 ký tự',
            'password.max'=>'Mật khẩu chỉ được tối đa 32 ký tự',
            'passwordAgain.required'=>'Bạn chưa nhập lại mật khẩu',
            'passwordAgain.same'=>'Mật khẩu nhập lại chưa trùng nhau',

        ]);
            $user->password = bcrypt($request->password);// mã hóa mật khẩu 
        }
        $user->save();

        return redirect('nguoidung')->with('thongbao','Bạn đã sửa thành công');
    }

    function getDangky()
    {
        return view('pages.dangky');
    }

    function postDangky(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|max:32',
            'passwordAgain' => 'required|same:password',
        ],[
            'name.required' =>'Bạn chưa nhập tên người dùng',
            'name.min' =>'Tên người dùng phải có ít nhất 3 ký tự',
            'email.required' =>'Bạn chưa nhập email',
            'email.email' =>'Bạn chưa nhập đúng định dạng email',
            'email.unique'=>'Email đã tồn tại',
            'password.required'=>'Bạn chưa nhập mật khẩu',
            'password.min'=>'Mật khẩu phải có ít nhất 6 ký tự',
            'password.max'=>'Mật khẩu chỉ được tối đa 32 ký tự',
            'passwordAgain.required'=>'Bạn chưa nhập lại mật khẩu',
            'passwordAgain.same'=>'Mật khẩu nhập lại chưa trùng nhau',

        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);// mã hóa mật khẩu
        $user->quyen = 0;
        $user->save();

        return redirect('dangky')->with('thongbao','Đăng ký thành công');
    }

    function timkiem(Request $request)
    {
        $tukhoa = $request->tukhoa;
        $tintuc= Tintuc::where('TieuDe','like',"%$tukhoa%")->orWhere('TomTat','like',"%$tukhoa%")->orWhere('NoiDung','like',"%$tukhoa%")->take(30)->paginate(5);
        return view('pages.timkiem',['tintuc'=>$tintuc,'tukhoa'=>$tukhoa]);
    }
}