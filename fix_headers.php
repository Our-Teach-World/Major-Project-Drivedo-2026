<?php
$files = array_merge(
    glob("resources/views/certificates/*.blade.php"),
    glob("resources/views/faculty/events/*.blade.php"),
    glob("resources/views/admin/templates/*.blade.php")
);
foreach($files as $file) {
    if(!file_exists($file)) continue;
    $content = file_get_contents($file);
    if(preg_match("/@section\('header-actions'\)(.*?)@endsection/s", $content, $matches)) {
        $actions = trim($matches[1]);
        $content = preg_replace("/@section\('header-actions'\).*?@endsection/s", "", $content);
        $content = preg_replace("/@section\('content'\)/", "@section('content')\n<div class=\"flex justify-end mb-4\">" . $actions . "</div>", $content);
        file_put_contents($file, $content);
    }
}
echo "Done";

