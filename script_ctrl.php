<?php
$file = "app/Http/Controllers/Quiz/QuizController.php";
$content = file_get_contents($file);

$newMethods = <<<'PHP'

    public function showAttempt(Quiz $quiz, QuizResult $result)
    {
        if ($quiz->created_by !== Auth::id()) {
            abort(403);
        }

        $quiz->load('questions');
        $userAnswers = \App\Models\QuizAnswer::where('user_id', $result->user_id)
            ->whereIn('question_id', $quiz->questions->pluck('id'))
            ->get()->keyBy('question_id');

        return view('quiz.attempt', compact('quiz', 'result', 'userAnswers'));
    }

    public function resetAttempt(Quiz $quiz, QuizResult $result)
    {
        if ($quiz->created_by !== Auth::id()) {
            abort(403);
        }

        // Delete the user's answers for this quiz
        \App\Models\QuizAnswer::where('user_id', $result->user_id)
            ->whereIn('question_id', $quiz->questions()->pluck('id'))
            ->delete();

        // Delete the result itself
        $result->delete();

        return back()->with('success', 'Student attempt has been reset. They can now retake the quiz.');
    }
PHP;

$content = str_replace("public function toggleStatus", $newMethods . "\n\n    public function toggleStatus", $content);
file_put_contents($file, $content);
echo "Controller methods added.\n";

