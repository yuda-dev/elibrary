<?php

namespace App\Http\Controllers;

use App\Book;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class SumbangController extends Controller
{
    public function index()
    {
        $title = 'Sumbang Buku';
        $category = Category::all();
        return view('sumbang.index', compact('title','category'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'ringkasan' => 'required',
            'cover' => 'required|max:5000',
            'file' => 'required|max:5000',
        ]);

        $data = new Book();
        $data->judul = $request->judul;
        $data->slug = Str::slug($request->judul);
        $data->user_id = Auth::id();
        $data->ringkasan = $request->ringkasan;
        $data->penulis = $request->penulis;
        $data->penerbit = $request->penerbit;
        $data->jml_halaman = $request->jml_halaman;
        $data->category_id = $request->category_id;
        $data->is_verify = 0;

        $cover = $request->file('cover');
        $file = $request->file('file');

        if ($cover) {
            $namacover = \Carbon\Carbon::now() . '-' . $cover->getClientOriginalName();
            $cover->move('cover', $namacover);
            $data->cover = $namacover;
        }

        if ($file) {
            $namafile = \Carbon\Carbon::now() . '-' . $file->getClientOriginalName();
            $file->move('filebook', $namafile);
            $data->file = $namafile;
        }

        //dd($data);

        $data->save();

        \Session::flash('sukses','Sumbangan berhasil terkirim !');

        Session::flash('success', 'Terimakasih atas sumbangsih anda, Saat ini sumbangan anda membutuhkan persetujuan terlebih dahulu supaya bisa di baca dan dilihat banyak orang. ');

        return redirect('sumbang');
    }
}
