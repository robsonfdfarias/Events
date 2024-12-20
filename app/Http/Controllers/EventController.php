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
            'status'=>true,
            'res'=>$result,
            'msg'=>$msg
        ]);
    }

    public function show($id){
        $event = Event::findOrFail($id);
        $eventOwner = User::where([
            ['id', $event->user_id]
        ])->first()->toArray();
        $currentUser = auth()->user();
        $registered = false;
        $users = $event->users;
        foreach($users as $user){
            if($user->id == $currentUser->id){
                $registered = true;
            }
        }
        return view('events.show', ['event'=>$event, 'eventOwner'=>$eventOwner, 'registered'=>$registered]);
    }

    public function dashboard(){
        $user = auth()->user();
        $events = $user->events;
        $eventAsParticipant = $user->eventAsParticipant;
        return view('events.dashboard', [
            'events'=>$events,
            'eventsAsParticipant'=>$eventAsParticipant
        ]);
    }

    public function destroy($id){
        $event = Event::findOrFail($id);
        $user = auth()->user();
        if($event->user_id != $user->id){
            return redirect('/dashboard')->with([
                'status'=>true,
                'msg'=>'Você não é o dono do evento '.$event->title.'. Sua tentativa de deletar foi registrada.',
                'res'=>0
            ]);
        }
        //Verifica se a imagem existe
        if(file_exists('img/events/'.$event->image)){
            //verifica se foi possível apagar a imagem
            if(unlink('img/events/'.$event->image)){
                //Imagem deletada com sucesso
            }
        }
        $res = Event::findOrFail($id)->delete();
        return redirect('/dashboard')->with([
            'status'=>true,
            'msg'=>'Evento excluído com sucesso!',
            'res'=>$res
        ]);
    }

    public function edit($id){
        $event = Event::findOrFail($id);
        $user = auth()->user();
        if($event->user_id != $user->id){
            return redirect('/dashboard')->with([
                'status'=>true,
                'msg'=>'Você não é o dono do evento '.$event->title,
                'res'=>1
            ]);
        }
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
            'status'=>true,
            'msg'=>'Evento Editado com sucesso!',
            'res'=>$res
        ]);
    }

    public function joinEvent($id){
        $user = auth()->user();
        $event = Event::findOrFail($id);
        $registrations = $event->users;
        foreach($registrations as $enrollment){
            //Verifica se o usuário está inscrito no evento, se estiver,
            //redireciona o mesmo para o dashboard avisando que ele já estava inscrito
            if($enrollment->id == $user->id){
                return redirect('/dashboard')->with([
                    'status'=>true,
                    'msg'=>'Você já está inscrito no evento '.$event->title,
                    'res'=>0
                ]);
                break;
            }
        }
        //Registra a participação do usuário no evento
        $res = $user->eventAsParticipant()->attach($id);
        return redirect('/dashboard')->with([
            'status'=>true,
            'msg'=>'Sua presença está confirmada no evento '.$event->title,
            'res'=>$res
        ]);
    }

    public function leaveEvent($id){
        $user = auth()->user();
        $event = Event::findOrFail($id);
        $registrations = $event->users;
        $registered = false;
        foreach($registrations as $enrollment){
            //Testa para saber se o usuário está inscrito no evento
            if($enrollment->id == $user->id){
                $registered = true;
            }
        }
        //Verifica se ele não está inscrito, se não estiver, ele redireciona informando que ele não está inscrito para sair.
        if(!$registered){
            return redirect('/dashboard')->with([
                'status'=>true,
                'msg'=>'Você já não está inscrito no evento '.$event->title,
                'res'=>0
            ]);
        }
        //Remove a inscrição do usuário no evento
        $res = $user->eventAsParticipant()->detach($id);
        return redirect('/dashboard')->with([
            'status'=>true,
            'msg'=>'Você removeu sua inscrição do Evento '.$event->title.'. Você ainda pode se inscrever novamente.',
            'res'=>$res
        ]);
    }
}
