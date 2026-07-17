document.addEventListener('DOMContentLoaded', function(){
  // Hanya tangkap tombol upload laporan yang spesifik, bukan semua btn-primary
  // Cek apakah kita di halaman laporan dan tombol tersebut adalah untuk upload laporan
  const uploadLaporanBtn = document.querySelector('form[action*="laporan"] .btn-primary, form[action*="laporan"] button[type="submit"]');
  if(uploadLaporanBtn && window.location.pathname.includes('/laporan')){
    uploadLaporanBtn.addEventListener('click', function(e){
      // Hanya prevent default jika form belum terhubung ke backend
      // Untuk sekarang, biarkan form bekerja normal
      // e.preventDefault();
      // alert('Unggah laporan: fitur demo (belum terhubung ke backend)');
    });
  }
});
