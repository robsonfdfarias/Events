<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index(){
        $events = Event::all();
        return view('welcome', ["events"=>$events]);
    }
    public function create(){
        return view('events.create');
    }
    public function store(Request $request){
        $events = new Event;
        $events->title = $request->title;
        $events->city = $request->city;
        $events->private = $request->private;
        $events->description = $request->description;
        $events->items = $request->items;
        $events->date = $request->date;

        // Image upload
        if($request->hasFile('image') && $request->file('image')->isValid()){
            $image = $request->image;
            $extension = $image->extension();
            $imageName = md5($image->getClientOriginalName().strtotime("now")).'.'.$extension;
            $image->move(public_path('img/events'), $imageName);
            $events->image = $imageName;
        }

        $result = $events->save();

        return redirect('/')->with('res', ''.$result);
    }

    public function show($id){
        $event = Event::findOrFail($id);
        return view('events.show', ['event'=>$event]);
    }
}
