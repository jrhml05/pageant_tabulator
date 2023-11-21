<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @foreach (App\Models\Barangay::all() as $index => $barangay)
                                @php
                                    $number = $index + 1;
                                @endphp
                                <div wire:click="edit({{ $barangay->id }})" class="col-lg-2 mb-4" data-toggle="modal"
                                    data-target="#modal_aside_right">
                                    <div class="card" id="card">
                                        @if ($barangay->candidate()->count() == 0)
                                            <img class="card-img-top"
                                                src="{{ asset('assets/img/mb/no_candidate.jpg') }}"
                                                alt="Card image cap">
                                        @else
                                            <img class="card-img-top" src="{{ asset('assets/img/mb/'.$number.'.jpg') }}"
                                                alt="Card image cap">
                                        @endif

                                        <div class="card-body">
                                            <h1 class="text-center">#{{ $barangay->id }}</h1>
                                            <p class="text-center text-primary">{{ strtoupper($barangay->name) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="formModal" class="modal fixed-left fade" tabindex="-1" role="dialog" wire:ignore.self>
        <div class="modal-dialog modal-dialog-aside" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">#{{ $barangay_id }} Brgy. {{ $barangay_name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($check == null)
                        <img class="card-img-top" src="{{ asset('assets/img/mb/no_candidate.jpg') }}"
                            alt="Card image cap">
                    @else
                        <img class="card-img-top" src="{{ asset('assets/img/mb/'.$barangay_id.'.jpg') }}" alt="Card image cap">
                    @endif
                    <div class="form-group">
                        <label for="">First Name</label>
                        <input wire:model="first_name" type="text" class="form-control form-control-lg">
                    </div>
                    <div class="form-group">
                        <label for="">Middle Name</label>
                        <input wire:model="middle_name" type="text" class="form-control form-control-lg">
                    </div>
                    <div class="form-group">
                        <label for="">Last Name</label>
                        <input wire:model="last_name" type="text" class="form-control form-control-lg">
                    </div>
                    <div class="form-group">
                        <label for="">Age</label>
                        <input wire:model="age" type="text" class="form-control form-control-lg">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button wire:click="update()" type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div> <!-- modal-bialog .// -->
    </div> <!-- modal.// -->
</div>

@push('scripts')
    <script>
        window.addEventListener('show-modal', event => {
            $('#formModal').modal('show');
        })
        window.addEventListener('hide-modal', event => {
            $('#formModal').modal('hide');
        })
    </script>
@endpush
