@extends('layouts.dashboard')

@section('content')
    <div>
        <div class="pt-4 pb-8">
            <div class="w-full mx-auto">
                <div class="flow-root">
                    <div class="overflow-y-auto">
                        <div class="min-w-full px-4 md:px-8 py-4">
                            <div class="grid grid-cols-1">
                                @livewire('dashboard.hours-count')
                                @livewire('dashboard.graph')
                            </div>

                            @livewire('dashboard.time-now')
                            @livewire('dashboard.recent-activity')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
