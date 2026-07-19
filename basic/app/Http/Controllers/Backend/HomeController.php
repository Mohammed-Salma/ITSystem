<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Clarifi;
use App\Models\Connect;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\App;
use App\Models\Usability;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class HomeController extends Controller
{
    public function AllFeature()
    {
        $feature = Feature::latest()->get();
        return view('admin.backend.feature.all_feature', compact('feature'));
    }
    //End Method
    public function AddFeature()
    {
        return view('admin.backend.feature.add_feature');
    }
    //End Method

    public function StoreFeature(Request $request)
    {
        Feature::create([
            'title' => $request->title,
            'icon' => $request->icon,
            'description' => $request->description,
        ]);

        $notification = array(
            'message' => 'Feature Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.feature')->with($notification);
    }
    //End Method

    public function EditFeature($id)
    {
        $feature = Feature::find($id);
        return view('admin.backend.feature.edit_feature', compact('feature'));
    }
    //End Method

    public function UpdateFeature(Request $request)
    {
        $feature_id = $request->id;
        Feature::find($feature_id)->update([
            'title' => $request->title,
            'icon' => $request->icon,
            'description' => $request->description,
        ]);

        $notification = array(
            'message' => 'Feature Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.feature')->with($notification);
    }
    //End Method

    public function DeleteFeature($id)
    {

        Feature::find($id)->delete();

        $notification = array(
            'message' => 'Feature Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
    //End Method

    public function GetClarifies()
    {
        $clarifi = Clarifi::find(1);
        return view('admin.backend.clarifi.get_clarifi', compact('clarifi'));
    }
    //End Method


    public function UpdateClarifies(Request $request)
    {
        $clarifi_id = $request->id;
        $clarifi = Clarifi::find($clarifi_id);

        if ($request->file('image')) {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver()); // Install intervention/image first

            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(302, 618)->save('upload/clarifi/' . $name_gen);
            $save_url = 'upload/clarifi/' . $name_gen;

            //Delete Old Image
            if (file_exists(public_path($clarifi->image))) {
                @unlink(public_path($clarifi->image));
            }

            Clarifi::find($clarifi_id)->update([
                'title' => $request->title,
                'description' => $request->description,
                'image' => $save_url,
            ]);
            $notification = array(
                'message' => 'Clarifi Updated With image Successfully',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        } else {
            Clarifi::find($clarifi_id)->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);
            $notification = array(
                'message' => 'Clarifi Updated Without image Successfully',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        }
    }
    //End Method

    public function GetUsability()
    {
        $usability = Usability::find(1);
        return view('admin.backend.usability.get_usability', compact('usability'));
    }
    //End Method



    public function UpdateUsability(Request $request)
    {
        $usability_id = $request->id;
        $usability = Usability::find($usability_id);

        if ($request->file('image')) {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver()); // Install intervention/image first

            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(560, 400)->save('upload/usability/' . $name_gen);
            $save_url = 'upload/usability/' . $name_gen;

            //Delete Old Image
            if (file_exists(public_path($usability->image))) {
                @unlink(public_path($usability->image));
            }

            Usability::find($usability_id)->update([
                'title' => $request->title,
                'description' => $request->description,
                'youtube' => $request->youtube,
                'link' => $request->link,
                'image' => $save_url,
            ]);
            $notification = array(
                'message' => 'Usability Updated With image Successfully',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        } else {
            Usability::find($usability_id)->update([
                'title' => $request->title,
                'description' => $request->description,
                'youtube' => $request->youtube,
                'link' => $request->link,
            ]);
            $notification = array(
                'message' => 'Usability Updated Without image Successfully',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        }
    }
    //End Method

    public function AllConnect()
    {
        $connect = Connect::latest()->get();
        return view('admin.backend.connect.all_connect', compact('connect'));
    }
    //End Method

    public function AddConnect()
    {
        return view('admin.backend.connect.add_connect');
    }
    //End Method

    public function StoreConnect(Request $request)
    {
        Connect::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        $notification = array(
            'message' => 'Connect Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.connect')->with($notification);
    }
    //End Method

    public function EditConnect($id)
    {
        $connect = Connect::find($id);
        return view('admin.backend.connect.edit_connect', compact('connect'));
    }
    //End Method

    public function UpdateConnect(Request $request)
    {
        $connect_id = $request->id;
        Connect::find($connect_id)->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        $notification = array(
            'message' => 'Connect Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.connect')->with($notification);
    }
    //End Method

    public function DeleteConnect($id)
    {

        Connect::find($id)->delete();

        $notification = array(
            'message' => 'Connect Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
    //End Method

    public function UpdateConnect2(Request $request, $id)
    {
        $connect = Connect::findOrFail($id);
        $connect->update($request->only(['title', 'description']));

        return response()->json([
            'success' => true,
            'message' => 'Connect updated successfully!',
        ]);
    }
    //End Method

    public function AllFaqs()
    {
        $faqs = Faq::latest()->get();
        return view('admin.backend.faqs.all_faqs', compact('faqs'));
    }
    //End Method

    public function AddFaqs()
    {
        return view('admin.backend.faqs.add_faqs');
    }
    //End Method


    public function StoreFaqs(Request $request)
    {
        Faq::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        $notification = array(
            'message' => 'Faqs Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.faqs')->with($notification);
    }
    //End Method

    public function EditFaqs($id)
    {
        $faqs = Faq::find($id);
        return view('admin.backend.faqs.edit_faqs', compact('faqs'));
    }
    //End Method

    public function UpdateFaqs(Request $request)
    {
        $faqs_id = $request->id;
        Faq::find($faqs_id)->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);
        $notification = array(
            'message' => 'Faqs Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.faqs')->with($notification);
    }
    //End Method

    public function DeleteFaqs($id)
    {
        Faq::find($id)->delete();
        $notification = array(
            'message' => 'Faqs Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
    //End Method

    public function UpdateApps(Request $request, $id)
    {
        $apps = App::findOrFail($id);
        $apps->update($request->only(['title', 'description']));

        return response()->json([
            'success' => true,
            'message' => 'Apps updated successfully!',
        ]);
    }
    //End Method


}
