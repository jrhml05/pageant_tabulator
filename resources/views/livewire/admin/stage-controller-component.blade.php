<div>
    <div class="row">
        <div class="col-xl-2 col-lg-4">
            <div class="card shadow mb-4">
                <div class="card">
                    <div class="card-header">
                        <h6>Stage Controller</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($stages as $stage)
                                <div class="form-group ml-3">
                                    <div class="form-check mr-4">
                                        @livewire('admin.active-status-component', ['model' => $stage, 'field' => 'is_active'], key($stage->id))
                                        <label class="form-check-label" for="exampleRadios1">
                                            {{ $stage->stage_name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
