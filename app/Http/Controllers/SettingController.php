<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    //change randomize to 1 or 0
    public function changeRandomize(Request $request)
    {
        $setting = Setting::first();
        if ($setting) {
            $setting->randomize = $request->input('randomize');
            $setting->save();
            return response()->json(['message' => 'Setting updated successfully']);
        } else {
            //if not found, create a new setting
            $setting = new Setting();
            $setting->randomize = $request->input('randomize');
            $setting->save();
            return response()->json(['message' => 'Setting created successfully']);
        }
    }

    //get the setting
    public function index()
    {
        $setting = Setting::first();
        if ($setting) {
            return response()->json($setting);
        } else {
            return response()->json(['message' => 'Setting not found'], 404);
        }
    }
}
