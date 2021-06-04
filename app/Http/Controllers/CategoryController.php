<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Validator;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = Category::orderBy('created_at','DESC')->get();
        return $list;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'video' => 'required|video|mimes:mp4,mov,ogg,qt | max:200000',
        ]);
        if ($validate->fails()) {
            return response()->json(["status" => false, "error" => $validate->errors()], 400);
        }
        $image = $request->file('image')->store('public/category_images');
        $logo = $request->file('logo')->store('public/category_images');
        $video = $request->file('video')->store('public/category_images');
        $cate = new Category([
            'name' => $request->name,
            'image' => str_replace("public", "storage", $image),
            'logo' => str_replace("public", "storage", $logo),
            'video' => str_replace("public", "storage", $video),
        ]);
        $cate->save();
        return response()->json(["status" => true, "data" =>  $cate], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $list = Product::where('cate_id',$id)->get();
        $cate = Category::where('id',$id)->first();
        $data = [];
        $listP = new \stdClass();
        $listP->id = $cate->id;
        $listP->name=$cate->name;
        $listP->logo=$cate->logo;
        $listP->video=$cate->video;
        $listP->image=$cate->image;
        $listP->product = $list;
        array_push($data, $listP);
        return $data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'video' => 'required|image|mimes:mp4,mov,ogg,qt | max:200000',
        ]);
        if ($validate->fails()) {
            return response()->json(["status" => false, "error" => $validate->errors()], 400);
        }
        $image = $request->file('image')->store('public/category_images');
        $logo = $request->file('logo')->store('public/category_images');
        $video = $request->file('video')->store('public/category_images');
        $category['image'] = str_replace("public", "storage", $image);
        $category['logo'] = str_replace("public", "storage", $logo);
        $category['video'] = str_replace("public", "storage", $video);
        $category->update($request->except('image','logo','video'));
        return response()->json(["status" => true, "data" => $request->all()], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
