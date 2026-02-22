import './bootstrap';
import Swal from 'sweetalert2';
window.Swal = Swal;

// Register Service Worker for Offline Ticket Access
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').then(registration => {
            console.log('SW registered: ', registration);
        }).catch(registrationError => {
            console.log('SW registration failed: ', registrationError);
        });
    });
}
