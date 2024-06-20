<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class WebsiteController extends Controller
{
    // app/Http/Controllers/BoardController.php
    public function privacy(){
        $title = 'Privacy Policy';
        $bread_curm = 'privacy-policy';

        $setting = Setting::where('setting_option', 'pages')->first();

        $item = json_decode($setting->value);
        $page = $item->privacy_policy;

        return view('backend.pages')->with(['title'=>$title, 'bread_curm'=>$bread_curm, 'page'=> $page]);
    }

    public function term_condition(){
        $title = 'Term & Condition';
        $bread_curm = 'term-condition';

        $setting = Setting::where('setting_option', 'pages')->first();

        $item = json_decode($setting->value);
        $page = $item->term_condition;

        return view('backend.pages')->with(['title'=>$title, 'bread_curm'=>$bread_curm, 'page'=> $page]);
    }

    public function support(){
        $title = 'Support';
        $bread_curm = 'support';

        $setting = Setting::where('setting_option', 'pages')->first();

        $item = json_decode($setting->value);
        $page = $item->Support;

        return view('backend.pages')->with(['title'=>$title, 'bread_curm'=>$bread_curm, 'page'=> $page]);
    }

    public function download_sample($name){
        return $this->generateSampleFile($name);
    }

}
