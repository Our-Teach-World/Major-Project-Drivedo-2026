<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    /**
     * AI Chat API for study materials.
     */
    public function chat(Request $request)
    {
        return response()->json([
            'response' => 'AI Chat is currently being initialized. Please try again in a few moments.',
            'foundFiles' => []
        ]);
    }

    /**
     * Resume Advisor Chat.
     */
    public function resumeChat(Request $request)
    {
        return response()->json([
            'response' => 'Resume analysis is currently offline. Please contact the administrator.',
            'error' => 'Service temporarily unavailable'
        ]);
    }
}
