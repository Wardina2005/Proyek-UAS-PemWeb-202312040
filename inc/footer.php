<!-- Content ends here -->
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JavaScript -->
<?php if (is_admin()): ?>
<script src="<?= get_base_url() ?>/assets/js/hamburger-admin.js"></script>
<?php else: ?>
<script src="<?= get_base_url() ?>/assets/js/hamburger-user.js"></script>
<?php endif; ?>

<script src="<?= get_base_url() ?>/assets/js/main.js"></script>
<script src="<?= get_base_url() ?>/assets/js/alert.js"></script>
<script src="<?= get_base_url() ?>/assets/js/validate.js"></script>

<script>
// Modern page transitions
document.addEventListener('DOMContentLoaded', function() {
    // Add fade-in animation to page content
    const contentWrapper = document.querySelector('.content-wrapper');
    if (contentWrapper) {
        contentWrapper.style.opacity = '0';
        contentWrapper.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            contentWrapper.style.transition = 'all 0.6s ease';
            contentWrapper.style.opacity = '1';
            contentWrapper.style.transform = 'translateY(0)';
        }, 100);
    }
    
    // Add modern card animations
    const cards = document.querySelectorAll('.modern-card, .card, .dashboard-item');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 200 + (index * 100));
    });
});

// Smooth page navigation
document.addEventListener('click', function(e) {
    const link = e.target.closest('a[href]');
    if (link && link.href && !link.href.includes('#') && !link.target) {
        const href = link.href;
        if (href.includes(window.location.origin)) {
            e.preventDefault();
            
            // Add exit animation
            document.body.style.transition = 'all 0.3s ease';
            document.body.style.opacity = '0.7';
            document.body.style.transform = 'scale(0.98)';
            
            setTimeout(() => {
                window.location.href = href;
            }, 200);
        }
    }
});
</script>

</body>
</html>
