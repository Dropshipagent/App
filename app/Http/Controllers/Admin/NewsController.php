<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\News;
use Auth;
use Image;
use File;
use Storage;

class NewsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $news = News::orderBy('id', 'DESC')->get();
        return view('admin/news/index', ['news_data' => $news]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.news.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'link' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->withErrors($errors)->withInput();
        } else {
            $data = $request->all();
            $newimage = "";
            unset($request['_token']);
            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(storage_path('app/public/news/images'), $imageName);
                //$profile_picture = Storage::put('public', $request->file('image'));
                $newimage = $imageName;
            }
            unset($data['image']);
            $data['image'] = $newimage;
            //dd($request->all());
            if ($result = News::create($data)) {
                return redirect('admin/news')->with('success', 'News saved successfully!');
            } else {
                return redirect()->back()->with('danger', 'Failed to Send , Please try again!');
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $news = News::find($id);
        return view('admin.news.edit', ['news' => $news]);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'link' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->withErrors($errors)->withInput();
        } else {
            $Posts = News::find($id);
            if ($request->hasFile('image')) {
                @unlink(storage_path('app/public/news/images/' . $Posts->image));
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(storage_path('app/public/news/images'), $imageName);
                //$profile_picture = Storage::put('public', $request->file('image'));
                $Posts->image = $imageName;

            }
            $Posts->title = $request->title;
            $Posts->description = $request->description;
            $Posts->link = $request->link;
            
            $Posts->save();
            $request->session()->flash('success', "News Update successfully.");
            return redirect('admin/news');
            // dd($Posts);
        }
    }

    public function destroy($id)
    {
        $news = News::find($id);
        if ($news->delete()) {
            return redirect('admin/news')->with('success', 'News deleted successfully!');
        } else {
            return redirect()->back()->with('danger', 'Failed to Delete, Please try again!');
        }
    }
}
