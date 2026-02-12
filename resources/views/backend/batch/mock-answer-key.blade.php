@extends('backend.layouts.app')

@section('title', 'Mock Test Answer Key')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Header -->
        <div class="border-b pb-4 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Mock Test Answer Key</h1>
                    <p class="text-gray-600 mt-1">Student: {{ $student->name }} ({{ $student->auto_stu_id ?? $student->id }})</p>
                    <p class="text-gray-600">Mock Test: {{ $mockTest->name }}</p>
                </div>
                <div class="text-right">
                    <a href="{{ url()->previous() }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Summary -->
        <div class="grid grid-cols-4 gap-4 mb-8">
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ count($answerKeyData) }}</div>
                <div class="text-sm text-gray-600">Total Questions</div>
            </div>
            <div class="bg-green-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-green-600">
                    {{ collect($answerKeyData)->where('is_correct', true)->count() }}
                </div>
                <div class="text-sm text-gray-600">Correct</div>
            </div>
            <div class="bg-red-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-red-600">
                    {{ collect($answerKeyData)->where('is_correct', false)->where('is_attempted', true)->count() }}
                </div>
                <div class="text-sm text-gray-600">Incorrect</div>
            </div>
            <div class="bg-yellow-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-yellow-600">
                    {{ collect($answerKeyData)->where('is_attempted', false)->count() }}
                </div>
                <div class="text-sm text-gray-600">Unattempted</div>
            </div>
        </div>

        <!-- Answer Key -->
        <div class="space-y-6">
            @php
                $currentSection = '';
            @endphp
            
            @foreach($answerKeyData as $item)
                @if($currentSection != $item['section_name'])
                    @php $currentSection = $item['section_name']; @endphp
                    <div class="bg-gray-100 rounded-lg p-3 mt-6">
                        <h3 class="font-semibold text-gray-700">{{ $item['section_name'] }}</h3>
                    </div>
                @endif
                
                <div class="border rounded-lg p-4 {{ $item['is_correct'] ? 'border-green-200 bg-green-50' : ($item['is_attempted'] ? 'border-red-200 bg-red-50' : 'border-yellow-200 bg-yellow-50') }}">
                    <div class="flex items-start gap-4">
                        <div class="font-bold text-gray-700 min-w-[40px]">Q{{ $item['question_number'] }}.</div>
                        <div class="flex-1">
                            <!-- Question -->
                            <div class="mb-3">
                                {!! $item['question_text'] !!}
                            </div>
                            
                            <!-- Options -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-3">
                                @foreach($item['options'] as $index => $option)
                                    <div class="p-2 rounded border {{ $index == $item['correct_answer'] ? 'border-green-500 bg-green-100' : ($index == $item['user_answer'] && !$item['is_correct'] ? 'border-red-500 bg-red-100' : 'border-gray-200') }}">
                                        <span class="font-semibold">{{ chr(65 + $index) }}.</span> {!! $option !!}
                                        @if($index == $item['correct_answer'])
                                            <span class="text-green-600 ml-2"><i class="fas fa-check"></i> Correct</span>
                                        @endif
                                        @if($index == $item['user_answer'] && !$item['is_correct'])
                                            <span class="text-red-600 ml-2"><i class="fas fa-times"></i> Your Answer</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Status -->
                            <div class="text-sm">
                                @if($item['is_correct'])
                                    <span class="text-green-600 font-semibold"><i class="fas fa-check-circle"></i> Correct Answer</span>
                                @elseif($item['is_attempted'])
                                    <span class="text-red-600 font-semibold"><i class="fas fa-times-circle"></i> Wrong Answer</span>
                                    <span class="text-gray-600 ml-2">Your answer: {{ chr(65 + $item['user_answer']) }}</span>
                                @else
                                    <span class="text-yellow-600 font-semibold"><i class="fas fa-exclamation-circle"></i> Not Attempted</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    @media print {
        .bg-green-50 { background-color: #f0fdf4 !important; }
        .bg-red-50 { background-color: #fef2f2 !important; }
        .bg-yellow-50 { background-color: #fefce8 !important; }
    }
</style>
@endsection
