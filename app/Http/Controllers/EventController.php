<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;

class EventController extends Controller
{
    public function index(){
        $search = request('search');
        if($search){
            $events = Event::where([
                ['title', 'like', '%'.$search.'%']
            ])->get();
        }else{
            $events = Event::all();
        }

        return view('welcome', ["events"=>$events, "search"=>$search]);
    }

    public function create(){
        return view('events.create', ['url'=>'/events']);
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

        $user = auth()->user();

        $events->user_id = $user->id;

        $result = $events->save();
        if($result){
            $msg = 'Evento salvo com sucesso!';
        }else{
            $msg = 'Não foi possível salvar o evento!';
        }

        return redirect('/')->with([
            'res'=>$result,
            'msg'=>$msg
        ]);
    }

    public function show($id){
        $event = Event::findOrFail($id);
        $eventOwner = User::where([
            ['id', $event->user_id]
        ])->first()->toArray();
        return view('events.show', ['event'=>$event, 'eventOwner'=>$eventOwner]);
    }

    public function dashboard(){
        $user = auth()->user();
        $events = $user->events;
        return view('events.dashboard', ['events'=>$events]);
    }

    public function destroy($id){
        $res = Event::findOrFail($id)->delete();
        return redirect('/dashboard')->with([
            'msg'=>'Evento excluído com sucesso!',
            'res'=>$res
        ]);
    }

    public function edit($id){
        $event = Event::findOrFail($id);
        return view('events.create', ['event'=>$event, 'url'=>'/events/update/'.$id]);
    }

    public function update(Request $request){
        $data = $request->all();
        // Image upload
        if($request->hasFile('image') && $request->file('image')->isValid()){
            $image = $request->image;
            $extension = $image->extension();
            $imageName = md5($image->getClientOriginalName().strtotime("now")).'.'.$extension;
            $image->move(public_path('img/events'), $imageName);
            $data['image'] = $imageName;
        }
        $res = Event::findOrFail($request->id)->update($data);
        return redirect('/dashboard')->with([
            'msg'=>'Evento Editado com sucesso!',
            'res'=>$res
        ]);
    }
}
