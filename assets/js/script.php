// Global JavaScript functions

// Utility function for API calls
async function apiCall(url, options = {}) {
    try {
        const response = await fetch(url, {
            headers: {
                'Content-Type': 'application/json',
                ...options.headers
            },
            ...options
        });
        return await response.json();
    } catch (error) {
        console.error('API call failed:', error);
        return { success: false, message: 'Network error occurred' };
    }
}

// Format price with Indian Rupee symbol
function formatPrice(price) {
    return '₹' + parseFloat(price).toFixed(2);
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show`;
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.prepend(notification);
    
    setTimeout(() => {
        notification