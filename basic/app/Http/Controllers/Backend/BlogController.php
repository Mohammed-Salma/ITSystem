<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BlogController extends Controller
{
    public function BlogCategory()
    {
        $category = BlogCategory::latest()->get();
        return view('admin.backend.blogcategory.blog_category', compact('category'));
    }
    // End Method

    public function StoreBlogCategory(Request $request)
    {
        BlogCategory::insert([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
        ]);

        $notification = array(
            'message' => 'Blog Category Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
    // End Method

    public function EditBlogCategory($id)
    {
        $category = BlogCategory::find($id);
        return response()->json($category);
    }
    // End Method

    public function UpdateBlogCategory(Request $request)
    {
        $cat_id = $request->cat_id;
        BlogCategory::find($cat_id)->update([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
        ]);

        $notification = array(
            'message' => 'Blog Category Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
    // End Method

    public function DeleteBlogCategory($id)
    {
        BlogCategory::find($id)->delete();

        $notification = array(
            'message' => 'Blog Category Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
    // End Method

    public function AllBlogPost()
    {
        $post = BlogPost::latest()->get();
        return view('admin.backend.post.all_post', compact('post'));
    }
    // End Method

    public function AddBlogPost()
    {
        return view('admin.backend.blog.add_blog_post');
    }
    // End Method

}
