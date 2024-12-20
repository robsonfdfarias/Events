@extends('layouts.main')
@section("title", "Título do main")
@section("js", "/js/js-welcome.js")
@section("content")
    <div class="col_md-10 offset-md-1">
        <div class="row">
            <div id="image-container" class="col-md-6">
                <img src="/img/events/{{ $event->image }}" class="img-fluid" alt="{{ $event->title }}">
            </div>
            <div id="info-container" class="col-md-6">
                <h1>{{ $event->title }}</h1>
                <p class="events-city">
                    <!-- <ion-icon name="location-outline"></ion-icon> -->
                    <i class="fa-solid fa-map-location-dot"></i>
                     {{ $event->city }}
                </p>
                <p class="events-participants">
                    <!-- <ion-icon name="people-outline"></ion-icon> -->
                    <i class="fa-solid fa-users"></i>
                     {{ count($event->users) }} participantes
                </p>
                <p class="events-owner">
                    <!-- <ion-icon name="star-outline"></ion-icon> -->
                    <i class="fa-solid fa-star"></i>
                     {{ $eventOwner['name'] }}
                </p>

                @if($registered)
                    <div>Você já está inscrito neste evento</div>
                    <form action="/events/leave/{{ $event->id }}" method="POST" class="tdActions">
                        @csrf
                        @method('DELETE')
                        <a href="/events/leave/{{ $event->id }}"
                            onclick="event.preventDefault();
                            this.closest('form').submit()"
                            class="btn btn-danger delete-btn color-text">
                            <i class="fa-solid fa-trash-can"></i>
                            Sair do evento
                        </a>
                    </form>
                @else
                    <form action="/events/join/{{ $event->id }}" method="POST">
                        @csrf
                        <a href="/events/join/{{ $event->id }}"
                            class="btn btn-primary"
                            onclick="event.preventDefault();
                            this.closest('form').submit()"
                            id="event-submit">
                            Confirmar Presença
                        </a>
                    </form>
                @endif
                <h3>O evento conta com:</h3>
                <ul id="items-list">
                    @foreach($event->items as $item)
                    <li>
                        <!-- <ion-icon name="play-outline"></ion-icon> -->
                        <i class="fa-regular fa-square-check"></i>
                         <span>{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-12" id="description-container">
                <h3>Sobre o Evento:</h3>
                <p class="event-description">{{ $event->description }}</p>
            </div>
        </div>
    </div>
@endsection
