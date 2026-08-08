<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceRating;
use Illuminate\View\View;

class ServiceRatingController extends Controller
{
    public function index(): View
    {
        $counts = ServiceRating::query()->selectRaw('score, COUNT(*) as total')->groupBy('score')->pluck('total', 'score');
        $total = (int) $counts->sum();

        return view('admin.ratings.index', [
            'total' => $total,
            'average' => $total ? round($counts->sum(fn ($count, $score) => $count * $score) / $total, 1) : 0,
            'results' => collect(range(5, 1))->map(fn ($score) => [
                'score' => $score,
                'count' => (int) ($counts[$score] ?? 0),
                'percent' => $total ? round(($counts[$score] ?? 0) * 100 / $total) : 0,
            ]),
            'today' => ServiceRating::whereDate('created_at', today())->count(),
            'recent' => ServiceRating::latest()->limit(15)->get(),
        ]);
    }
}
