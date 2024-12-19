@extends('layouts.main')
@section("title", "Título do main")
@section("js", "/js/js-welcome.js")
@section("content")

<div class="col-md-10 offset-md-1 dashboard-title-container">
    <h1>Meus Eventos</h1>
</div>
<div class="col-md-10 offset-md-1 dashboard-events-container">
    @if(count($events)>0)
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Participantes</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                    <tr>
                        <td scropt="row">{{ $loop->index+1 }}</td>
                        <td scropt="row"><a href="/events/{{ $event->id }}">{{ $event->title }}</a></td>
                        <td scropt="row">0</td>
                        <td scropt="row" class="tdActions">
                            <a href="/events/edit/{{$event->id}}" class="btn btn-info edit-btn color-text"><i class="fa-solid fa-pen-to-square"></i> Editar</a>
                            <form action="/events/{{ $event->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger delete-btn color-text"><i class="fa-solid fa-trash-can"></i> Deletar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Você ainda não tem eventos, <a href="/events/create">Criar evento</a></p>
    @endif
</div>

@endsection