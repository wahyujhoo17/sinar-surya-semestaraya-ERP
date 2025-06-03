<x-app-layout :breadcrumbs="[
    ['label' => 'Inventaris'],
    ['label' => 'Permintaan Barang', 'url' => route('inventaris.permintaan-barang.index')],
    ['label' => 'Buat Baru'],
]" :currentPage="'Buat Permintaan Barang'">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Buat Permintaan Barang</h3>
                        <div class="card-tools"> <a href="{{ route('inventaris.permintaan-barang.index') }}"
                                class="btn btn-default btn-sm"> <i class="fas fa-arrow-left"></i> Kembali </a> </div>
                    </div>
                    <form action="{{ route('inventaris.permintaan-barang.store') }}" method="POST"> @csrf <div
                            class="card-body">
                            <div class="alert alert-info"> <i class="fas fa-info-circle mr-1"></i> Permintaan barang
                                akan otomatis dibuat berdasarkan item pada Sales Order yang dipilih. </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group"> <label for="sales_order_id">Sales Order <span
                                                class="text-danger">*</span></label> <select
                                            class="form-control select2bs4 @error('sales_order_id') is-invalid @enderror"
                                            id="sales_order_id" name="sales_order_id" required>
                                            <option value="">-- Pilih Sales Order --</option>
                                            @foreach ($salesOrders as $so)
                                                <option value="{{ $so->id }}"
                                                    {{ old('sales_order_id') == $so->id ? 'selected' : '' }}>
                                                    {{ $so->nomor }} - {{ $so->customer->nama }} </option>
                                            @endforeach
                                        </select> @error('sales_order_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group"> <label for="gudang_id">Gudang <span
                                                class="text-danger">*</span></label> <select
                                            class="form-control select2bs4 @error('gudang_id') is-invalid @enderror"
                                            id="gudang_id" name="gudang_id" required>
                                            <option value="">-- Pilih Gudang --</option>
                                            @foreach ($gudangs as $gudang)
                                                <option value="{{ $gudang->id }}"
                                                    {{ old('gudang_id') == $gudang->id ? 'selected' : '' }}>
                                                    {{ $gudang->nama }} </option>
                                            @endforeach
                                        </select> @error('gudang_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group"> <label for="catatan">Catatan</label>
                                <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="3">{{ old('catatan') }}</textarea> @error('catatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer"> <button type="submit" class="btn btn-primary"> <i
                                    class="fas fa-save"></i> Simpan </button> </div>
                    </form>
                </div>
            </div>
        </div>
    </div> @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            /* Select2 bootstrap4 theme equivalent styling */
            .select2-container--default .select2-selection--single {
                height: 38px;
                padding: 4px 2px;
                border-color: #D1D5DB;
                border-radius: 0.375rem;
                display: flex;
                align-items: center;
            }

            .dark .select2-container--default .select2-selection--single {
                background-color: #374151;
                border-color: #4B5563;
                color: #F9FAFB;
            }

            .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #F9FAFB;
            }
        </style>
        @endpush @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(function() {
                $('.select2bs4').select2({
                    width: '100%',
                    allowClear: true,
                    placeholder: "Pilih..."
                });
            });
        </script>
    @endpush
</x-app-layout>
