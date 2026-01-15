<?php

namespace App\Models;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function courses(){
        return $this->hasMany(Course::class);
    }

    public function bundles(){
        return $this->hasMany(Bundle::class);
    }

    public function blogs(){
        return $this->hasMany(Blog::class);
    }

    public function faqs(){
        return $this->hasMany(Faq::class);
    }

    public function getFinalCatList(){

        $cats = Category::where('status','1')->get();

        $endCats = [];

        foreach($cats as $cat){

            $find = Category::where("parent",$cat->id)->first();
            if(!$find){
                $endCats[] = $cat;
            }
        }

        $list = $this->findParentCat($endCats[4]->id);
       



    }

    public function findParentCat($id){

         $course = Category::find($id);
                
                if( $course){
                $cat = Category::find($course->parent);
                if($cat){
                if($course->board_id==0){
          return
               $cat->name." > ".$course->name;
                }else{

                    $acCat = Category::where('is_board','1')->first();

                    $board = Board::find($course->board_id);
                    if($board){
                        if($acCat->id!=$cat->id){
                  return $acCat->name." > ".$board->name." > ". $cat->name." > ".$course->name;  
              }else{
                 return $acCat->name." > ".$board->name." > ".$course->name; 
              }
              }else{
                return $acCat->name." > ". $cat->name." > ".$course->name; 
              }
                }
            }else{
                return $course->name;
            }
                }else{
                    return "";
                }




    }


}
