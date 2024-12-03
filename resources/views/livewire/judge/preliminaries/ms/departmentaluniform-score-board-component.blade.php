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
                                <span class="input-group-text" id="basic-addon1">Presentation & Neatness 40%</span>
                            </div>
                            <input onfocus="this.select()" wire:model="records.{{ $index }}.presentation" type="number"
                                class="form-control text-center {{ ( $record->presentation > 40 || $record->presentation < 0) ? 'is-invalid' : '' }}"
                                placeholder="00.00" aria-describedby="basic-addon1"
                                {{ $record->is_lock === 1  ? 'disabled' : '' }}>

                        </div>
                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Figure 30%</span>
                            </div>
                            <input onfocus="this.select()" wire:model="records.{{ $index }}.figure" type="number"
                                class="form-control text-center {{ ($record->figure > 30 || $record->figure < 0) ? 'is-invalid' : '' }}"
                                placeholder="00.00" aria-describedby="basic-addon1"
                                {{ $record->is_lock === 1  ? 'disabled' : '' }}>

                        </div>
                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Beauty & Poise 20%</span>
                            </div>
                            <input onfocus="this.select()" wire:model="records.{{ $index }}.beauty_poise" type="number"
                                class="form-control text-center {{ ($record->beauty_poise > 20 || $record->beauty_poise < 0)? 'is-invalid' : '' }}"
                                placeholder="00.00" aria-describedby="basic-addon1"
                                {{ $record->is_lock === 1  ? 'disabled' : '' }}>

                        </div>
                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Overall Impact 10%</span>
                            </div>
                            <input onfocus="this.select()" wire:model="records.{{ $index }}.overall_impact" type="number"
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
                                $total = ((float) $record->presentation) + ((float) $record->figure) + ((float) $record->beauty_poise) + ((float) $record->overall_impact);
                            @endphp
                            <input style="font-weight: bold" disabled type="number" class="form-control text-center"
                                value="{{ number_format($total, 2) }}" placeholder="00.00"
                                aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><strong>Departmental Uniform Equivalent</strong>
                                </span>
                            </div>
                            @php
                                $dept_uniform = (cal_percentage($total, 100) / 100 ) * 20;
                            @endphp
                            <input style="font-weight: bold" disabled type="number"
                                class="form-control text-center" value="{{ number_format($dept_uniform, 2) }}"
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

@php
    function cal_percentage($num_amount, $num_total)
    {
        $count1 = $num_amount / $num_total;
        $count2 = $count1 * 100;
        $count = number_format($count2, 2);
        return $count;
    }
@endphp

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
                    window.livewire.emit('confirmedLockInScores');
                }
        });
    });
    </script>
@endpush
