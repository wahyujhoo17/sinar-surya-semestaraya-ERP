{{-- Edit Attendance Modal --}}
<div x-data="editAttendanceModal" x-show="open" @edit-attendance.window="openModal($event.detail)"
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
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Edit Data Absensi
                        </h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Ubah data absensi karyawan
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
                            <label for="edit_karyawan_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Karyawan <span class="text-red-500">*</span>
                            </label>
                            <select id="edit_karyawan_id" x-model="form.karyawan_id" required
                                class="select2-employee-edit block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih Karyawan</option>
                                @foreach ($karyawans as $karyawan)
                                    <option value="{{ $karyawan->id }}">
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
                            <label for="edit_tanggal"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="edit_tanggal" x-model="form.tanggal" required
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <div x-show="errors.tanggal" class="mt-1 text-sm text-red-600 dark:text-red-400"
                                x-text="errors.tanggal"></div>
                        </div>

                        {{-- Time In/Out --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="edit_jam_masuk"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Jam Masuk
                                </label>
                                <input type="time" id="edit_jam_masuk" x-model="form.jam_masuk"
                                    class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <div x-show="errors.jam_masuk" class="mt-1 text-sm text-red-600 dark:text-red-400"
                                    x-text="errors.jam_masuk"></div>
                            </div>
                            <div>
                                <label for="edit_jam_keluar"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Jam Keluar
                                </label>
                                <input type="time" id="edit_jam_keluar" x-model="form.jam_keluar"
                                    class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <div x-show="errors.jam_keluar" class="mt-1 text-sm text-red-600 dark:text-red-400"
                                    x-text="errors.jam_keluar"></div>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label for="edit_status"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="edit_status" x-model="form.status" required
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
                            <label for="edit_keterangan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Keterangan
                            </label>
                            <textarea id="edit_keterangan" x-model="form.keterangan" rows="3"
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
                        <span x-show="!loading">Update</span>
                        <span x-show="loading" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Mengupdate...
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
        Alpine.data('editAttendanceModal', () => ({
            open: false,
            loading: false,
            attendanceId: null,
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
                // Initialize Select2 when component is ready
                this.$nextTick(() => {
                    this.initSelect2();
                });
            },

            initSelect2() {
                const $select = $('#edit_karyawan_id');

                // Destroy existing Select2 if it exists
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }

                // Initialize Select2
                $select.select2({
                    placeholder: 'Pilih Karyawan',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $select.closest('.modal, [x-data]'),
                    templateResult: this.formatEmployeeOption,
                    templateSelection: this.formatEmployeeSelection,
                    escapeMarkup: function(markup) {
                        return markup;
                    }
                });

                // Sync Select2 changes with Alpine.js
                $select.on('change', () => {
                    this.form.karyawan_id = $select.val();
                });
            },

            formatEmployeeOption(employee) {
                if (!employee.id) {
                    return employee.text;
                }

                const $employee = $(employee.element);
                const text = $employee.text();

                // Extract name, department, and NIP from option text
                const parts = text.split(' - ');
                const nameAndNip = parts[0];
                const department = parts[1] || '';

                const nameParts = nameAndNip.split(' (');
                const name = nameParts[0];
                const nip = nameParts[1] ? nameParts[1].replace(')', '') : '';

                return $(
                    '<div class="flex flex-col">' +
                    '<div class="font-medium text-gray-900 dark:text-white">' + name +
                    '</div>' +
                    '<div class="text-sm text-gray-500 dark:text-gray-400">' +
                    (department ? department + ' • ' : '') + nip +
                    '</div>' +
                    '</div>'
                );
            },

            formatEmployeeSelection(employee) {
                if (!employee.id) {
                    return 'Pilih Karyawan';
                }

                const text = $(employee.element).text();
                const parts = text.split(' - ');
                const nameAndNip = parts[0];
                const nameParts = nameAndNip.split(' (');
                return nameParts[0]; // Return just the name for selection
            },

            openModal(attendance) {
                this.open = true;
                this.attendanceId = attendance.id;
                this.populateForm(attendance);

                // Reinitialize Select2 when modal opens
                this.$nextTick(() => {
                    this.initSelect2();
                    // Set the Select2 value after initialization
                    $('#edit_karyawan_id').val(this.form.karyawan_id).trigger('change');
                });
            },

            closeModal() {
                this.open = false;
                this.resetForm();

                // Destroy Select2 when modal closes
                const $select = $('#edit_karyawan_id');
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }
            },

            populateForm(attendance) {
                this.form = {
                    karyawan_id: attendance.karyawan_id || '',
                    tanggal: attendance.tanggal || '',
                    jam_masuk: attendance.jam_masuk ? attendance.jam_masuk.substring(0, 5) : '',
                    jam_keluar: attendance.jam_keluar ? attendance.jam_keluar.substring(0, 5) :
                        '',
                    status: attendance.status || '',
                    keterangan: attendance.keterangan || ''
                };
                this.errors = {};
            },

            resetForm() {
                this.form = {
                    karyawan_id: '',
                    tanggal: '',
                    jam_masuk: '',
                    jam_keluar: '',
                    status: '',
                    keterangan: ''
                };
                this.errors = {};
                this.attendanceId = null;
            },

            async submitForm() {
                if (!this.attendanceId) return;

                this.loading = true;
                this.errors = {};

                try {
                    const response = await fetch(
                        `{{ route('hr.absensi.index') }}/${this.attendanceId}`, {
                            method: 'PUT',
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
                    this.showNotification('Terjadi kesalahan saat mengupdate data', 'error');
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
