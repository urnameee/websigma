// Save this as sidebar.js
const SidebarManager = {
    // Function to initialize sidebar
    init: function() {
        this.loadSidebar();
        this.setActiveMenu();
    },

    // Function to load sidebar content
    loadSidebar: function() {
        // Load sidebar template using fetch
        fetch('/frontend/src/pages/admin-ukm/js/sidebar.html')
            .then(response => response.text())
            .then(html => {
                // Insert the sidebar HTML into the page
                document.querySelector('#sidebar-container').innerHTML = html;
                
                // Initialize AdminLTE sidebar functionality after loading
                if ($.fn.overlayScrollbars) {
                    $('.nav-sidebar').overlayScrollbars({
                        className: 'os-theme-light',
                        sizeAutoCapable: true,
                        scrollbars: {
                            autoHide: 'leave',
                            clickScrolling: true
                        }
                    });
                }
            })
            .catch(error => console.error('Error loading sidebar:', error));
    },

    // Function to set active menu based on current page
    setActiveMenu: function() {
        // Get current page URL
        const currentPage = window.location.pathname.split('/').pop();
        
        // Remove any existing active classes
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
        });
        
        // Add active class to current page's menu item
        const activeLink = document.querySelector(`.nav-link[href="${currentPage}"]`);
        if (activeLink) {
            activeLink.classList.add('active');
        }
    },

    // Function to toggle sidebar mini
    toggleSidebarMini: function() {
        document.body.classList.toggle('sidebar-mini');
    },

    // Logout function
    logout: function() {
        $.ajax({
            url: '/backend/controllers/logout.php',
            type: 'POST',
            success: function() {
                window.location.href = '/index.html';
            },
            error: function() {
                alert('Terjadi kesalahan saat logout.');
            }
        });
    }
};

// Export for use in other files
export default SidebarManager;