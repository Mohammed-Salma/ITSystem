<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class FrontendController extends Controller
{
    public function OurTeam()
    {
        return view('home.team.team_page');
    }
    //End Method

    public function AboutUs()
    {
        return view('home.about.about_us');
    }
    //End Method

    public function GetAboutUs()
    {
        $about = About::find(1);
        return view('admin.backend.about.get_about', compact('about'));
    }
    //End Method

    public function UpdateAboutUs(Request $request)
    {
        $about_id = $request->id;
        $about = About::find($about_id);

        if ($request->file('image')) {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver()); // Install intervention/image first

            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(526, 550)->save('upload/about/' . $name_gen);
            $save_url = 'upload/about/' . $name_gen;

            //Delete Old Image
            if (file_exists(public_path($about->image))) {
                @unlink(public_path($about->image));
            }

            About::find($about_id)->update([
                'title' => $request->title,
                'description' => $request->description,
                'image' => $save_url,
            ]);
            $notification = array(
                'message' => 'About Us Updated With image Successfully',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        } else {
            About::find($about_id)->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);
            $notification = array(
                'message' => 'About Us Updated Without image Successfully',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        }
    }
    //End Method

    public function BlogPage()
    {
        $blogcat = BlogCategory::latest()->withCount('posts')->get();
        $post = BlogPost::latest()->limit(5)->get();
        $recentpost = BlogPost::latest()->limit(3)->get();
        return view('home.blog.list_blog', compact('blogcat', 'post', 'recentpost'));
    }
    //End Method

    public function BlogDetails($slug)
    {
        $blog = BlogPost::where('post_slug', $slug)->first();
        $blogcat = BlogCategory::latest()->withCount('posts')->get();
        $recentpost = BlogPost::latest()->limit(3)->get();
        return view('home.blog.blog_details', compact('blog', 'blogcat', 'recentpost'));
    }
    //End Method


}
