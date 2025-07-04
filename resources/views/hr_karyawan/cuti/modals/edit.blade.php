{{-- Edit Cuti Modal --}}
<div x-data="editCutiModal" x-show="open" @edit-cuti.window="openModal($event.detail)"
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
                <div class="sm:flex sm:items-start">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Edit Pengajuan Cuti
                        </h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Ubah data pengajuan cuti karyawan
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
                                class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm text-gray-900 dark:text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:focus:border-primary-400 dark:focus:ring-primary-400">
                                <option value="">Pilih Karyawan</option>
                                @foreach ($karyawan as $emp)
                                    <option value="{{ $emp->id }}" data-name="{{ $emp->nama_lengkap }}"
                                        data-department="{{ $emp->department->nama ?? '' }}"
                                        data-nip="{{ $emp->nip }}">
                                        {{ $emp->nama_lengkap }} ({{ $emp->nip }}) -
                                        {{ $emp->department->nama ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                            <p x-show="errors.karyawan_id" x-text="errors.karyawan_id"
                                class="mt-1 text-sm text-red-600 dark:text-red-400"></p>
                        </div>

                        {{-- Jenis Cuti --}}
                        <div>
                            <label for="edit_jenis_cuti"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jenis Cuti <span class="text-red-500">*</span>
                            </label>
                            <select id="edit_jenis_cuti" x-model="form.jenis_cuti" required
                                class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm text-gray-900 dark:text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:focus:border-primary-400 dark:focus:ring-primary-400">
                                <option value="">Pilih Jenis Cuti</option>
                                <option value="tahunan">Cuti Tahunan</option>
                                <option value="sakit">Sakit</option>
                                <option value="melahirkan">Melahirkan</option>
                                <option value="penting">Keperluan Penting</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                            <p x-show="errors.jenis_cuti" x-text="errors.jenis_cuti"
                                class="mt-1 text-sm text-red-600 dark:text-red-400"></p>
                        </div>

                        {{-- Date Range --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="edit_tanggal_mulai"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tanggal Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="edit_tanggal_mulai" x-model="form.tanggal_mulai" required
                                    :min="today" @change="calculateDuration()"
                                    class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm text-gray-900 dark:text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:focus:border-primary-400 dark:focus:ring-primary-400">
                                <p x-show="errors.tanggal_mulai" x-text="errors.tanggal_mulai"
                                    class="mt-1 text-sm text-red-600 dark:text-red-400"></p>
                            </div>

                            <div>
                                <label for="edit_tanggal_selesai"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tanggal Selesai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="edit_tanggal_selesai" x-model="form.tanggal_selesai" required
                                    :min="form.tanggal_mulai || today" @change="calculateDuration()"
                                    class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm text-gray-900 dark:text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:focus:border-primary-400 dark:focus:ring-primary-400">
                                <p x-show="errors.tanggal_selesai" x-text="errors.tanggal_selesai"
                                    class="mt-1 text-sm text-red-600 dark:text-red-400"></p>
                            </div>
                        </div>

                        {{-- Duration Display --}}
                        <div x-show="duration > 0"
                            class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-blue-400 mr-2" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                    Durasi cuti: <span x-text="duration"></span> hari
                                </span>
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div>
                            <label for="edit_keterangan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Keterangan <span class="text-red-500">*</span>
                            </label>
                            <textarea id="edit_keterangan" x-model="form.keterangan" rows="4" required
                                class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm text-gray-900 dark:text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:focus:border-primary-400 dark:focus:ring-primary-400"
                                placeholder="Jelaskan alasan cuti atau keperluan..."></textarea>
                            <p x-show="errors.keterangan" x-text="errors.keterangan"
                                class="mt-1 text-sm text-red-600 dark:text-red-400"></p>
                        </div>
                    </div>
                </div>

                {{-- Modal footer --}}
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" :disabled="loading"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!loading">Perbarui</span>
                        <span x-show="loading" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Memperbarui...
                        </span>
                    </button>
                    <button type="button" @click="closeModal()" :disabled="loading"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:mr-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('editCutiModal', () => ({
            open: false,
            loading: false,
            duration: 0,
            cutiId: null,
            today: new Date().toISOString().split('T')[0],
            form: {
                karyawan_id: '',
                jenis_cuti: '',
                tanggal_mulai: '',
                tanggal_selesai: '',
                keterangan: ''
            },
            errors: {},

            openModal(cuti) {
                this.open = true;
                this.cutiId = cuti.id;
                this.populateForm(cuti);

                // Initialize Select2 when modal opens
                this.$nextTick(() => {
                    this.initSelect2();
                });
            },

            closeModal() {
                this.open = false;
                this.resetForm();
            },

            populateForm(cuti) {
                this.form = {
                    karyawan_id: cuti.karyawan_id?.toString() || '',
                    jenis_cuti: cuti.jenis_cuti || '',
                    tanggal_mulai: cuti.tanggal_mulai || '',
                    tanggal_selesai: cuti.tanggal_selesai || '',
                    keterangan: cuti.keterangan || ''
                };
                this.errors = {};
                this.calculateDuration();
            },

            initSelect2() {
                const $select = $('#edit_karyawan_id');

                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }

                $select.select2({
                    theme: 'default',
                    width: '100%',
                    placeholder: 'Pilih Karyawan',
                    allowClear: true,
                    dropdownParent: $select.parent(),
                    templateResult: this.formatEmployee,
                    templateSelection: this.formatEmployeeSelection,
                    matcher: this.customMatcher
                });

                // Set initial value
                $select.val(this.form.karyawan_id).trigger('change');

                $select.on('change', (e) => {
                    this.form.karyawan_id = e.target.value;
                    this.errors.karyawan_id = '';
                });
            },

            formatEmployee(employee) {
                if (!employee.id) {
                    return 'Pilih Karyawan';
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

            customMatcher(params, data) {
                if ($.trim(params.term) === '') {
                    return data;
                }

                const term = params.term.toLowerCase();
                const $option = $(data.element);
                const name = ($option.data('name') || '').toLowerCase();
                const department = ($option.data('department') || '').toLowerCase();
                const nip = ($option.data('nip') || '').toLowerCase();

                if (name.indexOf(term) > -1 || department.indexOf(term) > -1 || nip.indexOf(term) >
                    -1) {
                    return data;
                }

                return null;
            },

            calculateDuration() {
                if (this.form.tanggal_mulai && this.form.tanggal_selesai) {
                    const startDate = new Date(this.form.tanggal_mulai);
                    const endDate = new Date(this.form.tanggal_selesai);

                    if (endDate >= startDate) {
                        this.duration = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) +
                        1;
                    } else {
                        this.duration = 0;
                    }
                }
            },

            resetForm() {
                this.form = {
                    karyawan_id: '',
                    jenis_cuti: '',
                    tanggal_mulai: '',
                    tanggal_selesai: '',
                    keterangan: ''
                };
                this.errors = {};
                this.duration = 0;
                this.cutiId = null;

                // Reset Select2
                const $select = $('#edit_karyawan_id');
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.val(null).trigger('change');
                }
            },

            async submitForm() {
                if (!this.cutiId) return;

                this.loading = true;
                this.errors = {};

                try {
                    const response = await fetch(
                    `{{ route('hr.cuti.index') }}/${this.cutiId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(this.form)
                    });

                    const result = await response.json();

                    if (result.success) {
                        showNotification(result.message, 'success');
                        this.closeModal();
                        window.dispatchEvent(new CustomEvent('cuti-updated'));
                    } else {
                        if (result.errors) {
                            this.errors = result.errors;
                        } else {
                            showNotification(result.message || 'Terjadi kesalahan', 'error');
                        }
                    }
                } catch (error) {
                    console.error('Error submitting form:', error);
                    showNotification('Terjadi kesalahan saat mengirim data', 'error');
                } finally {
                    this.loading = false;
                }
            }
        }));
    });
</script>
