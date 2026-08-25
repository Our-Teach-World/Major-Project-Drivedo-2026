<?php
$file = "resources/views/admin/dashboard.blade.php";
$content = file_get_contents($file);

$oldGrid = <<<'HTML'
{{-- Stats Grid --}}
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
    @php
    $statCards = [
        ['label'=>'Total Users',       'value'=>$stats['total_users'],        'icon'=>'??', 'color'=>'blue'],
        ['label'=>'Events',            'value'=>$stats['total_events'],        'icon'=>'??', 'color'=>'purple'],
        ['label'=>'Certificates',      'value'=>$stats['total_certificates'],  'icon'=>'??', 'color'=>'green'],
        ['label'=>'Blockchain Blocks', 'value'=>$stats['total_blocks'],        'icon'=>'?',  'color'=>'yellow'],
        ['label'=>'Emails Sent',       'value'=>$stats['emails_sent'],         'icon'=>'??', 'color'=>'teal'],
        ['label'=>'Revoked',           'value'=>$stats['revoked'],             'icon'=>'??', 'color'=>'red'],
    ];
    @endphp
    @foreach($statCards as $card)
    <div class="card p-5">
        <p class="text-2xl mb-2">{{ $card['icon'] }}</p>
        <p class="text-2xl font-bold text-gray-800">{{ number_format($card['value']) }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $card['label'] }}</p>
    </div>
    @endforeach
</div>
HTML;

$newGrid = <<<'HTML'
{{-- User Stats Grid --}}
<h3 class="font-semibold text-gray-800 mb-4">Platform Overview</h3>
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 mb-8">
    <div class="card p-5">
        <p class="text-2xl mb-2">??</p>
        <p class="text-2xl font-bold text-gray-800">{{ number_format($totalUsers) }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Users</p>
    </div>
    <div class="card p-5">
        <p class="text-2xl mb-2">?</p>
        <p class="text-2xl font-bold text-gray-800">{{ number_format($approvedUsers) }}</p>
        <p class="text-xs text-gray-500 mt-1">Approved Users</p>
    </div>
    <div class="card p-5">
        <p class="text-2xl mb-2">?</p>
        <p class="text-2xl font-bold text-gray-800">{{ number_format($pendingUsers) }}</p>
        <p class="text-xs text-gray-500 mt-1">Pending Approvals</p>
    </div>
    <div class="card p-5">
        <p class="text-2xl mb-2">?????</p>
        <p class="text-2xl font-bold text-gray-800">{{ number_format($teachers) }}</p>
        <p class="text-xs text-gray-500 mt-1">Teachers</p>
    </div>
    <div class="card p-5">
        <p class="text-2xl mb-2">??</p>
        <p class="text-2xl font-bold text-gray-800">{{ number_format($students) }}</p>
        <p class="text-xs text-gray-500 mt-1">Students</p>
    </div>
</div>

<h3 class="font-semibold text-gray-800 mb-4">Certchain & Blockchain Stats</h3>
{{-- Certchain Stats Grid --}}
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
    @php
    $statCards = [
        ['label'=>'Total Users',       'value'=>$stats['total_users'],        'icon'=>'??', 'color'=>'blue'],
        ['label'=>'Events',            'value'=>$stats['total_events'],        'icon'=>'??', 'color'=>'purple'],
        ['label'=>'Certificates',      'value'=>$stats['total_certificates'],  'icon'=>'??', 'color'=>'green'],
        ['label'=>'Blockchain Blocks', 'value'=>$stats['total_blocks'],        'icon'=>'?',  'color'=>'yellow'],
        ['label'=>'Emails Sent',       'value'=>$stats['emails_sent'],         'icon'=>'??', 'color'=>'teal'],
        ['label'=>'Revoked',           'value'=>$stats['revoked'],             'icon'=>'??', 'color'=>'red'],
    ];
    @endphp
    @foreach($statCards as $card)
    <div class="card p-5">
        <p class="text-2xl mb-2">{{ $card['icon'] }}</p>
        <p class="text-2xl font-bold text-gray-800">{{ number_format($card['value']) }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $card['label'] }}</p>
    </div>
    @endforeach
</div>
HTML;

$content = str_replace($oldGrid, $newGrid, $content);
file_put_contents($file, $content);
echo "Dashboard view fixed.\n";

