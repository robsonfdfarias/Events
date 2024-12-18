@extends('layouts.main')
@section("title", "Título do main")
@section("js", "/js/js-welcome.js")
@section("content")
    <img src="/img/eventos-principal.jpg" alt="Banner eventos">
    <h1>Principal...</h1>
    Nome: {{ $nome }}
    <ul>
    @foreach($nomes as $nome)
    <li>{{ $nome }} - {{ $loop->iteration }}</li>
    @endforeach
    </ul>
    id = {{$id ?? ''}}
    Busca = {{$busca ?? 'Nada'}}
@endsection
