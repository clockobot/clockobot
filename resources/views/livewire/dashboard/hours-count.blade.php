<div class="lg:col-span-2">
    <div class="text-day-7 dark:text-night-1 mb-8">
        <dl class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-8">
            @include('components.dashboard.count-card', ['card_title' => __('timer.last_30_days'), 'count' => $monthly_count])
            @include('components.dashboard.count-card', ['card_title' => __('timer.this_week'), 'count' => $weekly_count])
            @include('components.dashboard.count-card', ['card_title' => __('timer.today'), 'count' => $daily_count])
        </dl>
    </div>
</div>
