<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AbduController extends Controller
{
    public function books()
    {
        return [
            "doc_title" => 'Qanaqadirda',
            "doc_url" => 'bu url manzilda',
            "base64" => "Buendi base 64 dagi rasm"
        ];
    }

    public function show1()
    {

        $name = "Abdulatif";
        return $name;
    }

    public function list()
    {

        $users = [
            'Abdulatif',
            'Son',
            'Asada'
        ];
        return $users;
    }
}
