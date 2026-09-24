<div class="pt-5">
    <div class="row pt-5">
        @foreach ($records as $index => $record)
            {{-- {{ dd($record->scores) }} --}}
            <div class="col-lg-3 mb-4">
                <div class="card" id="card">

                    <img class="card-img-top" src="{{ asset('assets/img/ms/' . $index + 1 . '.jpg') }}"
                        alt="Card image cap">

                    <div class="card-body">
                        {{-- <h6 class="text-center">#{{ $record->barangay_id }} <span
                                class="text-primary">{{ strtoupper($record->barangay->name) }}</span></h6> --}}

                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Creative Design 40%</span>
                            </div>
                            <input onfocus="this.select()" wire:model.live="records.{{ $index }}.design" type="number"
                                class="form-control text-center {{ ( $record->design > 40 || $record->design < 0) ? 'is-invalid' : '' }}"
                                placeholder="00.00" aria-describedby="basic-addon1"
                                {{ $record->is_lock === 1  ? 'disabled' : '' }}>

                        </div>
                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Stage Presence 30%</span>
                            </div>
                            <input onfocus="this.select()" wire:model.live="records.{{ $index }}.stage_presence" type="number"
                                class="form-control text-center {{ ($record->stage_presence > 30 || $record->stage_presence < 0) ? 'is-invalid' : '' }}"
                                placeholder="00.00" aria-describedby="basic-addon1"
                                {{ $record->is_lock === 1  ? 'disabled' : '' }}>

                        </div>
                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Poise & Bearing 20%</span>
                            </div>
                            <input onfocus="this.select()" wire:model.live="records.{{ $index }}.poise_bearing" type="number"
                                class="form-control text-center {{ ($record->poise_bearing > 20 || $record->poise_bearing < 0)? 'is-invalid' : '' }}"
                                placeholder="00.00" aria-describedby="basic-addon1"
                                {{ $record->is_lock === 1  ? 'disabled' : '' }}>

                        </div>
                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Overall Impact 10%</span>
                            </div>
                            <input onfocus="this.select()" wire:model.live="records.{{ $index }}.overall_impact" type="number"
                                class="form-control text-center {{ ($record->overall_impact > 10 || $record->overall_impact < 0)? 'is-invalid' : '' }}"
                                placeholder="00.00" aria-describedby="basic-addon1"
                                {{ $record->is_lock === 1  ? 'disabled' : '' }}>

                        </div>
                        <hr>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><strong>Total 100%</strong> </span>
                            </div>
                            @php
                                $total = ((float) $record->design) + ((float) $record->stage_presence) + ((float) $record->poise_bearing) + ((float) $record->overall_impact);
                            @endphp
                            <input style="font-weight: bold" disabled type="number" class="form-control text-center"
                                value="{{ number_format($total, 2) }}" placeholder="00.00"
                                aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><strong>National Costume Equivalent.</strong>
                                </span>
                            </div>
                            @php
                                $national_costume = (cal_percentage($total, 100) / 100 ) * 20;
                            @endphp
                            <input style="font-weight: bold" disabled type="number"
                                class="form-control text-center" value="{{ number_format($national_costume, 2) }}"
                                placeholder="00.00" aria-describedby="basic-addon1">

                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <br>
    <br>
    <br>
    <br>
    <div class="fixed-bottom bg-white">
        <div class="form-group pl-5 pr-5 pt-1">
            {{-- <button wire:click="try" class="btn btn-primary">dadas</button> --}}
            <div class="row mb-1">
                <div class="col-md-6">
                    <a href="{{ route('judge.app.ms.prelim.score', $stage) }}" type="button"
                        class="btn btn-secondary btn-lg btn-block rounded-pill">BACK TO MAIN SCORE BOARD</a>
                </div>
                <div class="col-md-6">
                    <button wire:click="lockInscore" type="button"
                        class="btn btn-primary btn-lg btn-block rounded-pill">LOCK IN SCORES</button>
                </div>
            </div>


        </div>

    </div>

</div>


@push('scripts')
    <script>
        window.addEventListener('swal:modal', event => {
            swal({
                title: event.detail.message,
                text: event.detail.text,
                icon: event.detail.type,
                buttons: false,
            });
        });

        window.addEventListener('swal:confirm', event => {
        swal({
                title: event.detail.message,
                text: event.detail.text,
                icon: event.detail.type,
                buttons: true,
                dangerMode: false,
            })
            .then((willSave) => {
                if (willSave) {
                    Livewire.dispatch('confirmedLockInScores');
                }
        });
    });
    </script>
@endpush
