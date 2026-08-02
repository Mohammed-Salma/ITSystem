<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\About;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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
}
