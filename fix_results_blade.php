<?php
$file = "resources/views/quiz/results.blade.php";
$content = file_get_contents($file);

$headerOld = "<th class=\"px-8 py-6 label-caps\">COMPLETION DATE</th>";
$headerNew = "<th class=\"px-8 py-6 label-caps\">COMPLETION DATE</th>\n                    <th class=\"px-8 py-6 label-caps text-right\">ACTIONS</th>";
$content = str_replace($headerOld, $headerNew, $content);

$rowOld = <<<'HTML'
                        <td class="px-8 py-6 text-sm font-bold opacity-40">
                            {{ $res->created_at->format('M d, Y') }}
                        </td>
                    </tr>
HTML;

$rowNew = <<<'HTML'
                        <td class="px-8 py-6 text-sm font-bold opacity-40">
                            {{ $res->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('teacher.quizzes.attempt.show', ['quiz' => $quiz, 'result' => $res]) }}" class="px-3 py-1.5 bg-blue-50 text-blue-700 font-bold rounded-lg text-xs hover:bg-blue-100 transition">View Details</a>
                                <form method="POST" action="{{ route('teacher.quizzes.attempt.reset', ['quiz' => $quiz, 'result' => $res]) }}" onsubmit="return confirm('Reset this attempt? The student will be able to retake the quiz.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-700 font-bold rounded-lg text-xs hover:bg-red-100 transition">Reset Attempt</button>
                                </form>
                            </div>
                        </td>
                    </tr>
HTML;

$content = str_replace($rowOld, $rowNew, $content);
file_put_contents($file, $content);
echo "Updated results blade.\n";

