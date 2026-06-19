// Auto-dismiss flash alerts
document.querySelectorAll('.alert').forEach((el) => {
    setTimeout(() => {
        el.style.transition = 'opacity .6s ease';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 650);
    }, 4500);
});

// Highlight active nav link based on current URL
const currentPath = window.location.pathname;
document.querySelectorAll('.sidebar .nav-link').forEach((link) => {
    const href = link.getAttribute('href');
    if (href && currentPath.startsWith(href) && href !== '/') {
        link.classList.add('active');
    }
});
