/**
 * Configuration - Dynamic API Base URL
 * Automatically detects the correct path regardless of project directory
 */

// Get the base URL dynamically
function getBaseUrl() {
  // Get current page URL
  const currentUrl = window.location.pathname;
  
  // Find the project root by looking for 'index.php' or 'admin.php'
  // Example: /padi/index.php -> /padi
  // Example: /padimart/admin.php -> /padimart
  // Example: /index.php -> /
  
  const parts = currentUrl.split('/').filter(p => p);
  
  // Remove the filename (index.php, admin.php, etc.)
  if (parts.length > 0 && (parts[parts.length - 1].endsWith('.php') || parts[parts.length - 1].endsWith('.html'))) {
    parts.pop();
  }
  
  // Reconstruct the base path
  const basePath = parts.length > 0 ? '/' + parts.join('/') : '/';
  
  return basePath;
}

// Get the API base URL
const API_BASE_URL = getBaseUrl() + '/api';

// Log for debugging
console.log('Project Base URL:', getBaseUrl());
console.log('API Base URL:', API_BASE_URL);

// Export for use in other scripts
window.CONFIG = {
  API_BASE_URL: API_BASE_URL,
  getBaseUrl: getBaseUrl
};

  