document.addEventListener('DOMContentLoaded', function(){
  // small interactive demo: click upload button -> show alert
  const uploadBtn = document.querySelector('.btn-primary');
  if(uploadBtn){
    uploadBtn.addEventListener('click', function(e){
      e.preventDefault();
      alert('Unggah laporan: fitur demo (belum terhubung ke backend)');
    });
  }
});
