@extends('layouts.app')
@section('title', $post->user->name . "'s Post")
@section('styles')
@endsection
@section('header')
    <div class="group flex">
        <div class="font-bold text-xl opacity-0 group-hover:opacity-100 transition-opacity">
             Posts
            ←
        </div>
        <button onclick="window.history.back()" class="text-gray-100 hover:text-gray-300 font-bold">
            Back
        </button>
    </div>
@endsection
@section('content')
    <div class="container  mt-4">
        <div class="card border p-4 rounded w-full md:w-1/2 mx-auto">
            <div class="card-body">
                <h2 class="text-xl font-bold mb-2">{{ $post->user->name }}</h2>
                <p class="text-gray-700 mb-4">{{ $post->content }}</p>
                <span class="text-sm text-gray-600">{{ $post->created_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection