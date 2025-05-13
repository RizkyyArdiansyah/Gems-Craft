<div class="w-full flex items-center justify-between max-w-lg mx-auto mt-4">
    @php
        $steps = ['Cart', 'Shipping', 'Payment', 'Finish'];
        $currentIndex = array_search($currentStep, $steps);
    @endphp

    <div class="w-[180px] mx-auto md:w-full flex items-center relative">
        @foreach ($steps as $index => $step)
            <div class="relative flex-1 text-center">
                <!-- Progress Bar -->
                @if (!$loop->first)
                    <div class="absolute top-2 -left-1/2 w-[calc(100%+8px)] h-1 
                        {{ $index <= $currentIndex ? 'bg-blue-500' : 'bg-gray-300' }} z-0">
                    </div>
                @endif

                <!-- Step Bulatan -->
                <div class="w-3 h-3 rounded-full mx-auto mt-1 relative z-10
                    {{ $index <= $currentIndex ? 'bg-blue-500' : 'bg-gray-300' }}">
                </div>

                <!-- Nama Step -->
                <p class="text-[10px] md:text-xs mt-2 {{ $index <= $currentIndex ? 'text-black font-bold' : 'text-gray-500' }}">
                    {{ $step }}
                </p>
            </div>
        @endforeach
    </div>
</div>
