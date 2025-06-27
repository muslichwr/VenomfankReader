/**
 * Venomfank - Header Dropdown Functionality
 * Handles user profile and notifications dropdowns
 */

// Profile dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    // Profile dropdown
    const profileBtn = document.getElementById('profile-button');
    const profileDropdown = document.getElementById('profile-dropdown');
    
    if (profileBtn && profileDropdown) {
        profileBtn.addEventListener('click', function() {
            profileDropdown.classList.toggle('hidden');
        });
    }
    
    // Notifications dropdown
    const notificationsBtn = document.getElementById('notifications-button');
    const notificationsDropdown = document.getElementById('notifications-dropdown');
    
    if (notificationsBtn && notificationsDropdown) {
        notificationsBtn.addEventListener('click', function() {
            notificationsDropdown.classList.toggle('hidden');
        });
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        // Profile dropdown
        if (profileBtn && profileDropdown && 
            !profileBtn.contains(event.target) && 
            !profileDropdown.contains(event.target)) {
            profileDropdown.classList.add('hidden');
        }
        
        // Notifications dropdown
        if (notificationsBtn && notificationsDropdown && 
            !notificationsBtn.contains(event.target) && 
            !notificationsDropdown.contains(event.target)) {
            notificationsDropdown.classList.add('hidden');
        }
    });
}); 