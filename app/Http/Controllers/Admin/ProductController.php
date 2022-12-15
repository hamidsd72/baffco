<?php

namespace App\Http\Controllers\Admin;

use App\Product;
use App\Models\Lang;
use App\Models\ProductAttr;
use App\Photo;
use App\Library;
use App\Attribute;
use App\Models\ProductType;
use App\Models\ProductBrand;
use App\ProductCategory;
use App\Models\ProductCat;
use App\Activity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{

    // Construct Function
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Create Function
    public function create()
    {
        $cats = ProductCat::all();
        $items = ProductCategory::where('parent_id',0)->get();
        $types= ProductType::where('parent_id',0)->get();
        $brands = ProductBrand::all();

        return view('admin.product.create', compact('cats','items','types','brands'), ['title' => 'افزودن محصول']);
    }

    // Store Function
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [

           /* 'cat_id' => 'required',
            'category_id' => 'required',
            'type_id' => 'required',
            'brand_id' => 'required',*/
            'name' => 'required',
//            'zeneric_name' => 'required',
            'text' => 'required',
//            'code' => 'required',
//            'status' => 'required',
            'photo' => 'required',
        ]);
        if(Product::where('slug',$request->slug)->first())
        {
            return redirect()->back()->with([
                'status' => 'danger-600',
                "message" => 'عنوان تکراری می باشد'
            ])->withErrors($validator)->withInput();
        }
        if ($validator->fails()) {
            return redirect()->back()->with([
                'status' => 'danger-600',
                "message" => 'لطفا فیلد ها را بررسی نمایید، بعضی از فیلد ها نمی توانند خالی باشند.',
            ])->withErrors($validator)->withInput();
        }
       // try {
        $item = Product::create([
            'cat_id' => $request->cat_id,
            'category_id' => $request->category_id,
            'type_id' => $request->type_id,
            'brand_id' => $request->brand_id,
            'name' => $request->name,
//            'zeneric_name' => $request->zeneric_name,
            'slug' => $request->slug,
            'slug_en' => $request->slug_en,
//            'code' => $request->code,
            'text' => $request->text,
//            'status' => $request->status,
            'short_text' => $request->short_text,
            'titleseo' => $request->titleseo,
            'keywordsseo' => $request->keywordsseo,
            'descriptionseo' => $request->descriptionseo,
            'text_masraf' => $request->text_masraf,
            'practical_title_1' => $request->practical_title_1,
            'practical_text_1' => $request->practical_text_1,
            'practical_link_1' => $request->practical_link_1,
            'practical_title_2' => $request->practical_title_2,
            'practical_text_2' => $request->practical_text_2,
            'practical_link_2' => $request->practical_link_2,
            'practical_title_3' => $request->practical_title_3,
            'practical_text_3' => $request->practical_text_3,
            'practical_link_3' => $request->practical_link_3,
            'pdf_title_1' => $request->pdf_title_1,
            'pdf_title_2' => $request->pdf_title_2,
        ]);

        if ($request->hasFile('photo')) {
            $photo = new Photo();
            $photo->path = file_store($request->photo, 'includes/asset/uploads/product/photos/', 'photo-');
            $item->photo()->save($photo);
        }
            if ($request->hasFile('pdf_fa')) {
                $item->pdf_fa = file_store($request->pdf_fa, 'includes/asset/uploads/product/pdf/', 'fa-');
                $item->save();
            }

        if ($request->hasFile('pdf_path_1')) {
            $item->pdf_path_1 = file_store($request->pdf_path_1, 'includes/asset/uploads/product/pdf/', 'pdf-');
            $item->save();
        }
        if ($request->hasFile('pdf_path_2')) {
            $item->pdf_path_2 = file_store($request->pdf_path_2, 'includes/asset/uploads/product/pdf/', 'pdf-');
            $item->save();
        }

        if ($request->hasFile('libraries')) {
            foreach ($request->libraries as $library) {
                $gallery = new Library();
                $gallery->path = file_store($library, 'includes/asset/uploads/product/libraries/', 'photo-');
                $item->library()->save($gallery);
            }
        }

            store_lang($request,'product',$item->id,['name','short_text','text','titleseo','keywordsseo','descriptionseo','text_masraf']);

        $activity = new Activity();
        $activity->user_id = Auth::user()->id;
        $activity->type = 'store';
        $activity->text = ' محصول : ' . '(' . $request->name . ')' . ' ثبت کرد';
        $item->activity()->save($activity);


        return redirect('admin/product-list')->with(['status' => 'success', "message" => 'محصول با موفقیت ثبت گردید']);
        //} catch (\Exception $e) {
        //    return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);
        //}
    }

    // Edit Function
    public function edit($id)
    {
        $item = Product::find($id);
        $cats = ProductCat::all();
        $items = ProductCategory::where('parent_id',0)->get();
        $types= ProductType::where('parent_id',0)->get();
        $brands = ProductBrand::all();
        return view('admin.product.edit', compact('item', 'cats','items','types','brands'), ['title' => $item->name]);
    }

    // Update Function
    public function update(Request $request, $id)
    {

     
        $item = Product::find($id);
        $old_title = $item->name;

        $validator = Validator::make($request->all(), [
            'category_id' =>'required',
            'brand_id' => 'required',
//            'zeneric_name' => 'required',
            'name' => 'required',
            'text' => 'required',
//            'code' => 'required',
//            'status' => 'required',
        ]);
        if(Product::where('slug',$request->slug)->where('id','!=',$item->id)->first())
        {
            return redirect()->back()->with([
                'status' => 'danger-600',
                "message" => 'عنوان تکراری می باشد'
            ])->withErrors($validator)->withInput();
        }
        if ($validator->fails()) {
            return redirect()->back()->with([
                'status' => 'danger - 600',
                "message" => 'لطفا فیلد ها را بررسی نمایید، بعضی از فیلد ها نمی توانند خالی باشند.',
            ])->withErrors($validator)->withInput();
        }

        try {
            $item->cat_id = $request->cat_id;
            $item->category_id = $request->category_id;
            $item->type_id = $request->type_id;
            $item->brand_id = $request->brand_id;
            $item->name = $request->name;
//            $item->zeneric_name = $request->zeneric_name;
            $item->slug = $request->slug;
            $item->slug_en = $request->slug_en;
            $item->text = $request->text;
//            $item->status = $request->status;
//            $item->code = $request->code;
            $item->short_text = $request->short_text;
            $item->titleseo = $request->titleseo;
            $item->keywordsseo = $request->keywordsseo;
            $item->descriptionseo = $request->descriptionseo;
            $item->text_masraf = $request->text_masraf;
            $item->practical_title_1 = $request->practical_title_1;
            $item->practical_text_1 = $request->practical_text_1;
            $item->practical_link_1 = $request->practical_link_1;
            $item->practical_title_2 = $request->practical_title_2;
            $item->practical_text_2 = $request->practical_text_2;
            $item->practical_link_2 = $request->practical_link_2;
            $item->practical_title_3 = $request->practical_title_3;
            $item->practical_text_3 = $request->practical_text_3;
            $item->practical_link_3 = $request->practical_link_3;
            $item->pdf_title_1 = $request->pdf_title_1;
            $item->pdf_title_2 = $request->pdf_title_2;

            if ($request->hasFile('photo')) {
                if ($item->photo) {
                    $old_path = $item->photo->path;
                    File::delete($old_path);
                    $item->photo->delete();
                }
                    $photo = new Photo();
                    $photo->path = file_store($request->photo, 'includes/asset/uploads/product/photos/', 'photo-');
                    $item->photo()->save($photo);
            }
            if ($request->hasFile('pdf_fa')) {
                if ($item->pdf_fa) {
                    $old_path = $item->pdf_fa;
                    File::delete($old_path);
                }
                $item->pdf_fa = file_store($request->pdf_fa, 'includes/asset/uploads/product/pdf/', 'fa-');
            }
            if ($request->hasFile('pdf_en')) {
                if ($item->pdf_en) {
                    $old_path = $item->pdf_en;
                    File::delete($old_path);
                }
                $item->pdf_en = file_store($request->pdf_en, 'includes/asset/uploads/product/pdf/', 'en-');
            }

            if ($request->hasFile('pdf_path_1')) {
                if ($item->pdf_path_1) {
                    $old_path = $item->pdf_path_1;
                    File::delete($old_path);
                }
                $item->pdf_path_1 = file_store($request->pdf_path_1, 'includes/asset/uploads/product/pdf/', 'pdf-');
            }
            if ($request->hasFile('pdf_path_2')) {
                if ($item->pdf_path_2) {
                    $old_path = $item->pdf_path_2;
                    File::delete($old_path);
                }
                $item->pdf_path_2 = file_store($request->pdf_path_2, 'includes/asset/uploads/product/pdf/', 'pdf-');
            }

            if ($request->hasFile('libraries')) {

                    foreach ($request->libraries as $library) {
                        $gallery = new Library();
                        $gallery->path = file_store($library, 'includes/asset/uploads/product/libraries/', 'photo-');
                        $item->library()->save($gallery);
                    }
            }
            $item->save();
            if($item->langs)
            {
                foreach ($item->langs as $lang){
                    $lang->delete();
                }
            }
            store_lang($request,'product',$item->id,['name','short_text','text','titleseo','keywordsseo','descriptionseo','text_masraf']);
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->type = 'update';
            $activity->text = ' محصول : ' . '(' . $old_title . ')' . ' ویرایش کرد ';
            $item->activity()->save($activity);
            return redirect('admin/product-list')->with(['status' => 'success', "message" => 'محصول با موفقیت ویرایش شد. ']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);
        }
    }

    // Index Function
    public function index()
    {
        $category = ProductCategory::all();
        $items = Product::get();
        return view('admin.product.index', compact('items', 'category'), ['title' => 'محصولات']);
    }

    public function search_product(Request $request)
    {
        $items = Product::orderBy('id','desc');
        $category = ProductCategory::all();
        if ($request->id) {
            $items = $items->where('id', $request->id);
        }
        if ($request->name) {
            $items = $items->where('name', 'LIKE', "%$request->name%");
        }
        if ($request->category_id) {
            $items = $items->where('category_id', $request->category_id);
        }
        $items = $items->get();
        return view('admin.product.index', compact('items', 'category'), ['title' => 'جستجو محصولات']);
    }

    // Destroy Function
    public function destroy($id)
    {
        $item = Product::find($id);
        $items = ProductAttr::where('product_id',$id)->get();
        $old_title = $item->name;
        try {
            foreach ($items as $itemss)
            {
                $itemss->delete();
            }
            $item->delete();
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->type = 'delete';
            $activity->text = ' محصول : ' . '(' . $old_title . ')' . ' حذف کرد';
            $item->activity()->save($activity);
            return redirect('admin/product-list')->with(['status' => 'success', "message" => 'محصول با موفقیت حذف شد']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);
        }
    }

    public function destroy_gallery($id)
    {
        try {
            $item = Library::find($id);
            $item->delete();
            return redirect()->back()->with(['status' => 'success', "message" => 'با موفقیت حذف شد ']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);
        }
    }

    // Active Function
    public function active($type,$id)
    {
        $item = Product::find($id);
        $old_name = $item->name;
        $activity = new Activity();
        try {
            if($item->$type=='active')
            {
                $item->$type='pending';
                $item->update();
                $activity->user_id = Auth::user()->id;
                $activity->type = 'update';
                $activity->text = ' محصول : ' . '(' . $old_name . ')' . ' را غیرفعال کرد';
                $item->activity()->save($activity);
                return redirect()->back()->with(['status' => 'success', "message" => ' با موفقیت غیرفعال شد.']);
            }
            else
            {
                $item->$type='active';
                $item->update();
                $activity->user_id = Auth::user()->id;
                $activity->type = 'update';
                $activity->text = ' محصول : ' . '(' . $old_name . ')' . ' را فعال کرد';
                $item->activity()->save($activity);
                return redirect()->back()->with(['status' => 'success', "message" => ' با موفقیت فعال شد.']);
            }

        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);
        }
    }
    // Index Function
    public function index_attr($id)
    {
        $items = ProductAttr::where('product_id',$id)->get();
        $item=Product::find($id);
        return view('admin.product.attr.index', compact('items', 'id'), ['title' => 'ویژگی محصول '.$item->name]);
    }

    // Store Function
    public function store_attr(Request $request,$id)
    {

        $validator = Validator::make($request->all(), [

            'title' => 'required',
            'value' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with([
                'status' => 'danger-600',
                "message" => 'لطفا فیلد ها را بررسی نمایید، بعضی از فیلد ها نمی توانند خالی باشند.',
            ])->withErrors($validator)->withInput();
        }
        try {
            $item = ProductAttr::create([
                'product_id' => $id,
                'title' => $request->title,
                'value' => $request->value,
            ]);

            store_lang($request,'p_attr',$item->id,['title','value']);

            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->type = 'store';
            $activity->text = ' ویژگی محصول : ' . '(' . $request->title . ')' . ' ثبت کرد';
            $item->activity()->save($activity);


            return redirect()->back()->with(['status' => 'success', "message" => 'ویژگی محصول با موفقیت ثبت گردید']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);
        }
    }

    public function destroy_attr($id)
    {
        $item = ProductAttr::find($id);
        $old_title = $item->title;
        try {
            $item->delete();
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->type = 'delete';
            $activity->text = ' ویژگی محصول : ' . '(' . $old_title . ')' . ' حذف کرد';
            $item->activity()->save($activity);
            return redirect()->back()->with(['status' => 'success', "message" => 'ویژگی محصول با موفقیت حذف شد']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);
        }
    }
}
