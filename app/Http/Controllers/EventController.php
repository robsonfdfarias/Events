<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(){
        $busca = request('search');
        $ar = ["Robson", "Tony", "Camila", "Anderson", "André", "Paulo", "Juliano", "João"];
        return view('welcome', ["nome"=>"RObson Farias", "nomes"=>$ar, "busca"=>$busca]);
    }
    public function create(){
        return view('events.create');
    }
}
