import './bootstrap.js';

// Admin-specific JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Alpine.js for admin components
    if (window.Alpine) {
        Alpine.start();
    }
    
    // Admin-specific functionality
    console.log('Admin interface loaded');
});