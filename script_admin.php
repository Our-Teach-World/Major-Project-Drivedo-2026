<?php
$files = array_merge(
    glob("resources/views/admin/templates/*.blade.php"),
    glob("resources/views/admin/blockchain.blade.php")
);
foreach($files as $file) {
    if(!file_exists($file)) continue;
    $content = file_get_contents($file);
    $content = str_replace("@extends('layouts.certchain_app')", "@extends('admin.layouts.app')", $content);
    $content = str_replace("@section('title',", "@section('page_title',", $content);
    file_put_contents($file, $content);
}
echo "Done";

