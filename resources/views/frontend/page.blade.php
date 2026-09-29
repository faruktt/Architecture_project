@extends('layouts.frontend')

@section('title', $page->title . ' | nook MAGAZINE')
@section('meta_description', $page->meta_description)

@section('content')
<div class="max-w-[860px] mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-zinc-900 border-b border-zinc-200 pb-4 mb-8">
        {{ $page->title }}
    </h1>

    <div class="prose max-w-none text-zinc-700 text-sm leading-relaxed space-y-4">
        {!! $page->content !!}
    </div>
</div>
@endsection
