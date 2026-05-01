<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        // Teachers only see their own quizzes
        $quizzes = Quiz::where('created_by', Auth::id())
            ->withCount('questions')
            ->latest()
            ->paginate(10);

        return view('quiz.index', compact('quizzes'));
    }

    public function create()
    {
        return view('quiz.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'duration_minutes' => 'required|integer|min:1|max:120',
        ]);

        $quiz = Quiz::create([
            'title' => $data['title'],
            'subject' => $data['subject'],
            'duration_minutes' => $data['duration_minutes'],
            'created_by' => Auth::id(),
            'status' => 'active',
        ]);

        return redirect()->route('teacher.quizzes.questions', $quiz)
            ->with('success', 'Quiz created! Now add some questions.');
    }

    public function questions(Quiz $quiz)
    {
        if ($quiz->created_by !== Auth::id()) {
            abort(403);
        }

        $questions = $quiz->questions;
        return view('quiz.questions', compact('quiz', 'questions'));
    }

    public function storeQuestions(Request $request, Quiz $quiz)
    {
        if ($quiz->created_by !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.option1' => 'required|string',
            'questions.*.option2' => 'required|string',
            'questions.*.option3' => 'required|string',
            'questions.*.option4' => 'required|string',
            'questions.*.correct_option' => 'required|integer|min:1|max:4',
        ]);

        // Clear existing questions and re-add (Simple approach for now)
        $quiz->questions()->delete();

        foreach ($request->questions as $qData) {
            $quiz->questions()->create($qData);
        }

        return redirect()->route('teacher.quizzes.index')
            ->with('success', 'Questions updated successfully!');
    }

    public function results(Quiz $quiz)
    {
        if ($quiz->created_by !== Auth::id()) {
            abort(403);
        }

        $results = QuizResult::where('quiz_id', $quiz->id)
            ->with('user')
            ->latest()
            ->get();

        return view('quiz.results', compact('quiz', 'results'));
    }

    public function toggleStatus(Quiz $quiz)
    {
        if ($quiz->created_by !== Auth::id()) {
            abort(403);
        }

        $quiz->status = $quiz->status === 'active' ? 'inactive' : 'active';
        $quiz->save();

        return back()->with('success', 'Quiz status updated.');
    }
}
