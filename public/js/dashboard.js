// Public copy of dashboard JS so view works without Vite during dev
document.addEventListener('DOMContentLoaded', function(){
  const uploadBtn = document.querySelector('.btn-primary');
  if(uploadBtn){
    uploadBtn.addEventListener('click', function(e){
      e.preventDefault();
      // simple UI demo
      alert('Unggah laporan: fitur demo (belum terhubung ke backend)');
    });
  }
});
