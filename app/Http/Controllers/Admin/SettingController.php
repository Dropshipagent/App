<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\AdminSetting;
use Auth;

class SettingController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $action = "admin/setting/1";
        $setting = AdminSetting::find('1');
        return view('admin/setting/index', compact('action', 'setting'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.notifications.create');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
                    'intro_video_url' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return back()->withErrors($errors)->withInput();
        } else {

            $AdminSetting = AdminSetting::find($id);
            $AdminSetting->intro_video_url = $request->intro_video_url;
            $AdminSetting->save();

            $request->session()->flash('success', "Setting Update successfully.");
            return redirect('admin/setting');
        }
    }

}
