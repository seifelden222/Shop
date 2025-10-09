<?php

namespace App\Http\Controllers;

use App\Jobs\SendContactMail;
use App\Mail\ContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        // Server-side sanitization (trim + strip tags) to avoid XSS or HTML injection
        $data = [
            'name' => trim(strip_tags($validated['name'])),
            'email' => filter_var(trim($validated['email']), FILTER_SANITIZE_EMAIL),
            'subject' => isset($validated['subject']) ? trim(strip_tags($validated['subject'])) : null,
            'message' => trim(strip_tags($validated['message'])),
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ];

        // Basic heuristics to detect suspicious payloads
        $suspicious = false;
        foreach (['name','subject','message'] as $k) {
            if (preg_match('/<script|\b(onerror|onload|onclick)\b|javascript:/i', $validated[$k] ?? '')) {
                $suspicious = true;
                break;
            }
        }

        if ($suspicious) {
            logger()->warning('Suspicious contact form submission blocked', $data);
            // respond with success message to avoid leaking detection logic
            return redirect()->route('contact')->with('success', 'Thank you — your message was received.');
        }

        // Dispatch email sending to the queue for better performance
        $to = config('site.developer.email', env('MAIL_TO_ADDRESS', null));
        if ($to) {
            try {
                SendContactMail::dispatch($data);
            } catch (\Exception $e) {
                logger()->error('Dispatching SendContactMail failed', ['error' => $e->getMessage(), 'data' => $data]);
            }
        } else {
            // If no mail configured, store or log the message safely
            logger()->info('Contact form submitted (no mail configured):', $data);
        }

        return redirect()->route('contact')->with('success', 'Thank you — your message was received.');
    }
}
