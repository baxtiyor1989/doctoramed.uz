<?php

namespace App\Http\Controllers;

use App\Models\ResumeApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ResumeApplicationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'regex:/^\+998\s\d{2}\s\d{3}\s\d{2}\s\d{2}$/'],
            'position' => ['required', 'string', 'max:255'],
            'branch' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $data['full_name'] = mb_strtoupper($data['full_name'], 'UTF-8');

        ResumeApplication::create($data);

        return back()->with('resume_status', 'Rezyume yuborildi.');
    }
}
