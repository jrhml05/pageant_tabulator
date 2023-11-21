<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>{{ session('error') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="order-status">Select Stage</label>
                                <select wire:model="stage" class="form-control">
                                    {{-- <option value="">Any</option> --}}
                                    @foreach (\App\Models\Stage::all() as $stage)
                                        <option value="{{ $stage->id }}">{{ $stage->stage_name }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="search-input"><br></label>
                                <div class="input-group">
                                    <button type="button" class="btn btn-primary mr-3" wire:click="filter">
                                        Filter
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="search-input"><br></label>
                                <div class="input-group">
                                    <button type="button" class="btn btn-success mr-3" wire:click="addRow">
                                        ADD NEW DATA ROW
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table" style="color: #454545">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>CATEGORY</th>
                                        <th>PERCENT</th>
                                        {{-- <th>STATUS</th> --}}
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($records as $index => $record)
                                        <tr>
                                            <td><input wire:model="records.{{ $index }}.category_name"
                                                    type="text" class="form-control form-control-lg"></td>
                                            <td><input wire:model="records.{{ $index }}.percent" type="number"
                                                    class="form-control form-control-lg"></td>
                                            {{-- <td><input wire:model="records.{{ $index }}.is_active" type="number" class="form-control form-control-lg"></td> --}}
                                            <td>
                                                <a href="{{ route('subcategory', ($record->id ?? 1)) }}"
                                                    class="btn btn-info"><i class="fas fa-plus fa-sm text-white-50"></i>
                                                    SubCategory</a>
                                                <button wire:click="remove({{ $index }})"
                                                    class="btn btn-danger">Remove</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3">
                                                <center>
                                                    No Data
                                                </center>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                @if ($records->count() > 0)
                                    <tfoot>
                                        <tr>
                                            <td class="text-sm-end">Total</td>
                                            <td class="text-lg">
                                                {{ $records->sum('percent') > 100 ? 'ERROR' : $records->sum('percent') . '%' }}
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                        <hr>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="input-group">
                                    <button wire:click="saveForm()" type="submit" class="btn btn-primary mr-3">
                                        SAVE DATA
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
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
            });
        });

        window.addEventListener('show-modal', event => {
            $('#subcatModal').modal('show');
        })
        window.addEventListener('hide-modal', event => {
            $('#subcatModal').modal('hide');
        })
    </script>
@endpush
