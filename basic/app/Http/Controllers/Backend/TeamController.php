<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TeamController extends Controller
{
    public function AllTeam()
    {
        $team = Team::latest()->get();
        return view('admin.backend.team.all_team', compact('team'));
    }
    //End Method

    public function AddTeam()
    {
        return view('admin.backend.team.add_team');
    }
    //End Method

    public function StoreTeam(Request $request)
    {

        if ($request->file('image')) {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver()); // Install intervention/image first
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(306, 400)->save(public_path('upload/team/' . $name_gen));
            $save_url = 'upload/team/' . $name_gen;

            Team::create([
                'name' => $request->name,
                'position' => $request->position,
                'image' => $save_url,
            ]);
        }

        $notification = array(
            'message' => 'Team Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.team')->with($notification);
    }
    //End Method

    public function EditTeam($id)
    {
        $team = Team::find($id);
        return view('admin.backend.team.edit_team', compact('team'));
    }
    //End Method

    public function UpdateTeam(Request $request)
    {
        $team_id = $request->id;
        $team = Team::find($team_id);


        if ($request->file('image')) {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver()); // Install intervention/image first

            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(306, 400)->save('upload/team/' . $name_gen);
            $save_url = 'upload/team/' . $name_gen;

            //to remove and change old image
            if (file_exists(public_path($team->image))) {
                unlink(public_path($team->image));
            }

            Team::find($team_id)->update([
                'name' => $request->name,
                'position' => $request->position,
                'image' => $save_url,
            ]);
            $notification = array(
                'message' => 'Team Updated With image Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.team')->with($notification);
        } else {
            Team::find($team_id)->update([
                'name' => $request->name,
                'position' => $request->position,
            ]);
            $notification = array(
                'message' => 'Team Updated Without image Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.team')->with($notification);
        }
    }
    //End Method

    public function DeleteTeam($id)
    {
        $team = Team::find($id);
        $img = $team->image;
        unlink($img);

        Team::find($id)->delete();

        $notification = array(
            'message' => 'Team Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
    //End Method
}
