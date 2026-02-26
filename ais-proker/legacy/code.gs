// GANTI DENGAN ID GOOGLE SHEET ANDA
const SHEET_ID = "1K13Nrxey3fMj_D07ukIewj_eCcH6XPTG6mNDIxL45TY"; 
const ss = SpreadsheetApp.openById(SHEET_ID);

// Fungsi utama untuk menampilkan web app
function doGet(e) {
  return HtmlService.createTemplateFromFile('Index')
      .evaluate()
      .setTitle('Program Kerja An Nahl Islamic School')
      .setXFrameOptionsMode(HtmlService.XFrameOptionsMode.DEFAULT);
}

// Fungsi untuk menyertakan file lain (CSS, JS) ke dalam HTML utama
function include(filename) {
  return HtmlService.createHtmlOutputFromFile(filename).getContent();
}

// GANTI SELURUH FUNGSI getAllData DENGAN YANG INI
function getAllData() {
  try {
    const sheetNames = {
      visi: 'Visi',
      misi: 'Misi',
      sasaran: 'Sasaran',
      program: 'ProgramKerja',
      apbs: 'APBS'
    };

    // Fungsi helper yang membaca SEMUA kolom yang ada
    const getDataFromSheet = (sheetName, mapFunction) => {
      const sheet = ss.getSheetByName(sheetName);
      if (!sheet) return [];
      const lastRow = sheet.getLastRow();
      if (lastRow < 2) return [];
      
      // Baca semua kolom yang ada di sheet, dari baris 2
      const range = sheet.getRange(2, 1, lastRow - 1, sheet.getLastColumn());
      return range.getValues().map(mapFunction).filter(row => row && (row.id || row.kode));
    };

    const misiSheet = ss.getSheetByName(sheetNames.misi);
    const lastMisiRow = misiSheet.getLastRow();

    return {
      visi: ss.getSheetByName(sheetNames.visi).getRange('A1').getValue(),
      misi: lastMisiRow < 2 ? [] : misiSheet.getRange(2, 1, lastMisiRow - 1, 1).getValues().flat().filter(String),
      
      // Pemetaan data yang aman, tidak peduli berapa banyak kolom yang ada
      sasaran: getDataFromSheet(sheetNames.sasaran, row => ({ 
          id: row[0], sasaran: row[1], target: row[2], periode: row[3], metode: row[4] 
      })),
      
      program: getDataFromSheet(sheetNames.program, row => ({ 
          id: row[0], nama: row[1], deskripsi: row[2], pj: row[3], unit: row[4], timeline: row[5] 
          // Kita hanya ambil data yang dibutuhkan untuk tabel utama, abaikan kolom detail
      })),
      
      apbs: getDataFromSheet(sheetNames.apbs, row => ({ 
          id: row[0], kode: row[1], uraian: row[2], anggaran: row[3], keterangan: row[4] 
      }))
    };
  } catch (e) {
    Logger.log("Error di getAllData: " + e.toString() + "\nStack: " + e.stack);
    return { error: e.toString() };
  }
}

// --- FUNGSI UNTUK MENYIMPAN DATA ---
function saveData(type, data) {
  try {
    let sheet;
    let newRow;
    let id = data.id || new Date().getTime(); // Generate ID jika baru

    switch(type) {
      case 'visi':
        ss.getSheetByName('Visi').getRange('A1').setValue(data.visi);
        return { status: 'success', message: 'Visi berhasil disimpan.' };
      
      case 'misi':
        const misiSheet = ss.getSheetByName('Misi');
        misiSheet.getRange(2, 1, misiSheet.getLastRow(), 1).clearContent();
        if (data.misi && data.misi.length > 0) {
          misiSheet.getRange(2, 1, data.misi.length, 1).setValues(data.misi.map(m => [m]));
        }
        return { status: 'success', message: 'Misi berhasil disimpan.' };
        
      case 'sasaran':
        sheet = ss.getSheetByName('Sasaran');
        newRow = [id, data.sasaran, data.target, data.periode, data.metode];
        break;
      
      case 'program':
        sheet = ss.getSheetByName('ProgramKerja');
        newRow = [id, data.nama, data.deskripsi, data.pj, data.unit, data.timeline];
        break;
        
      case 'apbs':
        sheet = ss.getSheetByName('APBS');
        newRow = [id, data.kode, data.uraian, data.anggaran, data.keterangan];
        break;
        
      default:
        return { status: 'error', message: 'Tipe data tidak dikenal.' };
    }

    if (data.id) { // Edit
      const dataRange = sheet.getDataRange().getValues();
      for (let i = 1; i < dataRange.length; i++) {
        if (dataRange[i][0] == data.id) {
          sheet.getRange(i + 1, 1, 1, newRow.length).setValues([newRow]);
          return { status: 'success', message: 'Data berhasil diperbarui.' };
        }
      }
    } else { // Tambah baru
      sheet.appendRow(newRow);
      return { status: 'success', message: 'Data berhasil ditambahkan.' };
    }
  } catch (e) {
    Logger.log(e);
    return { status: 'error', message: e.toString() };
  }
}

// --- FUNGSI UNTUK MENGHAPUS DATA ---
function deleteData(type, id) {
  try {
    let sheetName;
    switch(type) {
      case 'sasaran': sheetName = 'Sasaran'; break;
      case 'program': sheetName = 'ProgramKerja'; break;
      case 'apbs': sheetName = 'APBS'; break;
      default: return { status: 'error', message: 'Tipe data tidak dikenal.' };
    }
    
    const sheet = ss.getSheetByName(sheetName);
    const dataRange = sheet.getDataRange().getValues();
    for (let i = 1; i < dataRange.length; i++) {
      if (dataRange[i][0] == id) {
        sheet.deleteRow(i + 1);
        return { status: 'success', message: 'Data berhasil dihapus.' };
      }
    }
    return { status: 'error', message: 'Data tidak ditemukan.' };
  } catch (e) {
    Logger.log(e);
    return { status: 'error', message: e.toString() };
  }
}
// ===============================================
// FUNGSI UNTUK EKSPOR PDF
// ===============================================

// Fungsi untuk menyertakan file CSS ke dalam template PDF
function includeForPdf(filename) {
  return HtmlService.createHtmlOutputFromFile(filename).getContent();
}

// Fungsi utama yang dipanggil oleh client untuk memulai proses ekspor
function exportAllDataAsPdf() {
  try {
    // 1. Ambil semua data dinamis
    const data = getAllData();

    // =========================================================
    // MODIFIKASI: Suntikkan data struktur statis ke objek data
    // =========================================================
    data.struktur = {
      kepalaBagian: "Slamet Haryadi",
      koordinator: [
        { nama: "Masdik", jabatan: "Koordinator OB" },
        { nama: "Anen", jabatan: "Koordinator Sarpras" },
        { nama: "Jaka", jabatan: "Koordinator Security" }
      ],
      tim: ["Tim OB", "Tim Security"]
    };
    // =========================================================
    // AKHIR MODIFIKASI
    // =========================================================

    // 2. Buat konten HTML dari template khusus untuk PDF
    const template = HtmlService.createTemplateFromFile('PdfTemplate');
    template.data = data; // Kirim data (yang sekarang sudah berisi struktur) ke template
    const htmlContent = template.evaluate().getContent();

    // 3. Konversi HTML ke PDF
    const blob = Utilities.newBlob(htmlContent, MimeType.HTML, 'Program_Kerja_Lengkap.html');
    const pdfBlob = blob.getAs(MimeType.PDF);
    pdfBlob.setName(`Program Kerja Umum An Nahl - ${new Date().toLocaleDateString('id-ID')}.pdf`);

    // 4. Simpan PDF ke folder sementara di Google Drive
    const folderId = "1roN6dki1JJUw0RIG0b4ebaVyKAl74RAB"; // <--- GANTI INI!
    const folder = DriveApp.getFolderById(folderId);
    const pdfFile = folder.createFile(pdfBlob);
    
    // Atur file agar bisa diakses oleh siapa saja dengan link (untuk diunduh)
    pdfFile.setSharing(DriveApp.Access.ANYONE_WITH_LINK, DriveApp.Permission.VIEW);

    // 5. Kembalikan URL unduhan ke client
    // Kita menggunakan format URL khusus agar file langsung diunduh
    const downloadUrl = `https://drive.google.com/uc?export=download&id=${pdfFile.getId()}`;
    
    // (Opsional) Hapus file setelah beberapa waktu untuk menghemat ruang
    // ScriptApp.newTrigger('deleteFileById').timeBased().after(10 * 60 * 1000).create(); // Hapus setelah 10 menit
    
    return { success: true, downloadUrl: downloadUrl };

  } catch (e) {
    Logger.log("Error saat ekspor PDF: " + e.message + "\nStack: " + e.stack);
    return { success: false, message: e.message };
  }
}

// TAMBAHKAN FUNGSI BARU INI
function getProgramById(id) {
  try {
    const sheet = ss.getSheetByName('ProgramKerja');
    const dataRange = sheet.getDataRange().getValues();
    
    for (let i = 1; i < dataRange.length; i++) {
      if (dataRange[i][0] == id) { // Mencari baris dengan ID yang cocok
        const row = dataRange[i];
        // Pastikan urutan ini cocok dengan kolom di Sheet Anda
        return {
            success: true,
            data: {
                id: row[0],
                nama: row[1],
                deskripsi: row[2],
                pj: row[3],
                unit: row[4], // Tambahkan properti unit
                timeline: row[5],
                indikator: row[6] || 'N/A',
                anggaran: row[7] || 0,
                catatan: row[8] || 'Tidak ada'
            }
        };
      }
    }
    return { success: false, message: 'Program kerja tidak ditemukan.' };
  } catch (e) {
    Logger.log(e);
    return { success: false, message: e.toString() };
  }
}