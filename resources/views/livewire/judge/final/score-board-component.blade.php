<div>
    <div class="container mx-auto">
        <div class="grid grid-rows-5 grid-flow-col gap-4">

            @foreach ($records as $index => $record)
                <div class="row-span-3 ...">
                    <div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                        <a href="#">
                            <img class="rounded-t-lg" src="{{ asset('assets/img/mb/'. $record->barangay_id .'.jpg') }}" alt="" />
                        </a>
                        <div class="p-5">
                            <div class="mb-2">
                                <div class="relative">
                                    <input onfocus="this.select()" wire:model="records.{{ $index }}.beauty" type="number" id="floating_outlined_beauty_{{ $index }}" class="{{ $record->beauty > 40 ? 'border-red-500 text-red-500' : '' }} text-xl text-end block px-2.5 pb-2.5 pt-4 p-4 w-full text-gray-900 border border-gray-300 rounded-lg sm:text-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 peer" placeholder="0.00" />
                                    <label for="floating_outlined_beauty_{{ $index }}" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">Beauty 40 %</label>
                                    @if ($record->beauty > 40)
                                        <small class="text-red-300 text-sm">the input score should not be higher than 40</small>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="relative">
                                    <input onfocus="this.select()" wire:model="records.{{ $index }}.intelligence" type="number" id="floating_outlined_intelligence_{{ $index }}" class="{{ $record->intelligence > 60 ? 'border-red-500 text-red-500' : '' }}  text-xl text-end block px-2.5 pb-2.5 pt-4 p-4 w-full text-gray-900 border border-gray-300 rounded-lg sm:text-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 peer" placeholder="0.00" />
                                    <label for="floating_outlined_intelligence_{{ $index }}" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">Intelligence 60 %</label>
                                    @if ($record->intelligence > 60)
                                        <small class="text-red-300 text-sm">the input score should not be higher than 60</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>
