<div>

    <div class="row">

        @foreach ($records as $index => $record)
            {{-- {{ dd($record->scores) }} --}}
            <div class="col-lg-3 mb-4">
                <div class="card" id="card">

                    <img class="card-img-top" src="{{ asset('assets/img/mb/' . $index + 1 . '.jpg') }}"
                        alt="Card image cap">

                    <div class="card-body">
                        {{-- <h6 class="text-center">#{{ $record->barangay_id }} <span
                                class="text-primary">{{ strtoupper($record->barangay->name) }}</span></h6> --}}
                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Beauty 40%</span>
                            </div>
                            <input onfocus="this.select()" wire:model="records.{{ $index }}.beauty" type="number"
                                class="form-control text-center {{ $record->beauty > 40 ? 'is-invalid' : '' }}" placeholder="00.00"
                                aria-describedby="basic-addon1">

                        </div>
                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Poise 20%</span>
                            </div>
                            <input onfocus="this.select()" wire:model="records.{{ $index }}.poise" type="number"
                                class="form-control text-center {{ $record->poise > 20 ? 'is-invalid' : '' }}" placeholder="00.00"
                                aria-describedby="basic-addon1">

                        </div>
                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Swimsuit 20%</span>
                            </div>
                            <input onfocus="this.select()" wire:model="records.{{ $index }}.swimsuit" type="number"
                                class="form-control text-center {{ $record->swimsuit > 20 ? 'is-invalid' : '' }}" placeholder="00.00"
                                aria-describedby="basic-addon1">

                        </div>
                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Evening Gown 20%</span>
                            </div>
                            <input wire:model="records.{{ $index }}.evening_gown" type="number"
                                class="form-control text-center"  {{ $record->evening_gown > 20 ? 'is-invalid' : '' }}" placeholder="00.00"
                                aria-describedby="basic-addon1">

                        </div>
                        <hr>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><strong>Total 100%</strong> </span>
                            </div>
                            @php
                               $total = ((float) $record->beauty) + ((float) $record->poise) + ((float) $record->swimsuit) + ((float) $record->evening_gown);
                            @endphp
                            <input style="font-weight: bold" disabled type="number"
                                class="form-control text-center" value="{{ number_format($total, 2) }}" placeholder="00.00"
                                aria-describedby="basic-addon1">

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
                <div class="col-md-4 mb-1">
                    <a href="{{ route('judge.app') }}" type="button" class="btn btn-secondary btn-lg btn-block rounded-pill">BACK TO HOME</a>
                </div>
                <div class="col-md-4 mb-1">
                    <button wire:click="alertConfirm" type="button" class="btn btn-primary btn-lg btn-block rounded-pill">SAVE SCORES</button>
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
                buttons: event.detail.button,
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
