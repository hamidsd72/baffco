<?php

namespace App\Http\Controllers\Admin;

use App\Activity;
use App\Models\Lang;
use App\Http\Controllers\Controller;
use App\Photo;
use App\ProductCategory;
use App\ProductCategoryHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller
{

    // Construct Function
    public function __construct()
    {
//        $this->middleware(['auth', 'clearance']);
        $this->middleware('auth');
    }

    // Create Function
    public function create()
    {
        $items = ProductCategory::all();
        return view('admin.product-category.create', compact('items'), ['title' => 'افزودن دسته بندی محصولات']);
    }

    // Store Function
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sort_id' => 'required',
            'name' => 'required',
        ]);

        $slug = Validator::make($request->all(), [
            'slug' => 'required|unique:product_categories',
        ]);
        if ($slug->fails()) {
            return redirect()->back()->with([
                'status' => 'danger-600',
                "message" => 'نام وارد شده تکراری است لطفا نام دیگری انتخاب کنید'
            ])->withErrors($validator)->withInput();
        }
        $slug_en = Validator::make($request->all(), [
            'slug_en' => 'required|unique:product_categories',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with([
                'status' => 'danger-600',
                "message" => 'لطفا فیلد ها را بررسی نمایید، بعضی از فیلد ها نمی توانند خالی باشند.'
            ])->withErrors($validator)->withInput();
        }

        try {
            $item = ProductCategory::create([
                'sort_id' => $request->sort_id,
                'parent_id' => $request->parent_id,
                'name' => $request->name,
                'slug' => $request->slug,
                'keyword' => $request->keyword,
                'dis' => $request->dis,
                'text' => $request->text,
                'sort_menu' => $request->sort_menu,
                'short_text' => $request->short_text,
            ]);
            if ($request->hasFile('photo')) {
                $photo = new Photo();
                $photo->path = file_store($request->photo, 'includes/asset/uploads/ProductCategory/photos/', 'photo-');;
                $item->photo()->save($photo);
            }
            if ($request->hasFile('icon')) {
                $item->icon = file_store($request->icon, 'includes/asset/uploads/ProductCategory/photos/', 'icon-');;
                $item->save();
            }
            if ($request->hasFile('icon_hover')) {
                $item->icon_hover = file_store($request->icon_hover, 'includes/asset/uploads/ProductCategory/photos/', 'icon_hover-');;
                $item->save();
            }

            store_lang($request,'product_category',$item->id,['name','keyword','dis']);

            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->type = 'store';
            $activity->text = ' دسته بندی محصولات : ' . '(' . $request->name . ')' . ' را ثبت کرد';
            $item->activity()->save($activity);
            return redirect('admin/product-category-list')->with(['status' => 'success', "message" => 'دسته بندی محصولات با موفقیت ثبت شد.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);
        }
    }

    // Edit Function
    public function edit($id)
    {
        $items = ProductCategory::where('id', '!=', $id)->get();
        $item = ProductCategory::find($id);
        return view('admin.product-category.edit', compact('item', 'items'), ['title' => $item->name]);
    }

    // Update Function
    public function update(Request $request, $id)
    {
        $item = ProductCategory::find($id);
        $old_name = $item->name;
        $validator = Validator::make($request->all(), [
            'sort_id' => 'required',
            'name' => 'required',
        ]);

        $slug = Validator::make($request->all(), [
            'slug' => 'required|unique:product_categories,slug,'.$item->id
        ]);

        if ($slug->fails()) {
            return redirect()->back()->with([
                'status' => 'danger-600',
                "message" => 'نام وارد شده تکراری است لطفا نام دیگری انتخاب کنید'
            ])->withErrors($validator)->withInput();
        }
        $slug_en = Validator::make($request->all(), [
            'slug_en' => 'required|unique:product_categories,slug_en,'.$item->id
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->with([
                'status' => 'danger-600',
                "message" => 'لطفا فیلد ها را بررسی نمایید، بعضی از فیلد ها نمی توانند خالی باشند.'
            ])->withErrors($validator)->withInput();
        }
        try {
            $item->sort_id = $request->sort_id;
            $item->parent_id = $request->parent_id ? $request->parent_id : $item->parent_id;
            $item->name = $request->name;
            $item->slug = $request->slug;
            $item->slug_en = $request->slug_en;
            $item->keyword = $request->keyword;
            $item->dis = $request->dis;
            $item->text = $request->text;
            $item->short_text = $request->short_text;
            $item->sort_menu = $request->sort_menu;
            if ($request->hasFile('icon')) {
                if ($item->icon) {
                    $old_path = $item->icon;
                    File::delete($old_path);
                }
                $item->icon = file_store($request->icon, 'includes/asset/uploads/ProductCategory/photos/', 'icon-');;
            }
            if ($request->hasFile('icon_hover')) {
                if ($item->icon_hover) {
                    $old_path = $item->icon_hover;
                    File::delete($old_path);
                }
                $item->icon_hover = file_store($request->icon_hover, 'includes/asset/uploads/ProductCategory/photos/', 'icon_hover-');;
            }
            $item->save();
            if ($request->hasFile('photo')) {
                if ($item->photo) {
                    $old_path = $item->photo->path;
                    File::delete($old_path);
                    $item->photo->delete();
                }
                    $photo = new Photo();
                    $photo->path = file_store($request->photo, 'includes/asset/uploads/ProductCategory/photos/', 'photo-');;
                    $item->photo()->save($photo);
            }
            if($item->langs)
            {
                foreach ($item->langs as $lang){
                    $lang->delete();
                }
            }
            store_lang($request,'product_category',$item->id,['name','keyword','dis']);
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->type = 'update';
            $activity->text = ' دسته بندی محصولات : ' . '(' . $old_name . ')' . ' را ویرایش کرد';
            $item->activity()->save($activity);
            return redirect('admin/product-category-list')->with(['status' => 'success', "message" => 'دسته بندی محصولات با موفقیت ویرایش شد.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);
        }
    }

    // Index Function
    public function index(ProductCategoryHelper $ProductCategoryHelper)
    {
// dd(ProductCategory::where('name','انتقال قدرت')->first()->children_orderBy->reverse());
        $categories = ProductCategory::orderBy('sort_id', 'ASC')->get();
        $ProductCategoryHelper->setupItems($categories);
        $items = $ProductCategoryHelper->render_order_list();
        return view('admin.product-category.index', compact('items'), ['title' => 'دسته بندی محصولات']);
    }

    // Destroy Function
    public function destroy($id)
    {
        $item = ProductCategory::find($id);
        $old_name = $item->name;
        try {
            if($item->children && count($item->children))
            {
                return redirect()->back()->with(['status' => 'danger-600', "message" => 'دارای زیر دسته می باشد']);
            }
            if($item->product && count($item->product))
            {
                return redirect()->back()->with(['status' => 'danger-600', "message" => 'دارای محصول می باشد']);
            }
            if($item->doctors && count($item->doctors))
            {
                return redirect()->back()->with(['status' => 'danger-600', "message" => 'دارای توصیه پزشکی می باشد']);
            }
            if(is_file($item->icon))
            {
                File::delete($item->icon);
            } if(is_file($item->icon_hover))
            {
                File::delete($item->icon_hover);
            }
            $item->delete();
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->type = 'delete';
            $activity->text = ' دسته بندی محصولات : ' . '(' . $old_name . ')' . ' را حذف کرد';
            $item->activity()->save($activity);
            return redirect('admin/product-category-list')->with(['status' => 'success', "message" => 'دسته بندی محصولات با موفقیت حذف شد.']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);
        }
    }
    // Active Function
    public function active($type,$id)
    {
        $item = ProductCategory::find($id);
        $old_name = $item->name;
        $activity = new Activity();
        try {
            if($item->$type=='active')
            {
                $item->$type='pending';
                $item->update();
                $activity->user_id = Auth::user()->id;
                $activity->type = 'update';
                $activity->text = ' دسته بندی محصول : ' . '(' . $old_name . ')' . ' را غیرفعال کرد';
                $item->activity()->save($activity);
                return redirect()->back()->with(['status' => 'success', "message" => ' با موفقیت غیرفعال شد.']);
            }
            else
            {
                if(!$item->photo)
                {
                    return redirect()->back()->with(['status' => 'danger-600', "message" => 'دسته بندی های صفحه اصلی باید تصویر داشته باشند.']);
                }
                $item->$type='active';
                $item->update();
                $activity->user_id = Auth::user()->id;
                $activity->type = 'update';
                $activity->text = ' دسته بندی محصول : ' . '(' . $old_name . ')' . ' را فعال کرد';
                $item->activity()->save($activity);
                return redirect()->back()->with(['status' => 'success', "message" => ' با موفقیت فعال شد.']);
            }

        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);
        }
    }
}
