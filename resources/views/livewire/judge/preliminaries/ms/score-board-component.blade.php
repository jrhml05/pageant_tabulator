<div class="pt-5">

    <div class="row pt-5">

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
                                <span class="input-group-text" id="basic-addon1">Casual Wear 50%</span>
                            </div>
                            <input disabled wire:model="records.{{ $index }}.casual_wear" type="number"
                                class="form-control text-center" placeholder="00.00"
                                aria-describedby="basic-addon1">

                        </div>
                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Formal Wear 50%</span>
                            </div>
                            <input disabled wire:model="records.{{ $index }}.formal_wear" type="number"
                                class="form-control text-center" placeholder="00.00"
                                aria-describedby="basic-addon1">

                        </div>
                        <hr>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><strong>Total 100%</strong> </span>
                            </div>
                            @php
                               $total = ((float) $record->casual_wear) + ((float) $record->formal_wear);
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
                <div class="col-md-2 mb-1">
                    <a href="{{ route('judge.app') }}" type="button" class="btn btn-secondary btn-lg btn-block rounded-pill">BACK TO HOME</a>
                </div>
                <div class="col-md-4 mb-1">
                    <a href="{{ route('judge.app.ms.casualwear.score',$stage) }}" type="button" class="btn btn-info btn-lg btn-block rounded-pill">ENTER CASUAL WEAR SCORES</a>

                </div>
                <div class="col-md-4 mb-1">
                    <a href="{{ route('judge.app.ms.formalwear.score',$stage) }}" type="button" class="btn btn-info btn-lg btn-block rounded-pill">ENTER FORMAL WEAR SCORES</a>

                </div>
                <div class="col-md-2 mb-1">
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
