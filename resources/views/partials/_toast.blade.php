{{-- Toast Notification - included in all layouts --}}
<style>
.toast-container-custom{position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:10px;pointer-events:none}
.toast-custom{pointer-events:auto;min-width:320px;max-width:420px;padding:14px 20px;border-radius:12px;color:#fff;font-size:14px;font-weight:500;display:flex;align-items:center;gap:12px;box-shadow:0 8px 25px rgba(0,0,0,.2);animation:toastSlideIn .4s cubic-bezier(.22,1,.36,1);backdrop-filter:blur(10px)}
.toast-custom.toast-success{background:linear-gradient(135deg,#198754,#20c997)}
.toast-custom.toast-error{background:linear-gradient(135deg,#dc3545,#e35d6a)}
.toast-custom.toast-info{background:linear-gradient(135deg,#0d6efd,#6ea8fe)}
.toast-custom .toast-icon{font-size:22px;flex-shrink:0}
.toast-custom .toast-msg{flex:1;line-height:1.4}
.toast-custom .toast-close{background:none;border:none;color:rgba(255,255,255,.7);font-size:18px;cursor:pointer;padding:0 0 0 8px;flex-shrink:0;transition:.2s}
.toast-custom .toast-close:hover{color:#fff}
.toast-hide{animation:toastSlideOut .35s ease forwards}
@keyframes toastSlideIn{from{opacity:0;transform:translateX(80px) scale(.95)}to{opacity:1;transform:translateX(0) scale(1)}}
@keyframes toastSlideOut{from{opacity:1;transform:translateX(0) scale(1)}to{opacity:0;transform:translateX(80px) scale(.9)}}
</style>

<div class="toast-container-custom" id="toastContainer"></div>

<script>
function showToast(type, message, duration = 4000) {
    const container = document.getElementById('toastContainer');
    const icons = { success: 'bi-check-circle-fill', error: 'bi-exclamation-circle-fill', info: 'bi-info-circle-fill' };
    const toast = document.createElement('div');
    toast.className = 'toast-custom toast-' + type;
    toast.innerHTML = '<i class="bi ' + (icons[type] || icons.info) + ' toast-icon"></i><span class="toast-msg">' + message + '</span><button class="toast-close" onclick="this.parentElement.classList.add(\'toast-hide\');setTimeout(()=>this.parentElement.remove(),350)">&times;</button>';
    container.appendChild(toast);
    // Auto dismiss
    setTimeout(() => { if (toast.parentElement) { toast.classList.add('toast-hide'); setTimeout(() => toast.remove(), 350); } }, duration);
}

// Auto-show from Laravel session flash
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        showToast('success', @json(session('success')));
    @endif
    @if(session('error'))
        showToast('error', @json(session('error')));
    @endif
    @if($errors->any())
        @foreach($errors->all() as $e)
            showToast('error', @json($e));
        @endforeach
    @endif
});
</script>
