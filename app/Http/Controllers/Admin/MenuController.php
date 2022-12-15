<?php

namespace App\Http\Controllers\Admin;

use App\Activity;
use App\Http\Controllers\Controller;
use App\Menu;
use App\MenuHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class MenuController extends Controller
{
    public function controller_paginate()
    {
        return 20;
    }

    // Construct Function
    public function __construct()
    {
//        $this->middleware(['auth', 'clearance']);
        $this->middleware('auth');
    }

    // Create Function
    public function create()
    {
        return view('admin.menu.create', ['title' => 'افزودن صفحه']);
    }

    // Store Function
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
//            'name_en' => 'required',
            'slug' => 'required',
//            'slug_en' => 'required',
//            'page_content' => 'required',
//            'page_content_en' => 'required',
        ]);
        if (Menu::where('slug',$request->slug)->first()) {
            return redirect()->back()->with([
                'status' => 'danger-600',
                "message" => 'صفحه ای با این عنوان تعریف شده.'
            ])->withErrors($validator)->withInput();
        }
//        if (Menu::where('slug_en',$request->slug_en)->first()) {
//            return redirect()->back()->with([
//                'status' => 'danger-600',
//                "message" => 'صفحه ای با این عنوان انگلیسی تعریف شده.'
//            ])->withErrors($validator)->withInput();
//        }
        if ($validator->fails()) {
            return redirect()->back()->with([
                'status' => 'danger-600',
                "message" => 'لطفا فیلد ها را بررسی نمایید، بعضی از فیلد ها نمی توانند خالی باشند.'
            ])->withErrors($validator)->withInput();
        }
        try {
            $item = Menu::create([
                'name' => $request->name,
                'slug' => $request->slug,
//                'slug_en' => $request->slug_en,
                // 'page_content' => $request->page_content,
                // 'place' => $request->place,
//                'menu_type' => $request->menu_type,
                // 'link' => $request->link
            ]);
            if ($request->hasFile('pic')) {
                $item->pic = file_store($request->pic, 'includes/asset/uploads/menu/photos/', 'pic-');;
            }
            $item->save();
            store_lang($request,'menu',$item->id,['name','page_content']);
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->type = 'store';
            $activity->text = ' صفحات داینامیک : ' . '(' . $request->name . ')' . ' را ثبت کرد';
            $item->activity()->save($activity);
            return redirect('admin/menu-list')->with(['status' => 'success', "message" => 'صفحه با موفقیت ثبت شد.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);
        }
    }

    // Edit Function
    public function edit($id)
    {
        $item = Menu::find($id);
        return view('admin.menu.edit', compact('item'), ['title' => $item->name]);
    }

    // Update Function
    public function update(Request $request, $id)
    {
        $item = Menu::find($id);
        $old_name = $item->name;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
//            'name_en' => 'required',
            'slug' => 'required',
//            'slug_en' => 'required',
//            'page_content' => 'required',
//            'page_content_en' => 'required',
        ]);
        if (Menu::where('slug',$request->slug)->where('id','!=',$id)->first()) {
            return redirect()->back()->with([
                'status' => 'danger-600',
                "message" => 'صفحه ای با این عنوان تعریف شده.'
            ])->withErrors($validator)->withInput();
        }
//        if (Menu::where('slug_en',$request->slug_en)->where('id','!=',$id)->first()) {
//            return redirect()->back()->with([
//                'status' => 'danger-600',
//                "message" => 'صفحه ای با این عنوان انگلیسی تعریف شده.'
//            ])->withErrors($validator)->withInput();
//        }
        if ($validator->fails()) {
            return redirect()->back()->with([
                'status' => 'danger-600',
                "message" => 'لطفا فیلد ها را بررسی نمایید، بعضی از فیلد ها نمی توانند خالی باشند.'
            ])->withErrors($validator)->withInput();
        }
        try {
            $item->name = $request->name;
            $item->slug = $request->slug;
//            $item->slug_en = $request->slug_en;
            // $item->page_content = $request->page_content;
            // $item->place = $request->place;
//            $item->menu_type = $request->menu_type;
            // $item->link = $request->link;
            if ($request->hasFile('pic')) {
                if (is_file($item->pic)) {
                    $old_path = $item->pic;
                    File::delete($old_path);
                }
                $item->pic = file_store($request->pic, 'includes/asset/uploads/menu/photos/', 'pic-');;
            }
            $item->save();
            if($item->langs)
            {
                foreach ($item->langs as $lang){
                    $lang->delete();
                }
            }
            store_lang($request,'menu',$item->id,['name','page_content']);
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->type = 'update';
            $activity->text = ' صفحات داینامیک : ' . '(' . $old_name . ')' . ' را ویرایش کرد';
            $item->activity()->save($activity);
            return redirect('admin/menu-list')->with(['status' => 'success', "message" => 'منو / صفحه با موفقیت ویرایش شد.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);
        }
    }

    // Index Function
    public function index(MenuHelper $MenuHelper)
    {
        $items = Menu::orderBy('sort_id','ASC')->paginate($this->controller_paginate());
        return view('admin.menu.index', compact('items'), ['title' => 'صفحات داینامیک']); 
    }

    // Destroy Function
    public function destroy($id)
    {
        $item = Menu::find($id);
        if ($item->informations()->count()) {
            return redirect()->back()->with(['status' => 'danger-600', "message" => 'صفحه دارای محتوا میباشد، قابل حذف نیست.']);
        }
        
        $old_name = $item->name;
        try {
            if (is_file($item->pic)) {
                $old_path = $item->pic;
                File::delete($old_path);
            }
            $item->delete();
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->type = 'delete';
            $activity->text = ' صفحات داینامیک : ' . '(' . $old_name . ')' . ' را حذف کرد';
            $item->activity()->save($activity);
            return redirect('admin/menu-list')->with(['status' => 'success', "message" => 'صفحه با موفقیت حذف شد.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);
        }
    }

    // Active Function
    public function active($type,$id)
    {
        $item = Menu::find($id);
        $old_name = $item->name;
        if($type=='slider_down')
        {
            $text='جهت نمایش زیر اسلایدر';
        }
        else
        {
            $text='جهت نمایش';
        }
        $activity = new Activity();
        try {
            if($item->$type=='active')
            {
                $item->$type='pending';
                $item->update();
                $activity->user_id = Auth::user()->id;
                $activity->type = 'update';
                $activity->text = ' صفحات داینامیک : ' . '(' . $old_name . ')' . ''.$text.' را غیرفعال کرد';
                $item->activity()->save($activity);
                return redirect()->back()->with(['status' => 'success', "message" => ' با '.$text.' موفقیت غیرفعال شد.']);
            }
            else
            {
                $item->$type='active';
                $item->update();
                $activity->user_id = Auth::user()->id;
                $activity->type = 'update';
                $activity->text = ' صفحات داینامیک : ' . '(' . $old_name . ')' . ''.$text.' را فعال کرد';
                $item->activity()->save($activity);
                return redirect()->back()->with(['status' => 'success', "message" => ' با '.$text.' موفقیت فعال شد.']);
            }

        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);
        }
    }

    // Active Function
    public function active2(Request $request)
    {
        $item       = Menu::find($request->id);
        $type       = 'menu_type';
        $text       = 'جهت نمایش';
        $activity = new Activity();
        try {
            $item->$type=$request->val;
            $item->update();
            $activity->user_id = Auth::user()->id;
            $activity->type = 'update';
            $activity->text = ' صفحات داینامیک : ' . '(' . $item->name . ')' . ''.$text.' را غیرفعال کرد';
            $item->activity()->save($activity);
            return redirect()->back()->with(['status' => 'success', "message" => ' با '.$text.' موفقیت غیرفعال شد.']);

        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);
        }
    }

    // SORT Function
    public function sort(Request$request,$id)
    {
        $item = Menu::find($id);
        $old_name = $item->name;
        try {
            $item->sort_id=$request->sort_id;
            $item->update();
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->type = 'delete';
            $activity->text = ' صفحات داینامیک : ' . '(' . $old_name . ')' . ' سورت آی دی را تغیر داد';
            $item->activity()->save($activity);
            return redirect('admin/menu-list')->with(['status' => 'success', "message" => 'سورت آیدی صفحه با موفقیت تغیر یافت.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);
        }
    }
}
