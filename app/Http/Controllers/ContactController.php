<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'student_id' => 'required|string|max:50',
            'issue_type' => 'required|string|in:voting,login,candidate,other',
            'message' => 'required|string|max:1000',
        ]);

        // Log the contact submission
        Log::info('Contact form submission', [
            'name' => $request->name,
            'email' => $request->email,
            'student_id' => $request->student_id,
            'issue_type' => $request->issue_type,
            'message' => $request->message,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Here you could send an email notification to election committee
        // For now, we'll just redirect back with success message

        return back()->with('success', 'Thank you for your message. Our election committee will respond to you shortly.');
    }
}
