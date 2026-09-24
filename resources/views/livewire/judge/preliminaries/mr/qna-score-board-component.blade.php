<div class="pt-5">
    <div class="row pt-5">
        @foreach ($records as $index => $record)
            {{-- {{ dd($record->scores) }} --}}
            <div class="col-lg-3 mb-4">
                <div class="card" id="card">

                    <img class="card-img-top" src="{{ asset('assets/img/mr/' . $index + 1 . '.jpg') }}"
                        alt="Card image cap">

                    <div class="card-body">
                        {{-- <h6 class="text-center">#{{ $record->barangay_id }} <span
                                class="text-primary">{{ strtoupper($record->barangay->name) }}</span></h6> --}}

                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Relevance 40%</span>
                            </div>
                            <input onfocus="this.select()" wire:model.live="records.{{ $index }}.relevance" type="number"
                                class="form-control text-center {{ ( $record->relevance > 40 || $record->relevance < 0) ? 'is-invalid' : '' }}"
                                placeholder="00.00" aria-describedby="basic-addon1"
                                {{ $record->is_lock === 1  ? 'disabled' : '' }}>

                        </div>
                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Delivery/Confidence 20%</span>
                            </div>
                            <input onfocus="this.select()" wire:model.live="records.{{ $index }}.delivery" type="number"
                                class="form-control text-center {{ ($record->delivery > 20 || $record->delivery < 0) ? 'is-invalid' : '' }}"
                                placeholder="00.00" aria-describedby="basic-addon1"
                                {{ $record->is_lock === 1  ? 'disabled' : '' }}>

                        </div>
                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Content of Answer 30%</span>
                            </div>
                            <input onfocus="this.select()" wire:model.live="records.{{ $index }}.content" type="number"
                                class="form-control text-center {{ ($record->content > 30 || $record->content < 0)? 'is-invalid' : '' }}"
                                placeholder="00.00" aria-describedby="basic-addon1"
                                {{ $record->is_lock === 1  ? 'disabled' : '' }}>

                        </div>
                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Audience Impact 10%</span>
                            </div>
                            <input onfocus="this.select()" wire:model.live="records.{{ $index }}.audience_impact" type="number"
                                class="form-control text-center {{ ($record->audience_impact > 10 || $record->audience_impact < 0)? 'is-invalid' : '' }}"
                                placeholder="00.00" aria-describedby="basic-addon1"
                                {{ $record->is_lock === 1  ? 'disabled' : '' }}>

                        </div>
                        <hr>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><strong>Total 100%</strong> </span>
                            </div>
                            @php
                                $total = ((float) $record->relevance) + ((float) $record->delivery) + ((float) $record->content) + ((float) $record->audience_impact);
                            @endphp
                            <input style="font-weight: bold" disabled type="number" class="form-control text-center"
                                value="{{ number_format($total, 2) }}" placeholder="00.00"
                                aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><strong>Casual Q&A Equivalent</strong>
                                </span>
                            </div>
                            @php
                                $qna = (cal_percentage($total, 100) / 100 ) * 20;
                            @endphp
                            <input style="font-weight: bold" disabled type="number"
                                class="form-control text-center" value="{{ number_format($qna, 2) }}"
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
                    <a href="{{ route('judge.app.mr.prelim.score', $stage) }}" type="button"
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
