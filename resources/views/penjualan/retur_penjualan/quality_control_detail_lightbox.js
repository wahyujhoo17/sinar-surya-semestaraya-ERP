// Direct modal opener function (added as a global function for inline onclick)
window.openImageModalDirect = function (imageSrc, imageCaption, imageIndex) {
    console.log(`DIRECT OPEN MODAL CALLED with src: ${imageSrc}, caption: ${imageCaption}, index: ${imageIndex}`);

    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const modalCaption = document.getElementById('modalCaption');
    const imageCounter = document.getElementById('imageCounter');

    if (!modal || !modalImage) {
        console.error('Modal elements not found for direct open');
        alert('Error: Modal elements not found. Please try again.');
        return;
    }

    // Show modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';

    // Clear previous image first
    modalImage.src = '';
    modalImage.style.opacity = '0';

    // Set new image
    setTimeout(() => {
        modalImage.src = imageSrc;
        if (modalCaption) modalCaption.textContent = imageCaption || 'Image';

        // Handle counter if available
        const galleryImages = document.querySelectorAll('.qc-gallery-item');
        if (imageCounter && galleryImages.length > 0) {
            imageCounter.textContent = `${imageIndex + 1} / ${galleryImages.length}`;
        }
    }, 50);

    // When image loads, make it visible
    modalImage.onload = function () {
        console.log(`Direct modal image loaded: ${this.src}`);
        this.style.transition = 'opacity 0.3s ease-in-out';
        this.style.opacity = '1';

        // Apply proper sizing
        this.style.maxWidth = '95vw';
        this.style.maxHeight = '85vh';
        this.style.objectFit = 'contain';
        this.style.margin = '0 auto';
    };

    return false; // Prevent default action
};

// Direct modal close function
window.closeImageModalDirect = function () {
    console.log('DIRECT CLOSE MODAL CALLED');
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');

    if (!modal) {
        console.error('Modal element not found for direct close');
        return;
    }

    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';

    if (modalImage) {
        modalImage.src = '';
        modalImage.style.opacity = '0';
    }

    return false; // Prevent default action
};

// Add keyboard support for direct modal approach
document.addEventListener('keydown', function (event) {
    const modal = document.getElementById('imageModal');

    if (modal && !modal.classList.contains('hidden')) {
        if (event.key === 'Escape') {
            window.closeImageModalDirect();
        }
    }
});
