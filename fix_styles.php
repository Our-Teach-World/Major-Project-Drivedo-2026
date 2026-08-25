<?php
$files = [
    "resources/views/certificates/bulk.blade.php",
    "resources/views/certificates/create.blade.php",
    "resources/views/certificates/index.blade.php",
    "resources/views/certificates/show.blade.php",
    "resources/views/faculty/events/create.blade.php",
    "resources/views/faculty/events/edit.blade.php",
    "resources/views/faculty/events/index.blade.php",
    "resources/views/admin/templates/create.blade.php",
    "resources/views/admin/templates/edit.blade.php",
    "resources/views/admin/templates/index.blade.php",
    "resources/views/admin/blockchain.blade.php"
];

$replacements = [
    "class=\"card " => "class=\"bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg ",
    "class=\"card\"" => "class=\"bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg\"",
    "btn-primary" => "bg-primary text-on-primary px-4 py-2 rounded-xl font-bold hover:scale-[1.02] transition-all",
    "btn-gold" => "bg-amber-500 text-white px-4 py-2 rounded-xl font-bold hover:scale-[1.02] transition-all",
    "text-gray-800" => "text-on-surface font-bold",
    "text-gray-700" => "text-on-surface",
    "text-gray-600" => "text-on-surface-variant",
    "text-gray-500" => "text-on-surface-variant",
    "text-gray-400" => "text-on-surface-variant opacity-70",
    "border-gray-200" => "border-outline-variant/20",
    "border-gray-100" => "border-outline-variant/10",
    "bg-gray-50" => "bg-surface-container-low",
    "bg-white" => "bg-surface",
    "text-blue-600" => "text-primary",
    "text-blue-500" => "text-primary",
    "bg-blue-50" => "bg-primary/5",
    "bg-blue-100" => "bg-primary/10",
    "border-blue-500" => "border-primary",
    "ring-blue-500" => "ring-primary",
    "from-blue-900 to-blue-700" => "from-primary to-primary/80",
    "hover:from-blue-800 hover:to-blue-600" => "hover:scale-[1.02]",
    "shadow-blue-900/20" => "shadow-primary/20",
];

foreach($files as $file) {
    if(!file_exists($file)) continue;
    $content = file_get_contents($file);
    foreach($replacements as $old => $new) {
        $content = str_replace($old, $new, $content);
    }
    file_put_contents($file, $content);
}
echo "Styles updated.";

