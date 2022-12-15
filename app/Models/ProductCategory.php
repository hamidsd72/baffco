<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{

    public $timestamps = false;

    protected $parent = 'parent_id';

    protected $guarded = ['id'];

    public function langs()
    {
        return $this->hasMany('App\Models\Lang','item_id')->where('type','product_category');
    }
    public function parents()
    {
        return $this->belongsTo('App\ProductCategory', 'parent_id');
    }

    public function parent()
    {
        return $this->hasOne('App\ProductCategory','id', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\ProductCategory', 'parent_id');
    }

    public function children_order_menu()
    {
        return $this->hasMany('App\ProductCategory', 'parent_id')->orderBy('sort_menu');
    }

    public function children_orderBy()
    {
        return $this->hasMany('App\ProductCategory', 'parent_id')->orderBy('sort_id');
    }

    public function products()
    {
        return $this->hasMany('App\Product', 'category_id', 'id')->with(['category','photo']);
    }
    public function doctors()
    {
        return $this->hasMany('App\Models\ProductDoctor', 'category_id', 'id');
    }
    public function products_g()
    {
        return $this->hasMany('App\Product', 'category_id', 'id')->where('cat_id',1);
    }
    public function products_s()
    {
        return $this->hasMany('App\Product', 'category_id', 'id')->where('cat_id',2);
    }
    public function photo()
    {
        return $this->morphOne('App\Photo', 'pictures');
    }

    public function activity()
    {
        return $this->morphOne('App\Activity', 'activities');
    }

    public function visitor()
    {

        return $this->hasMany('App\Visitor', 'cat_id' , 'id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($item) {
            $item->children()->get()
                ->each(function ($children) {
                    $children->delete();
                });
            foreach ($item->langs as $lang){
                $lang->delete();
            }
        });
    }

}
