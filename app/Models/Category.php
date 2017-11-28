<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'parent_id','name', 'desc',
    ];
    
    /*无限极分类，可转为json和array
    第一种
    $categorys =Category::with('allChildrenCategorys')->first()->toArray();   
    第二种
    $categorys=Category::find(1);
    $categorys->allChildrenCategorys;
    第三种
`   $categorys->allChildrenCategorys->first()->allChildrenCategorys; 
    */
    
    public function childCategory() {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }
    public function allChildrenCategorys()
    {
        return $this->childCategory()->with('allChildrenCategorys');
    }
}
