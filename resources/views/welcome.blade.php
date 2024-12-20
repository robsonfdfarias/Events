@extends('layouts.main')
@section("title", "Título do main")
@section("js", "/js/js-welcome.js")
@section("content")
    <div id="search-container" class="col-md-12">
        <h1>Busque um evento</h1>
        <form action="/" method="GET" style="display:flex;">
            <input type="text" name="search" id="search" class="form-control rad" placeholder="Digite sua pesquisa">
            <!-- <input type="submit" value="Pesquisar" class="btn btn-primary rad2" alt="Pesquisar"> -->
            <button class="btn btn-primary rad2" alt="Pesquisar">
                <i class="fa-solid fa-magnifying-glass" style="color:white"></i>
             <span>Pesquisar</span></button>
        </form>
    </div>
    <div id="events-container" class="col-md-12">
        @if($search)
            <h2>Você pesquisou por: <span style="color:#F2A340;">{{ $search }}</span></h2>
        @else
            <h2>Próximos eventos</h2>
            <p class="subtitle">Veja os eventos dos próximos dias</p>
        @endif
        <div id="cards-container" class="row">
            @foreach($events as $event)
                <div class="card col-md-3">
                    <img src="/img/events/{{$event->image}}" alt="{{ $event->title }}" title="{{ $event->title }}">
                    <div class="content-container">
                        <div class="card-date">{{ $event->date->format("d/m/Y") }}</div>
                        <h5 class="card-title">{{ $event->title }}</h5>
                        <p class="card-participants">{{ count($event->users) }} participantes</p>
                        <a href="/events/{{ $event->id }}" class="btn btn-primary">Saber mais</a>
                    </div>
                </div>
            @endforeach
            @if(count($events) == 0)
                <p>Não há eventos disponíveis</p>
            @endif
        </div>
    </div>
    <!-- Busca = {{$busca ?? 'Nada'}} -->
@endsection
