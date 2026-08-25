@extends('admin.layouts.app')
@section('title','Certificate Templates')
@section('page-title','Certificate Templates')
@section('page-subtitle','Manage reusable certificate designs')

@section('header-actions')
<a href="{{ route('admin.certchain.templates.create') }}" class="btn-primary text-sm">+ New Template</a>
@endsection

@section('content')
<div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5">
    @forelse($templates as $template)
    <div class="bg-white border-2 border-black p-5 flex flex-col rounded-xl shadow-[6px_6px_0px_#000] hover:transform hover:-translate-y-1 transition-all">
        <div class="flex items-start justify-between mb-3">
            <div>
                <h3 class="font-bold text-gray-900">{{ $template->name }}</h3>
                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-wider mt-0.5">{{ $template->type }}</p>
            </div>
            <span class="px-2 py-0.5 border-2 border-black rounded-lg text-xs font-bold {{ $template->is_active ? 'bg-green-100' : 'bg-gray-100 text-gray-500' }}">
                {{ $template->is_active ? 'ACTIVE' : 'INACTIVE' }}
            </span>
        </div>
        <p class="text-xs text-gray-600 mb-1 font-medium">By: {{ $template->creator->name ?? 'Admin' }}</p>
        <p class="text-xs text-gray-500 mb-4 font-mono">{{ $template->certificates()->count() }} ISSUED</p>

        <div class="mt-auto flex gap-2">
            <a href="{{ route('admin.certchain.templates.preview', $template) }}" target="_blank"
                class="flex-1 text-center px-3 py-2 border-2 border-black rounded-lg text-xs font-bold hover:bg-gray-50 transition-all">
                👁 PREVIEW
            </a>
            <a href="{{ route('admin.certchain.templates.edit', $template) }}"
                class="flex-1 text-center px-3 py-2 bg-black text-white border-2 border-black rounded-lg text-xs font-bold hover:bg-gray-800 transition-all shadow-[2px_2px_0px_#3b82f6]">
                ✏ EDIT
            </a>
            @if($template->certificates()->count() === 0)
            <form method="POST" action="{{ route('admin.certchain.templates.destroy', $template) }}" class="confirm-delete-form">
                @csrf @method('DELETE')
                <button class="px-3 py-2 bg-red-100 text-red-600 border-2 border-black rounded-lg text-xs hover:bg-red-200 transition-all">🗑</button>
            </form>
            @endif
        </div>
    </div>
    @empty
    <div class="md:col-span-3 text-center py-16 text-gray-400">
        <p class="text-4xl mb-3">🎨</p>
        <p>No templates yet.</p>
        <a href="{{ route('admin.certchain.templates.create') }}" class="text-blue-600 hover:underline text-sm mt-2 inline-block">Create your first template →</a>
    </div>
    @endforelse
</div>
{{ $templates->links() }}
@endsection
