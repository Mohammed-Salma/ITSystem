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
        $blogcat = BlogCategory::latest()->get();
        return view('admin.backend.post.add_post', compact('blogcat'));
    }
    // End Method

    public function StoreBlogPost(Request $request)
    {

        if ($request->file('image')) {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver()); // Install intervention/image first

            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(746, 500)->save('upload/post/' . $name_gen);
            $save_url = 'upload/post/' . $name_gen;

            BlogPost::create([
                'blogcat_id' => $request->blogcat_id,
                'post_title' => $request->post_title,
                'post_slug' => strtolower(str_replace(' ', '-', $request->post_title)),
                'long_description' => $request->long_description,
                'image' => $save_url,
            ]);
        }

        $notification = array(
            'message' => 'Blog Post Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.blog.post')->with($notification);
    }
    //End Method

    public function EditBlogPost($id)
    {
        $post = BlogPost::find($id);
        $blogcat = BlogCategory::latest()->get();
        return view('admin.backend.post.edit_post', compact('post', 'blogcat'));
    }
    //End Method

    public function UpdateBlogPost(Request $request)
    {
        $post_id = $request->id;

        if ($request->file('image')) {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver()); // Install intervention/image first
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(746, 500)->save('upload/post/' . $name_gen);
            $save_url = 'upload/post/' . $name_gen;

            BlogPost::find($post_id)->update([
                'blogcat_id' => $request->blogcat_id,
                'post_title' => $request->post_title,
                'post_slug' => strtolower(str_replace(' ', '-', $request->post_title)),
                'long_description' => $request->long_description,
                'image' => $save_url,
            ]);

            $notification = array(
                'message' => 'Blog Post Updated With image Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.blog.post')->with($notification);
        } else {
            BlogPost::find($post_id)->update([
                'blogcat_id' => $request->blogcat_id,
                'post_title' => $request->post_title,
                'post_slug' => strtolower(str_replace(' ', '-', $request->post_title)),
                'long_description' => $request->long_description,
            ]);
            $notification = array(
                'message' => 'Blog Post Updated Without image Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.blog.post')->with($notification);
        }
    }
    //End Method


}
