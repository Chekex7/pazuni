document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar collapse
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    
    menuToggle.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
    });
    
    // Handle submenu toggle
    const hasSubmenu = document.querySelectorAll('.has-submenu');
    
    hasSubmenu.forEach(item => {
        const link = item.querySelector('a');
        link.addEventListener('click', function(e) {
            if (!sidebar.classList.contains('collapsed')) {
                e.preventDefault();
                item.classList.toggle('active');
            }
        });
    });
    
    // Responsive sidebar for mobile
    function handleResponsive() {
        if (window.innerWidth <= 768) {
            sidebar.classList.add('collapsed');
        }
    }
    
    // Initial check
    handleResponsive();
    
    // Check on resize
    window.addEventListener('resize', handleResponsive);
});