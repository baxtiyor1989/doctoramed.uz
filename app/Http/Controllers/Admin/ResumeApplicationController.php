<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResumeApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ResumeApplicationController extends Controller
{
    public function index(): View
    {
        return view('admin.resumes.index', [
            'items' => ResumeApplication::query()->latest()->paginate(20),
        ]);
    }

    public function destroy(ResumeApplication $resume): RedirectResponse
    {
        $resume->delete();

        return back()->with('status', 'Rezyume o‘chirildi.');
    }
}
