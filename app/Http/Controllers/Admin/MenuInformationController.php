<?php

namespace App\Http\Controllers\Admin;

use App\Menu;
use App\Models\MenuInformation;
use App\Models\MenuInformationPicture;
use App\Activity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class MenuInformationController extends Controller {
    
    public function controller_paginate()
    {
        return 20;
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id=null)
    {
        $menus = Menu::all();
        $items = MenuInformation::orderByDesc('id')->paginate($this->controller_paginate());
        if ($id) {
            $items = MenuInformation::where('menu_id',$id)->orderByDesc('id')->paginate($this->controller_paginate());
        }
        return view('admin.menu_information.index', compact('items','menus','id'), ['title' => 'محتوا ها']);
    }

    public function create($id=null)
    {
        $items = Menu::orderBy('sort_id')->get();
        return view('admin.menu_information.create', compact('items','id'), ['title' => 'افزودن محتوا']);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text'          => 'required',
            'menu_id'       => 'required',
            // 'section_number'=> 'required',
            'position'      => 'required',
            'sort'          => 'required',
            'status'        => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with([
                'status' => 'danger-600',
                "message" => 'لطفا فیلد ها را بررسی نمایید، بعضی از فیلد ها نمی توانند خالی باشند.'
            ])->withErrors($validator)->withInput();
        }
        try {
            $item = MenuInformation::create($request->all());
            if ($request->hasFile('pic')) {
                $pic = new MenuInformationPicture();
                $pic->menu_information_id = $item->id;
                $pic->pic = file_store($request->pic, 'includes/asset/uploads/menu/information/picture/', 'pic-');
                $pic->save();
            }
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->type = 'store';
            $activity->text = ' محتوا : ' . '(' . $item->id . ')' . ' را ثبت کرد';
            $item->activity()->save($activity);
            return redirect()->route('admin-menu-information-filter-by-id',$item->menu_id)->with(['status' => 'success', "message" => 'محتوا با موفقیت ثبت شد.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);
        }
    }

    public function edit($id)
    {
        $item   = MenuInformation::find($id);
        $items  = Menu::orderBy('sort_id')->get();
        $pic    = MenuInformationPicture::where('menu_information_id' , $item->id)->first();
        return view('admin.menu_information.edit', compact('items','item','pic'), ['title' => 'ویرایش محتوا']);
    }

    public function show($id)
    {
        $item   = MenuInformation::find($id);
        if ($item->status == 'active') {
            $item->status = 'pending';
        } else {
            $item->status = 'active';
        }
        $item->save();
        $old_title  = $item->id;
        $activity = new Activity();
        $activity->user_id = Auth::user()->id;
        $activity->type = 'update';
        $activity->text = ' محتوا : ' . '(' . $old_title . ')' . ' را ویرایش کرد';
        $item->activity()->save($activity);
        return redirect()->back()->with(['status' => 'success', "message" => 'محتوا با موفقیت ویرایش شد.']);
    }

    public function update(Request $request, $id)
    {
        $item       = MenuInformation::find($id);
        $old_title  = $item->id;
        $validator  = Validator::make($request->all(), [
            'text'          => 'required',
            'menu_id'       => 'required',
            // 'section_number'=> 'required',
            'position'      => 'required',
            'sort'          => 'required',
            'status'        => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with([
                'status' => 'danger-600',
                "message" => 'لطفا فیلد ها را بررسی نمایید، بعضی از فیلد ها نمی توانند خالی باشند.'
            ])->withErrors($validator)->withInput();
        }
        try {
            $item->update($request->all());
            if ($request->hasFile('pic')) {
                $items = MenuInformationPicture::where('menu_information_id',$item->id)->get();
                if ($items->count()) {
                    foreach ($items as $item) {
                        $item->delete();
                        // File::delete($item->pic);
                    }
                }
                $pic = new MenuInformationPicture();
                $pic->menu_information_id = $item->id;
                $pic->pic = file_store($request->pic, 'includes/asset/uploads/menu/information/picture/', 'pic-');
                $pic->save();
            }
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->type = 'update';
            $activity->text = ' محتوا : ' . '(' . $old_title . ')' . ' را ویرایش کرد';
            $item->activity()->save($activity);
            return redirect()->route('admin-menu-information-filter-by-id',$request->menu_id)->with(['status' => 'success', "message" => 'محتوا با موفقیت ویرایش شد.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);
        }
    }

    public function destroy($id)
    {
        $item = MenuInformation::find($id);
        $old_title = $item->id;
        try {
            $items = MenuInformationPicture::where('menu_information_id',$item->id)->get();
            if ($items->count()) {
                foreach ($items as $item) {
                    $item->delete();
                    File::delete($item->pic);
                }
            }
            $item->delete();
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->type = 'delete';
            $activity->text = ' محتوا : ' . '(' . $old_title . ')' . ' را حذف کرد';
            $item->activity()->save($activity);
            return redirect()->back()->with(['status' => 'success', "message" => 'محتوا با موفقیت حذف شد.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);
        }
    }

}
