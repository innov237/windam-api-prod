<?php

namespace App\Http\Controllers;

use App\Models\AdminStat;
use App\Models\Demande;
use Illuminate\Http\Request;

class StatistiqueController extends Controller
{
    public function getAllStat(){

        $allStat=AdminStat::select("id")->first();
        $allStatUser=AdminStat::count();

        return $this->reply(true,"statistique",$allStat );
    }

    public function recentCmd(){

        $recentCmd=Demande::all()->take(5);

        return $this->reply(true,"liste des commande ",$recentCmd);

    }

    public function getAllAgent(){

    }
}
