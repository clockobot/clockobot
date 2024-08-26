@extends('layouts.dashboard')

@section('content')
    <div class="page-layout">
        <div class="page-wrapper">
            <x-pages.header page_title="{{ $project->title}}" page_intro="{{ $project->description }}" back_link="{{ route('projects.index') }}">
                <x-slot:page_actions>
                    @php
                        $hoursConsumption = $project->hours_consumption();
                        $progressWidth = min($hoursConsumption, 100) . '%';
                        $bgClass = $hoursConsumption > 100 ? 'bg-app text-white' : ($hoursConsumption > 50 ? 'bg-app/50 text-app' : 'bg-app/20 text-app');
                    @endphp

                    @if($project->hour_estimate)
                        <div class="flex items-center space-x-2">
                            <span>{{ __('timer.status') }}</span>
                            <div class="relative border border-app-contrast w-[180px] h-10 rounded">
                                <span style="width: {{ $progressWidth }}; transition: width 1s ease;"
                                      class="transition-all duration-700 ease-in-out block h-full rounded {{ $bgClass }}">
                                    <i class="absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-1/2 font-semibold text-xl">{{ number_format($hoursConsumption, 2) . '%' }}</i>
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="relative flex justify-center items-center border border-app-contrast text-app-contrast w-[180px] h-10 rounded italic font-medium text-xl">
                            <span>{{ __('timer.non_estimated') }}</span>
                        </div>
                    @endif
                </x-slot>
            </x-pages.header>
        </div>
    </div>

    <div class="grid grid-cols-1 bg-day-1 dark:bg-night-5 sm:grid-cols-2 lg:grid-cols-4 text-day-7 dark:text-night-1 font-medium text-center">
        <div class="p-4 lg:px-8 border-day-3 dark:border-night-4 border-b lg:border-b-0">
            <p class="text-sm text-app">{{ __('timer.client') }}</p>
            <p class="mt-2 gap-x-2">
                <span class="text-xl md:text-2xl">{{ $project->client->name }}</span>
            </p>
        </div>
        <div class="py-4 px-4 lg:px-8 sm:border-l border-day-3 dark:border-night-4 border-b lg:border-b-0">
            <p class="text-sm text-app">{{ __('timer.time_engaged') }}</p>
            <p class="mt-2 gap-x-2">
                <span class="text-xl md:text-2xl">{{ process_hours_total($project->time_entries) }}</span>
            </p>
        </div>
        <div class="py-4 px-4 lg:px-8 sm:border-l md:border-l-0 border-day-3 dark:border-night-4 border-b md:border-b-0">
            <p class="text-sm text-app">{{ __('timer.time_estimate') }}</p>
            <p class="mt-2 gap-x-2">
                <span class="text-xl md:text-2xl">{{ $project->hour_estimate }}</span>
            </p>
        </div>
        <div class="py-4 px-4 lg:px-8 sm:border-l border-day-3 dark:border-night-4">
            <p class="text-sm text-app">{{ __('timer.deadline') }}</p>
            <p class="mt-2 gap-x-2">
                <span class="text-xl md:text-2xl">{{ ($project->deadline) ? $project->deadline->format('d.m.Y') : '' }}</span>
            </p>
        </div>
    </div>

    <div class="px-6">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mt-8">
            <h2 class="text-day-7 dark:text-night-4 font-medium text-xl mb-4 md:mb-0">{{ __('timer.time_entries') }}</h2>
            <x-buttons.primary type="link" href="{{ route('projects.export', $project->id) }}">{{ __('timer.export') }}</x-buttons.primary>
        </div>

        @livewire('projects.project-show-time-entries', ['project' => $project])
    </div>

@endsection
