@extends('layouts.main')
@section('title', 'Create event')
@section('content')

<div id="event-create-container" class="col-md-6 offset-md-3">
    <h1>Crie o seu evento</h1>
    <form action="{{$url}}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($event))
            @method('PUT')
        @endif
        <div class="form-group">
            @php
                $sel='';
                $textBt = 'Criar Evento';
                if(isset($event)){
                    $date = $event->date->format('Y-m-d');
                    $event->items;
                    $img = "/img/events/".$event->image;
                    if($event->private==1){
                        $sel="selected='selected'";
                    }
                    $textBt = 'Editar Evento';
                }else{
                    $img='';
                    $date='';
                }
            @endphp
            <label for="image">Evento:</label>
            @if($img!='')
                <img src="{{$img}}" alt="{{ $event->title }}" style="width:100px;">
            @endif
            <input type="file" name="image" id="image" class="form-control-file" placeholder="Nome do evento">
        </div>
        <div class="form-group">
            <label for="title">Evento:</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Nome do evento" value="{{ $event->title ?? '' }}">
        </div>
        <div class="form-group">
            <label for="date">Data do evento:</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ $date }}">
        </div>
        <div class="form-group">
            <label for="cite">Cidade:</label>
            <input type="text" name="city" id="city" class="form-control" placeholder="Local do evento" value="{{ $event->city ?? '' }}">
        </div>
        <div class="form-group">
            <label for="private">O evento é privado?</label>
            <select name="private" id="private" class="form-control">
                <option value="0">Não</option>
                <option value="1" {{$sel}}>Sim</option>
            </select>
        </div>
        <div class="form-group">
            <label for="description">Descrição:</label>
            <textArea name="description" id="description" class="form-control" placeholder="o que vai acontecer no evento?">{{ $event->description ?? '' }}</textArea>
        </div>
        <div class="form-group">
            <label for="items">Adicione itens de infraestrutura:</label>
            <div class="form-group">
                <input type="checkbox" name="items[]" id="items" value="Cadeiras"> Cadeiras
            </div>
            <div class="form-group">
                <input type="checkbox" name="items[]" id="items" value="Palco"> Palco
            </div>
            <div class="form-group">
                <input type="checkbox" name="items[]" id="items" value="Cerveja grátis"> Cerveja grátis
            </div>
            <div class="form-group">
                <input type="checkbox" name="items[]" id="items" value="Open Food"> Open Food
            </div>
            <div class="form-group">
                <input type="checkbox" name="items[]" id="items" value="Brindes"> Brindes
            </div>
            <div class="form-group">
                <input type="checkbox" name="items[]" id="items" value="Sorteio"> Sorteio
            </div>
        </div>

        <input type="submit" value="{{ $textBt }}" class="btn btn-primary">
    </form>
</div>

@endsection
