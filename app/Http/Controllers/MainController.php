<?php

namespace App\Http\Controllers;

use App\Models\Pekerjaan;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index() {
        // Data untuk chart gender
        $genderStats = Pegawai::select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->get();
        
        $genderLabels = [];
        $genderData = [];
        foreach ($genderStats as $stat) {
            $genderLabels[] = ucfirst($stat->gender);
            $genderData[] = $stat->total;
        }
        
        // Data untuk chart pekerjaan
        $pekerjaanStats = Pekerjaan::withCount('pegawai')
            ->orderBy('pegawai_count', 'desc')
            ->limit(5)
            ->get();
        
        $pekerjaanLabels = $pekerjaanStats->pluck('nama')->toArray();
        $pekerjaanData = $pekerjaanStats->pluck('pegawai_count')->toArray();
        
        return view('index', compact('genderLabels', 'genderData', 'pekerjaanLabels', 'pekerjaanData'));
    }
}
