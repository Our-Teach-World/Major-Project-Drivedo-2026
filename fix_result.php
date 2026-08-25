<?php
$file = "resources/views/student/quiz/result.blade.php";
$content = file_get_contents($file);

$reviewSection = <<<'HTML'
            <!-- Detailed Review -->
            <div class="lg:col-span-12 mt-10">
                <div class="card-surface p-10">
                    <h3 class="label-caps mb-6 text-xl">Detailed Review</h3>
                    <div class="space-y-8">
                        @foreach($quiz->questions as $index => $question)
                            @php
                                $userAnswer = $userAnswers->get($question->id);
                                $selectedOption = $userAnswer ? $userAnswer->selected_option : null;
                                $isCorrect = $selectedOption == $question->correct_option;
                            @endphp
                            <div class="p-6 rounded-2xl border {{ $isCorrect ? 'border-emerald-200 bg-emerald-50/30' : 'border-red-200 bg-red-50/30' }}">
                                <div class="flex items-start gap-4">
                                    <div class="w-8 h-8 flex-shrink-0 rounded-full flex items-center justify-center font-bold {{ $isCorrect ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="flex-grow">
                                        <p class="font-bold text-lg mb-4 text-[#06141B]">{{ $question->question_text }}</p>
                                        <div class="grid md:grid-cols-2 gap-3">
                                            @for($i = 1; $i <= 4; $i++)
                                                @php
                                                    $optText = $question->{"option_".$i};
                                                    if(!$optText) continue;
                                                    
                                                    $isThisSelected = ($selectedOption == $i);
                                                    $isThisCorrect = ($question->correct_option == $i);
                                                    
                                                    $optClass = "border-gray-200 bg-white opacity-60";
                                                    if($isThisCorrect) {
                                                        $optClass = "border-emerald-500 bg-emerald-50 text-emerald-900 font-bold shadow-sm ring-1 ring-emerald-500";
                                                    } elseif($isThisSelected && !$isThisCorrect) {
                                                        $optClass = "border-red-500 bg-red-50 text-red-900 shadow-sm ring-1 ring-red-500";
                                                    }
                                                @endphp
                                                <div class="px-4 py-3 border rounded-xl {{ $optClass }}">
                                                    <div class="flex items-center justify-between">
                                                        <span>{{ $optText }}</span>
                                                        @if($isThisCorrect)
                                                            <span class="text-emerald-600 font-bold">? Correct</span>
                                                        @elseif($isThisSelected)
                                                            <span class="text-red-600 font-bold">? Your Answer</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
HTML;

$content = str_replace("<!-- Chart Card -->", $reviewSection . "\n\n            <!-- Chart Card -->", $content);
file_put_contents($file, $content);
echo "Added review section.\n";

