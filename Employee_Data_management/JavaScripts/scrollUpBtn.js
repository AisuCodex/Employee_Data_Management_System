   // Show the scroll to top button when scrolled 50px from the top
   window.onscroll = function() {
    const scrollToTopBtn = document.getElementById('scrollToTopBtn');
    if (window.scrollY > 50) {
        scrollToTopBtn.style.display = 'block';
    } else {
        scrollToTopBtn.style.display = 'none';
    }
};

// Scroll to top function
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}