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
                                <span class="input-group-text" id="basic-addon1">Mastery 40%</span>
                            </div>
                            <input onfocus="this.select()" wire:model="records.{{ $index }}.mastery" type="number"
                                class="form-control text-center {{ $record->mastery > 40 ? 'is-invalid' : '' }}"
                                placeholder="00.00" aria-describedby="basic-addon1">

                        </div>
                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Poise & Bearing 30%</span>
                            </div>
                            <input onfocus="this.select()" wire:model="records.{{ $index }}.poise" type="number"
                                class="form-control text-center {{ $record->poise > 30 ? 'is-invalid' : '' }}"
                                placeholder="00.00" aria-describedby="basic-addon1">

                        </div>
                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Stage Presence 30%</span>
                            </div>
                            <input onfocus="this.select()" wire:model="records.{{ $index }}.stage_presence" type="number"
                                class="form-control text-center {{ $record->stage_presence > 30 ? 'is-invalid' : '' }}"
                                placeholder="00.00" aria-describedby="basic-addon1">

                        </div>
                        <hr>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><strong>Total 100%</strong> </span>
                            </div>
                            @php
                                $total = ((float) $record->mastery) + ((float) $record->poise) + ((float) $record->stage_presence);
                            @endphp
                            <input style="font-weight: bold" disabled type="number" class="form-control text-center"
                                value="{{ number_format($total, 2) }}" placeholder="00.00"
                                aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><strong>Production Number Equivalent.</strong>
                                </span>
                            </div>
                            @php
                                $production_number = (cal_percentage($total, 100) / 100 ) * 30;
                            @endphp
                            <input style="font-weight: bold" disabled type="number"
                                class="form-control text-center" value="{{ number_format($production_number, 2) }}"
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
                    <a href="{{ route('judge.app.mr.score', $stage) }}" type="button"
                        class="btn btn-secondary btn-lg btn-block rounded-pill">BACK TO MAIN SCORE BOARD</a>
                </div>
                <div class="col-md-6">
                    {{-- <button wire:click="alertConfirm" type="button"
                        class="btn btn-primary btn-lg btn-block rounded-pill">SAVE SCORES</button> --}}
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
                    window.livewire.emit('save');
                }
        });
    });
    </script>
@endpush
