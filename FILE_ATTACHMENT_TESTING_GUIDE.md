# Cara Test File Attachment Preview

## Langkah Testing

### 1. Buka Halaman Create Prospek

```
URL: http://your-domain/crm/prospek/create
```

### 2. Buka Browser Console

-   **Chrome/Edge:** Press `F12` atau `Ctrl+Shift+I` (Windows) / `Cmd+Option+I` (Mac)
-   **Firefox:** Press `F12` atau `Ctrl+Shift+K`
-   Pilih tab **Console**

### 3. Scroll ke Section "Lampiran File"

Cari section dengan judul "Lampiran File" di bagian bawah form.

### 4. Pilih File untuk Upload

Click tombol "Upload File" dan pilih:

-   1 file PDF (contoh: `test.pdf`)
-   atau 1 gambar (contoh: `test.jpg`)
-   Ukuran < 10MB

### 5. Check Console Output

Seharusnya muncul log seperti ini:

```javascript
Alpine component initialized
Model type: prospek
Model ID: null
Existing attachments: 0

File select triggered!
Total files selected: 1
Processing file: test.pdf application/pdf 524288
Valid files: 1
Valid file names: ["test.pdf"]
After nextTick, selectedFiles: 1
```

### 6. Check Preview Display

Setelah pilih file, seharusnya muncul section baru dengan heading:
**"File yang akan diupload:"**

Di dalam section tersebut, seharusnya tampil card file dengan:

-   ✅ Icon (🔴 merah untuk PDF, 🔵 biru untuk gambar)
-   ✅ Nama file (contoh: "test.pdf")
-   ✅ Ukuran file (contoh: "512.00 KB")
-   ✅ Tombol X (hapus) di sebelah kanan

### 7. Test Remove File

Click tombol X (hapus) pada file card.

Console seharusnya menampilkan:

```javascript
Files after remove: []
```

Dan preview section seharusnya hilang.

### 8. Test Multiple Files

Pilih beberapa file sekaligus (2-3 file):

Console output:

```javascript
File select triggered!
Total files selected: 3
Processing file: doc1.pdf application/pdf 204800
Processing file: img1.jpg image/jpeg 512000
Processing file: doc2.pdf application/pdf 102400
Valid files: 3
Valid file names: ["doc1.pdf", "img1.jpg", "doc2.pdf"]
```

Preview seharusnya menampilkan 3 card file.

### 9. Test Invalid File Type

Pilih file dengan tipe tidak didukung (contoh: `.txt`, `.docx`):

Console output:

```javascript
File select triggered!
Total files selected: 1
Processing file: test.txt text/plain 1024
File type not allowed: text/plain
Valid files: 0
Valid file names: []
```

Seharusnya muncul notifikasi error merah:

> "File test.txt tidak didukung. Hanya PDF dan gambar yang diperbolehkan."

### 10. Test File Too Large

Pilih file > 10MB:

Console output:

```javascript
File select triggered!
Total files selected: 1
Processing file: large.pdf application/pdf 15728640
File too large: 15728640
Valid files: 0
Valid file names: []
```

Seharusnya muncul notifikasi error:

> "File large.pdf terlalu besar. Maksimal 10MB per file."

### 11. Test Form Submission

1. Isi form prospek (minimal nama prospek, status, sumber)
2. Upload 1-2 file
3. Click "Simpan Prospek"
4. Buka tab **Network** di DevTools
5. Cari request `store` dengan method POST
6. Click request tersebut
7. Pilih tab **Payload** atau **Request**
8. Seharusnya ada `attachments[0]`, `attachments[1]` dengan file data

### 12. Verify Database

Setelah submit berhasil dan redirect ke index:

1. Buka prospek yang baru dibuat
2. Scroll ke section "Lampiran File"
3. Seharusnya tampil file yang diupload
4. Test download file

Atau check database:

```sql
SELECT id, nama_prospek, attachments
FROM prospek
WHERE id = [ID_PROSPEK_BARU]
ORDER BY created_at DESC
LIMIT 1;
```

Seharusnya kolom `attachments` berisi JSON:

```json
[
    {
        "original_name": "test.pdf",
        "filename": "1729267200_abc123.pdf",
        "path": "prospek_attachments/1729267200_abc123.pdf",
        "size": 524288,
        "mime_type": "application/pdf",
        "uploaded_at": "2025-10-18 15:30:00"
    }
]
```

## Troubleshooting

### Jika Console Tidak Menampilkan Log Apapun

**Masalah:** Alpine.js tidak initialized atau JavaScript error.

**Solusi:**

1. Check console untuk error JavaScript
2. Refresh halaman dengan hard reload: `Ctrl+Shift+R` (Windows) / `Cmd+Shift+R` (Mac)
3. Check apakah Alpine.js loaded: type `Alpine` di console, seharusnya return object

### Jika Log Muncul Tapi Preview Tidak

**Masalah:** Alpine.js reactivity atau x-show condition.

**Solusi:**

1. Inspect element preview section
2. Check apakah `x-show` attribute resolved
3. Di console, jalankan:

```javascript
const component = Alpine.$data(
    document.querySelector('[x-data="attachmentManager()"]')
);
console.log("Selected files:", component.selectedFiles);
```

Seharusnya return array dengan file objects.

### Jika Preview Muncul Tapi Nama File Kosong

**Masalah:** x-text binding tidak bekerja.

**Solusi:**

1. Check versi Alpine.js (minimal v3.x)
2. Inspect element nama file
3. Check apakah `x-text` attribute ada
4. Manual test: di console

```javascript
const files = Array.from(document.getElementById("attachments-prospek").files);
console.log(
    "File names:",
    files.map((f) => f.name)
);
```

### Jika File Tidak Tersubmit

**Masalah:** Input file ter-reset atau DataTransfer API tidak didukung.

**Solusi:**

1. Comment out bagian DataTransfer:

```javascript
// const dt = new DataTransfer();
// validFiles.forEach(file => dt.items.add(file));
// event.target.files = dt.files;
```

2. Test submit lagi
3. Jika berhasil, berarti browser tidak support DataTransfer API

## Expected Results

✅ Console log lengkap muncul saat pilih file
✅ Preview section muncul dengan file cards
✅ Nama file dan ukuran tampil dengan benar
✅ Icon sesuai tipe file (merah=PDF, biru=gambar)
✅ Tombol hapus berfungsi
✅ File tersubmit saat save form
✅ File tersimpan di database dalam format JSON
✅ File fisik tersimpan di storage/app/public/prospek_attachments/
✅ File tampil di halaman show dengan download button

## Success Criteria

Sistem dianggap berhasil jika:

1. ✅ User bisa pilih file
2. ✅ Preview langsung muncul dengan info lengkap
3. ✅ Validasi file type dan size bekerja
4. ✅ File berhasil diupload ke server
5. ✅ File tersimpan dengan benar di database dan storage
6. ✅ File bisa didownload dari halaman show

Jika semua criteria terpenuhi, maka implementasi **SUKSES**! 🎉
