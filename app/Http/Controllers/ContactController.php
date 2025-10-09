<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        // Send email to site developer or configured contact
        $to = config('site.developer.email', env('MAIL_TO_ADDRESS', null));
        if ($to) {
            Mail::to($to)->send(new ContactFormMail($data));
        } else {
            // If no mail configured, optionally log or store the message. For now we just log.
            logger()->info('Contact form submitted (no mail configured):', $data);
        }

        return redirect()->route('contact')->with('success', 'Thank you — your message was sent.');
    }
}
