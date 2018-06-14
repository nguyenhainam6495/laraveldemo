<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TheLoai;
use App\Loaitin;

class LoaiTinController extends Controller
{
    //
    public function getDanhSach()
    {
    	$loaitin = Loaitin::all();
    	return view('admin.loaitin.danhsach',['loaitin'=>$loaitin]);
    }

    public function getThem()
    {
        $theloai = TheLoai::all();
    	return view('admin.loaitin.them',['theloai'=>$theloai]);
    }

    public function postThem(Request $request)
    {
    	$this->validate($request,
            [
                'Ten'=>'required|unique:LoaiTin,Ten|min:1|max:100',
                'TheLoai'=>'required'
            ],[
                'Ten.required'=>'Bạn chưa nhập tên loại tin',
                'Ten.unique'=>'Tên loại tin đã tồn tại',
                'Ten.min'=>'Tên loại tin phải có độ dài từ 1 đến 100 ký tự',
                'Ten.max'=>'Tên loại tin phải có độ dài từ 1 đến 100 ký tự',
                'TheLoai.required'=>'Bạn chưa chọn thể loại',
            ]);

        $loaitin = new LoaiTin;
        $loaitin->Ten = $request->Ten;
        $loaitin->TenKhongDau = changeTitle($request->Ten);
        $loaitin->idTheLoai = $request->TheLoai;
        $loaitin->save();

        return redirect('admin/loaitin/them')->with('thongbao','Bạn thêm thành công');
    }

    public function getSua($id)
    {
        $theloai = TheLoai::all();
    	$loaitin = Loaitin::find($id);
        return view('admin.loaitin.sua',['loaitin'=>$loaitin,'theloai'=>$theloai]);
    }
    public function postSua(Request $request,$id)
    {
    	$this->validate($request,
            [
                'Ten'=>'required|unique:LoaiTin,Ten|min:1|max:100',
                'TheLoai'=>'required'
            ],[
                'Ten.required'=>'Bạn chưa nhập tên loại tin',
                'Ten.unique'=>'Tên loại tin đã tồn tại',
                'Ten.min'=>'Tên loại tin phải có độ dài từ 1 đến 100 ký tự',
                'Ten.max'=>'Tên loại tin phải có độ dài từ 1 đến 100 ký tự',
                'TheLoai.required'=>'Bạn chưa chọn thể loại',
            ]);
        $loaitin = LoaiTin::find($id);
        $loaitin->Ten = $request->Ten;
        $loaitin->TenKhongDau = changeTitle($request->Ten);
        $loaitin->idTheLoai = $request->TheLoai;
        $loaitin->save();

        return redirect('admin/loaitin/sua/'.$id)->with('thongbao','Bạn đã sửa thành công');
    }

    public function getXoa($id)
    {
    	$loaitin = Loaitin::find($id);
        $loaitin->delete();

        return redirect('admin/loaitin/danhsach')->with('thongbao','Bạn đã xóa thành công');
    }
}
