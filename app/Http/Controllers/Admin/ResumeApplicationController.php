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
        $items = ResumeApplication::query()->with(['region', 'district', 'branchRelation', 'vacancy'])->latest()->paginate(20);
        $unreadIds = $items->getCollection()->whereNull('viewed_at')->pluck('id');

        if ($unreadIds->isNotEmpty()) {
            ResumeApplication::query()->whereKey($unreadIds)->update(['viewed_at' => now()]);
        }

        return view('admin.resumes.index', compact('items'));
    }

    public function destroy(ResumeApplication $resume): RedirectResponse
    {
        $resume->delete();

        return back()->with('status', 'Rezyume o‘chirildi.');
    }
}
