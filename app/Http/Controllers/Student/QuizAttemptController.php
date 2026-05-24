<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizAttemptController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $query = Quiz::where('status', 'active');

        // Filter by Subject
        if ($request->filled('subject')) {
            $query->where('subject', $request->input('subject'));
        }

        // Filter by Date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        // Filter by Completion Status
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'completed') {
                $query->whereHas('results', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
            } elseif ($status === 'uncompleted') {
                $query->whereDoesntHave('results', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
            }
        }

        // Students see active quizzes with pagination and filters preserved
        $quizzes = $query->withCount('questions')
            ->withExists(['results as completed' => function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        // Get all unique subjects for the filter dropdown
        $subjects = Quiz::where('status', 'active')
            ->whereNotNull('subject')
            ->where('subject', '!=', '')
            ->distinct()
            ->pluck('subject');

        // Get student's quiz attempt history
        $history = QuizResult::where('user_id', $userId)
            ->with('quiz')
            ->latest()
            ->get();

        return view('student.quiz.index', compact('quizzes', 'subjects', 'history'));
    }

    public function take(Quiz $quiz)
    {
        if ($quiz->status !== 'active') {
            return redirect()->route('student.quizzes.index')->with('error', 'This quiz is not currently active.');
        }

        // Check if already attempted
        $alreadyAttempted = QuizResult::where('user_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->exists();

        if ($alreadyAttempted) {
            return redirect()->route('student.quizzes.result', $quiz);
        }

        $questions = $quiz->questions()->get();
        return view('student.quiz.take', compact('quiz', 'questions'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        if ($quiz->status !== 'active') {
            return response()->json(['success' => false, 'message' => 'Quiz is closed.'], 403);
        }

        $answers = $request->input('answers', []); // question_id => selected_option
        $questions = $quiz->questions;
        $score = 0;
        $total = $questions->count();

        foreach ($questions as $question) {
            $selected = $answers[$question->id] ?? null;
            
            // Save answer
            QuizAnswer::create([
                'user_id' => Auth::id(),
                'question_id' => $question->id,
                'selected_option' => $selected ? (int)$selected : 0,
            ]);

            if ($selected && (int)$selected === (int)$question->correct_option) {
                $score++;
            }
        }

        $percentage = ($total > 0) ? ($score / $total) * 100 : 0;

        $result = QuizResult::create([
            'user_id' => Auth::id(),
            'quiz_id' => $quiz->id,
            'score' => $score,
            'total_questions' => $total,
            'percentage' => $percentage,
        ]);

        return response()->json([
            'success' => true,
            'redirect' => route('student.quizzes.result', $quiz)
        ]);
    }

    public function result(Quiz $quiz)
    {
        $userId = Auth::id();
        $result = QuizResult::where('user_id', $userId)
            ->where('quiz_id', $quiz->id)
            ->firstOrFail();

        // Get class average for comparison
        $avgScore = QuizResult::where('quiz_id', $quiz->id)->avg('score') ?? 0;
        
        // Get highest score for comparison
        $maxScore = QuizResult::where('quiz_id', $quiz->id)->max('score') ?? 0;
        
        // Get rank
        $rank = QuizResult::where('quiz_id', $quiz->id)
            ->where('score', '>', $result->score)
            ->count() + 1;

        $quiz->load('questions');
        $userAnswers = QuizAnswer::where('user_id', $userId)
            ->whereIn('question_id', $quiz->questions->pluck('id'))
            ->get()->keyBy('question_id');

        // Fetch user's entire quiz history for listing at the bottom
        $history = QuizResult::where('user_id', $userId)
            ->with('quiz')
            ->latest()
            ->get();

        return view('student.quiz.result', compact('quiz', 'result', 'avgScore', 'maxScore', 'rank', 'userAnswers', 'history'));
    }
}
