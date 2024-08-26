@persist('timer-trigger')
<div class="w-full flex justify-end">
    <div class="flex w-full justify-between items-center gap-x-4 lg:gap-x-6">
        <div class="flex space-x-2 items-center text-day-7 dark:text-night-1">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </span>

            <div x-data="{
                    started: @entangle('started'),
                    timeEntry: {{ json_encode($this->timeEntry()) }},
                    elapsed: 0,
                    interval: null,
                    init() {
                        Livewire.on('runTimer', () => {
                            this.startTimer();
                        });

                        Livewire.on('stopTimer', () => {
                            this.clearTimer();
                        });

                        if (this.started) {
                            this.startTimer();
                        }
                    },
                    startTimer() {
                        this.clearTimer(); // Clear any existing interval before starting a new one
                        if (this.interval !== null) {
                            return;
                        }

                        if (this.timeEntry) {
                            const startDate = new Date(this.timeEntry.start);

                            this.interval = setInterval(() => {
                                const now = new Date();
                                const diff = Math.floor((now - startDate) / 1000);

                                const hours = Math.floor(diff / 3600) % 24;
                                const minutes = Math.floor(diff / 60) % 60;
                                const seconds = diff % 60;

                                document.getElementById('timer-hours').textContent = String(hours).padStart(2, '0');
                                document.getElementById('timer-minutes').textContent = String(minutes).padStart(2, '0');
                                document.getElementById('timer-seconds').textContent = String(seconds).padStart(2, '0');
                            }, 1000);
                        }
                    },
                    clearTimer() {
                        if (this.interval !== null) {
                            clearInterval(this.interval);
                            this.interval = null;
                        }

                        this.elapsed = 0;
                        document.getElementById('timer-hours').textContent = '00';
                        document.getElementById('timer-minutes').textContent = '00';
                        document.getElementById('timer-seconds').textContent = '00';
                    }
                }"
                 x-init="init()"
                 wire:key="timer-trigger-{{ $this->getId() }}"
                 class="flex-1 text-2xl font-medium">
                <div class="flex space-x-1">
                    <div id="timer-hours" x-text="String(Math.floor(elapsed / 3600)).padStart(2, '0')">00</div>:
                    <div id="timer-minutes" x-text="String(Math.floor((elapsed % 3600) / 60)).padStart(2, '0')">00</div>:
                    <div id="timer-seconds" x-text="String(elapsed % 60).padStart(2, '0')">00</div>
                </div>
            </div>
        </div>

        @if(!$disabledTimer)
            <button type="button"
                    class="group px-4 py-1 rounded-2xl flex space-x-1 text-white shadow transition-colors
                @if($started) bg-red-600 hover:bg-red-500 @else bg-app hover:bg-app-contrast @endif"
                    @if($this->timeEntry() && $started)
                        onclick="Livewire.dispatch('openModal', {component: 'modals.time-entries.stop-time-modal', arguments: {id: {{ $this->timeEntry()->id }} } })"
                    @else
                        wire:click="startTimer"
                @endif>
                @if($started)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
                    </svg>
                @endif
                <span class="font-medium">
                    @if($started) Stop @else Start @endif
                </span>
            </button>
        @endif
    </div>
</div>
@endpersist
