<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TheLoai;
use App\TinTuc;
use App\LoaiTin;
use App\Post;
use App\Comment;

class TinTucController extends Controller
{
    //
    public function getDanhSach()
    {
    	$tintuc = TinTuc::orderBy('created_at', 'desc')->get();

        
        return view('admin.tintuc.danhsach',['tintuc'=>$tintuc]);
    }

    public function getThem()
    {
        $theloai = TheLoai::all();
        $loaitin = Loaitin::all();
    	return view('admin.tintuc.them',['theloai'=>$theloai,'loaitin'=>$loaitin]);
    }

    public function postThem(Request $request)
    {
    	$this->validate($request,[
            'LoaiTin'=>'required',
            'TieuDe'=>'required|min:3|unique:TinTuc,TieuDe',
            'TomTat'=>'required',
            'NoiDung'=>'required', 
        ],[
           'LoaiTin.required'=>'Bạn chưa chọn loại tin',
           'TieuDe.required'=>'Bạn chưa chọn tiêu đề',
           'TieuDe.min'=>'Tiêu đề phải có ít nhất 3 ký tự',
           'TieuDe.unique'=>'Tiêu đề đã tồn tại',
           'TomTat.required'=>'Bạn chưa nhập tóm tắt',
           'NoiDung.required'=>'Bạn chưa nhập nội dung',

        ]);

        $tintuc = new TinTuc;
        $tintuc->TieuDe = $request->TieuDe;
        $tintuc->TieuDeKhongDau = changeTitle($request->TieuDe);
        $tintuc->idLoaiTin = $request->LoaiTin;
        $tintuc->TomTat = $request->TomTat;
        $tintuc->NoiDung = $request->NoiDung;
        $tintuc->SoLuotXem = 0;


        if($request->hasFile('Hinh'))
        {

            $file = $request->file('Hinh');
            $duoi = $file->getClientOriginalExtension();//kiem tra duoi anh

            if(strcmp($duoi, 'jpg') != 0 && strcmp($duoi, 'png') != 0 && strcmp($duoi, 'png') != 0)
            {
                
                return redirect('admin/tintuc/them')->with('loi','Bạn chỉ được chọn file có đuôi là jpg,png,jpeg');
            }
            
            $name = $file->getClientOriginalName(); // kiem tra xem ten anh co trung ko?
            $Hinh = str_random(4)."_". $name;
            while (file_exists("upload/tintuc/".$Hinh)) {    // kiem tra xem ton tai chua 
                $Hinh = str_random(4)."_". $name;
            }
            $file->move("upload/tintuc",$Hinh); // luu hinh vao trong thu muc
            $tintuc->Hinh = $Hinh;

           
        }else{
            $tintuc->Hinh = "";
        }

            $tintuc->save();

            return redirect('admin/tintuc/them')->with('thongbao','Bạn đã thêm tin thành công');

    }

    public function getSua($id)
    {
        $theloai = TheLoai::all();
        $loaitin = Loaitin::all();
        $tintuc = TinTuc::find($id);
        return view('admin.tintuc.sua',['tintuc'=>$tintuc,'theloai'=>$theloai,'loaitin'=>$loaitin]);
    }
    public function postSua(Request $request,$id)
    {
    	$tintuc = TinTuc::find($id);
        $this->validate($request,[
            'LoaiTin'=>'required',
            'TieuDe'=>'required|min:3|unique:TinTuc,TieuDe',
            'TomTat'=>'required',
            'NoiDung'=>'required', 
        ],[
           'LoaiTin.required'=>'Bạn chưa chọn loại tin',
           'TieuDe.unique'=>'Tiêu đề đã tồn tại',
           'TomTat.required'=>'Bạn chưa nhập tóm tắt',
           'NoiDung.required'=>'Bạn chưa nhập nội dung',

        ]);

        $tintuc->TieuDe = $request->TieuDe;
        $tintuc->TieuDeKhongDau = changeTitle($request->TieuDe);
        $tintuc->idLoaiTin = $request->LoaiTin;
        $tintuc->TomTat = $request->TomTat;
        $tintuc->NoiDung = $request->NoiDung;

        if($request->hasFile('Hinh'))
        {
            $file = $request->file('Hinh');
            $duoi = $file->getClientOriginalExtension();//kiem tra duoi anh
            if($duoi != 'jpg' && $duoi != 'png' && $duoi != 'jpeg');
            {
                return redirect('admin/tintuc/them')->with('loi','Bạn chỉ được chọn file có đuôi là jpg,png,jpeg');
            }
            $name = $file->getClientOriginalName(); // kiem tra xem ten anh co trung ko?
            $Hinh = str_random(4)."_". $name;
            while (file_exists("upload/tintuc/".$Hinh)) {    // kiem tra xem ton tai chua 
                $Hinh = str_random(4)."_". $name;
            }

            $file->move("upload/tintuc",$Hinh); // luu hinh vao trong thu muc
            unlink("upload/tintuc/".$tintuc->Hinh); // xóa ảnh cũ đi lưu file mới 
            $tintuc->Hinh = $Hinh;
        }
        $tintuc->save();
        return redirect('admin/tintuc/sua/'.$id)->with('thongbao','Sửa thành công');

    }

    public function getXoa($id)
    {
    	$tintuc = TinTuc::find($id);
        $tintuc->delete();

        return redirect('admin/tintuc/danhsach')->with('thongbao','Xóa thành công');
    }
}
