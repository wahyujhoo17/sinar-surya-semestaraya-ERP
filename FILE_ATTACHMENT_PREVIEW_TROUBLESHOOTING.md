# File Attachment Preview Issue - Troubleshooting

## Masalah: "File yang akan diupload: kosong tidak ada namanya"

### Diagnosa

Preview file menampilkan area kosong tanpa nama file setelah file dipilih.

### Kemungkinan Penyebab

1. **Alpine.js tidak loaded/initialized**

    - Check browser console untuk error Alpine.js
    - Pastikan Alpine.js script ada di layout

2. **File object tidak tersimpan di state**

    - Check console.log output setelah pilih file
    - Seharusnya muncul: "Selected files: [...]"

3. **x-text binding tidak bekerja**
    - Browser tidak mendukung Alpine.js syntax
    - Conflict dengan JavaScript lain

### Perbaikan yang Sudah Dilakukan

#### 1. Added Debug Console Logs

```javascript
handleFileSelect(event) {
    // ...
    this.selectedFiles = validFiles;
    console.log('Selected files:', validFiles);
    console.log('File names:', validFiles.map(f => f.name));
    // ...
}
```

#### 2. Added Null Checks

```blade
<span x-text="file.name || 'Unknown file'"></span>
<span x-text="formatFileSize(file.size || 0)"></span>
```

#### 3. Added x-cloak

```blade
<div x-show="selectedFiles && selectedFiles.length > 0" x-cloak>
```

#### 4. Added CSS for x-cloak

```css
[x-cloak] {
    display: none !important;
}
```

#### 5. Fixed removeFile Function

Sekarang juga update file input ketika file dihapus dari preview.

### Cara Test

#### Test 1: Check Alpine.js Loaded

1. Buka halaman create prospek
2. Open browser console (F12)
3. Type: `Alpine`
4. **Expected:** Object dengan methods
5. **If undefined:** Alpine.js tidak loaded

#### Test 2: Check Component Initialized

1. Buka halaman create prospek
2. Scroll ke "Lampiran File"
3. Check console untuk log:
    ```
    Alpine component initialized
    Model type: prospek
    Model ID: null
    Existing attachments: 0
    ```
4. **If no logs:** Component tidak initialized

#### Test 3: Check File Selection

1. Click "Upload File"
2. Pilih 1 file PDF atau gambar
3. Check console untuk log:
    ```
    Selected files: [File {...}]
    File names: ["nama_file.pdf"]
    ```
4. **If no logs:** Event handler tidak triggered

#### Test 4: Check Preview Display

1. Setelah pilih file
2. Area "File yang akan diupload:" harus muncul
3. Harus tampil:
    - Icon (merah untuk PDF, biru untuk gambar)
    - Nama file
    - Ukuran file
    - Tombol X (hapus)

### Solusi Berdasarkan Test Results

#### Jika Alpine.js Tidak Loaded

**File:** `resources/views/layouts/app.blade.php`

Check apakah ada:

```blade
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
```

Atau:

```blade
@vite(['resources/js/app.js'])
```

Dan di `resources/js/app.js`:

```javascript
import Alpine from "alpinejs";
window.Alpine = Alpine;
Alpine.start();
```

#### Jika Component Tidak Initialized

Pastikan function `attachmentManager()` defined sebelum component render:

```blade
<script>
function attachmentManager() {
    return {
        selectedFiles: [],
        // ...
    }
}
</script>

<div x-data="attachmentManager()">
```

#### Jika File Selection Tidak Bekerja

Check apakah event handler terdaftar:

1. Inspect element input file
2. Check Event Listeners di DevTools
3. Seharusnya ada `change` event

#### Jika Preview Tidak Muncul

Check kondisi x-show:

```blade
<div x-show="selectedFiles && selectedFiles.length > 0">
```

Dalam console, check:

```javascript
Alpine.$data(document.querySelector("[x-data]")).selectedFiles;
```

Should return array dengan file objects.

### Manual Test HTML

Jika Alpine.js bermasalah, buat test manual:

```html
<!-- Test tanpa Alpine.js -->
<input type="file" id="test-file" multiple />
<div id="preview"></div>

<script>
    document
        .getElementById("test-file")
        .addEventListener("change", function (e) {
            const files = Array.from(e.target.files);
            const preview = document.getElementById("preview");

            preview.innerHTML = files
                .map(
                    (f) => `
        <div>${f.name} - ${(f.size / 1024).toFixed(2)} KB</div>
    `
                )
                .join("");
        });
</script>
```

Jika ini bekerja tapi Alpine.js version tidak, maka masalahnya di Alpine.js setup.

### Alternative Solution: Pure JavaScript

Jika Alpine.js terus bermasalah, gunakan vanilla JavaScript:

```blade
<input type="file" id="attachments-{{ $modelType }}" name="attachments[]" multiple
       onchange="handleFileChange(this)">
<div id="preview-{{ $modelType }}"></div>

<script>
function handleFileChange(input) {
    const preview = document.getElementById('preview-{{ $modelType }}');
    const files = Array.from(input.files);

    preview.innerHTML = files.map((file, index) => `
        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
            <span>${file.name}</span>
            <span>${(file.size / 1024).toFixed(2)} KB</span>
        </div>
    `).join('');
}
</script>
```

### Check Form Submission

Pastikan file benar-benar tersubmit:

```php
// Di controller
dd($request->file('attachments'));
```

Seharusnya return array of UploadedFile objects.

### Final Checklist

-   [ ] Alpine.js loaded (check window.Alpine)
-   [ ] Component initialized (check console logs)
-   [ ] Event handler triggered (check console logs)
-   [ ] selectedFiles array populated (check console logs)
-   [ ] Preview div visible (check x-show condition)
-   [ ] File names displayed (check x-text binding)
-   [ ] Files submitted with form (check network tab)

### Contact for Support

Jika semua sudah dicek tapi masih tidak bekerja:

1. Screenshot browser console
2. Screenshot network tab (saat submit form)
3. Share hasil `dd($request->all())` dari controller
4. Share Laravel version dan environment (local/production)
