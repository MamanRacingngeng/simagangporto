<style>
  /* Toast container */
  #global-toast-container {
    position: fixed;
    right: 20px;
    bottom: 20px;
    z-index: 999999;
    display: flex;
    flex-direction: column;
    gap: 8px;
    pointer-events: none;
  }
  .global-toast {
    pointer-events: auto;
    min-width: 220px;
    max-width: 420px;
    background: rgba(17,24,39,0.95);
    color: #fff;
    padding: 10px 14px;
    border-radius: 8px;
    box-shadow: 0 8px 24px rgba(2,6,23,0.36);
    font-weight: 600;
    opacity: 0;
    transform: translateY(8px);
    transition: all 220ms ease;
  }
  .global-toast.show {
    opacity: 1;
    transform: translateY(0);
  }
  .global-toast.success { background: linear-gradient(90deg,#10B981,#059669); }
  .global-toast.warn { background: linear-gradient(90deg,#F59E0B,#D97706); }
  .global-toast.error { background: linear-gradient(90deg,#EF4444,#B91C1C); }
</style>

<div id="global-toast-container" aria-live="polite" aria-atomic="true"></div>

<script>
  window.showToast = function(message = '', type = 'default', duration = 3500) {
    try {
      const container = document.getElementById('global-toast-container');
      if (!container) return;
      const toast = document.createElement('div');
      toast.className = 'global-toast ' + (type === 'success' || type === 'warn' || type === 'error' ? type : '');
      toast.textContent = message;
      container.appendChild(toast);
      // force reflow then show
      window.getComputedStyle(toast).opacity;
      toast.classList.add('show');
      const t = setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => { try { container.removeChild(toast); } catch(e){} }, 240);
      }, duration);
      // allow click to dismiss
      toast.addEventListener('click', () => {
        clearTimeout(t);
        toast.classList.remove('show');
        setTimeout(() => { try { container.removeChild(toast); } catch(e){} }, 180);
      });
    } catch(e) { console.warn('showToast error', e); }
  };
</script>
