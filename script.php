<?php
$content = file_get_contents("routes/web.php");
$content = str_replace(
    "Route::post('/{quiz}/toggle', [\App\Http\Controllers\Quiz\QuizController::class, 'toggleStatus'])->name('teacher.quizzes.toggle');",
    "Route::post('/{quiz}/toggle', [\App\Http\Controllers\Quiz\QuizController::class, 'toggleStatus'])->name('teacher.quizzes.toggle');\n        Route::get('/{quiz}/results/{result}', [\App\Http\Controllers\Quiz\QuizController::class, 'showAttempt'])->name('teacher.quizzes.attempt.show');\n        Route::delete('/{quiz}/results/{result}', [\App\Http\Controllers\Quiz\QuizController::class, 'resetAttempt'])->name('teacher.quizzes.attempt.reset');",
    $content
);
file_put_contents("routes/web.php", $content);
echo "Routes added.\n";

