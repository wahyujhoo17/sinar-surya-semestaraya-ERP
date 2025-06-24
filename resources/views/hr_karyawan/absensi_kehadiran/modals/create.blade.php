{{-- Create Attendance Modal --}}
<div x-data="createAttendanceModal" x-show="open" @open-create-modal.window="openModal()"
    class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        {{-- Background overlay --}}
        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        {{-- Modal centering trick --}}
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        {{-- Modal panel --}}
        <div x-show="open" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

            {{-- Modal header --}}
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-start">
                    <div
                        class="flex-shrink-0 mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Tambah Data Absensi
                        </h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Tambahkan data absensi karyawan secara manual
                        </p>
                    </div>
                </div>
            </div>

            {{-- Modal body --}}
            <form @submit.prevent="submitForm()">
                <div class="bg-white dark:bg-gray-800 px-4 pb-4 sm:p-6 sm:pb-4">
                    <div class="space-y-4">
                        {{-- Employee Selection --}}
                        <div>
                            <label for="create_karyawan_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Karyawan <span class="text-red-500">*</span>
                            </label>
                            <select id="create_karyawan_id" x-model="form.karyawan_id" required
                                class="create-employee-select block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih Karyawan</option>
                                @foreach ($karyawans as $karyawan)
                                    <option value="{{ $karyawan->id }}" data-name="{{ $karyawan->nama_lengkap }}"
                                        data-department="{{ $karyawan->department->nama ?? '' }}"
                                        data-nip="{{ $karyawan->nip ?? '' }}">
                                        {{ $karyawan->nama_lengkap }}
                                        @if ($karyawan->department)
                                            - {{ $karyawan->department->nama }}
                                        @endif
                                        ({{ $karyawan->nip ?? 'No NIP' }})
                                    </option>
                                @endforeach
                            </select>
                            <div x-show="errors.karyawan_id" class="mt-1 text-sm text-red-600 dark:text-red-400"
                                x-text="errors.karyawan_id"></div>
                        </div>

                        {{-- Date --}}
                        <div>
                            <label for="create_tanggal"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="create_tanggal" x-model="form.tanggal" required
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <div x-show="errors.tanggal" class="mt-1 text-sm text-red-600 dark:text-red-400"
                                x-text="errors.tanggal"></div>
                        </div>

                        {{-- Time In/Out --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="create_jam_masuk"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Jam Masuk
                                </label>
                                <input type="time" id="create_jam_masuk" x-model="form.jam_masuk"
                                    class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <div x-show="errors.jam_masuk" class="mt-1 text-sm text-red-600 dark:text-red-400"
                                    x-text="errors.jam_masuk"></div>
                            </div>
                            <div>
                                <label for="create_jam_keluar"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Jam Keluar
                                </label>
                                <input type="time" id="create_jam_keluar" x-model="form.jam_keluar"
                                    class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <div x-show="errors.jam_keluar" class="mt-1 text-sm text-red-600 dark:text-red-400"
                                    x-text="errors.jam_keluar"></div>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label for="create_status"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="create_status" x-model="form.status" required
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih Status</option>
                                <option value="hadir">Hadir</option>
                                <option value="sakit">Sakit</option>
                                <option value="alpha">Alpha</option>
                                <option value="izin">Izin</option>
                                <option value="cuti">Cuti</option>
                            </select>
                            <div x-show="errors.status" class="mt-1 text-sm text-red-600 dark:text-red-400"
                                x-text="errors.status"></div>
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label for="create_keterangan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Keterangan
                            </label>
                            <textarea id="create_keterangan" x-model="form.keterangan" rows="3"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Masukkan keterangan tambahan..."></textarea>
                            <div x-show="errors.keterangan" class="mt-1 text-sm text-red-600 dark:text-red-400"
                                x-text="errors.keterangan"></div>
                        </div>
                    </div>
                </div>

                {{-- Modal footer --}}
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" :disabled="loading"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!loading">Simpan</span>
                        <span x-show="loading" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Menyimpan...
                        </span>
                    </button>
                    <button type="button" @click="closeModal()" :disabled="loading"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:mr-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('createAttendanceModal', () => ({
            open: false,
            loading: false,
            form: {
                karyawan_id: '',
                tanggal: '',
                jam_masuk: '',
                jam_keluar: '',
                status: '',
                keterangan: ''
            },
            errors: {},

            init() {
                // Set default date to today
                this.form.tanggal = new Date().toISOString().split('T')[0];
            },

            initSelect2() {
                const $select = $('#create_karyawan_id');

                // Destroy existing Select2 if it exists
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }

                // Initialize Select2
                $select.select2({
                    placeholder: 'Pilih Karyawan',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $select.closest('.fixed'),
                    templateResult: this.formatEmployeeOption,
                    templateSelection: this.formatEmployeeSelection,
                    escapeMarkup: function(markup) {
                        return markup;
                    }
                });

                // Sync Select2 changes with Alpine.js
                $select.on('change', (e) => {
                    this.form.karyawan_id = $select.val();
                });
            },

            formatEmployeeOption(employee) {
                if (!employee.id) {
                    return employee.text;
                }

                const $employee = $(employee.element);
                const name = $employee.data('name') || '';
                const department = $employee.data('department') || '';
                const nip = $employee.data('nip') || '';

                return $(
                    '<div class="flex flex-col py-2">' +
                    '<div class="font-medium text-gray-900 dark:text-white">' + name +
                    '</div>' +
                    '<div class="text-sm text-gray-500 dark:text-gray-400">' +
                    (department ? department + ' • ' : '') + (nip || 'No NIP') +
                    '</div>' +
                    '</div>'
                );
            },

            formatEmployeeSelection(employee) {
                if (!employee.id) {
                    return 'Pilih Karyawan';
                }

                const $employee = $(employee.element);
                return $employee.data('name') || employee.text;
            },

            openModal() {
                this.open = true;
                this.resetForm();

                // Reinitialize Select2 when modal opens
                this.$nextTick(() => {
                    this.initSelect2();
                });
            },

            closeModal() {
                this.open = false;
                this.resetForm();

                // Destroy Select2 when modal closes
                const $select = $('#create_karyawan_id');
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }
            },

            resetForm() {
                this.form = {
                    karyawan_id: '',
                    tanggal: new Date().toISOString().split('T')[0],
                    jam_masuk: '',
                    jam_keluar: '',
                    status: '',
                    keterangan: ''
                };
                this.errors = {};
            },

            async submitForm() {
                this.loading = true;
                this.errors = {};

                try {
                    const response = await fetch('{{ route('hr.absensi.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(this.form)
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        this.closeModal();
                        this.showNotification(result.message, 'success');
                        // Trigger data reload
                        this.$dispatch('filters-changed', {});
                    } else {
                        if (result.errors) {
                            this.errors = result.errors;
                        } else {
                            this.showNotification(result.message || 'Terjadi kesalahan',
                                'error');
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    this.showNotification('Terjadi kesalahan saat menyimpan data', 'error');
                } finally {
                    this.loading = false;
                }
            },

            showNotification(message, type = 'info') {
                // You can implement a notification system here
                if (type === 'error') {
                    alert('Error: ' + message);
                } else {
                    alert(message);
                }
            }
        }));
    });
</script>
