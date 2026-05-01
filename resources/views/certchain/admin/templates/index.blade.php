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
    <div class="card p-5 flex flex-col">
        <div class="flex items-start justify-between mb-3">
            <div>
                <h3 class="font-semibold text-gray-800">{{ $template->name }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ ucfirst($template->type) }} &bull; {{ ucfirst($template->border_style) }} border</p>
            </div>
            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $template->is_active ? 'badge-verified' : 'bg-gray-100 text-gray-500' }}">
                {{ $template->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
        <p class="text-xs text-gray-500 mb-3">Created by {{ $template->creator->name ?? '—' }} &bull; {{ $template->created_at->format('d M Y') }}</p>
        <p class="text-xs text-gray-400 mb-4">{{ $template->certificates()->count() }} certificates issued using this template</p>

        <div class="mt-auto flex gap-2">
            <a href="{{ route('admin.certchain.templates.preview', $template) }}" target="_blank"
                class="flex-1 text-center px-3 py-1.5 border border-gray-200 rounded-lg text-xs text-gray-600 hover:bg-gray-50 transition">
                👁 Preview
            </a>
            <a href="{{ route('admin.certchain.templates.edit', $template) }}"
                class="flex-1 text-center px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-100 transition">
                ✏ Edit
            </a>
            @if($template->certificates()->count() === 0)
            <form method="POST" action="{{ route('admin.certchain.templates.destroy', $template) }}" onsubmit="return confirm('Delete this template?')">
                @csrf @method('DELETE')
                <button class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs hover:bg-red-100 transition">🗑</button>
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
