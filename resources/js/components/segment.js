/**
 * Oboun ERP - Segment Control System
 * Apple-style sliding segments/tabs
 */

const SegmentSystem = {
    /**
     * Move the segment indicator
     * @param {number} index - Index of the selected segment (0-based)
     * @param {HTMLElement} btn - The clicked button element
     * @param {string} bgId - ID of the sliding background element
     */
    move(index, btn, bgId = 'segment-bg') {
        const bg = document.getElementById(bgId);
        if (!bg) return;

        const container = btn.parentElement;
        const buttons = container.querySelectorAll('button');
        const totalButtons = buttons.length;

        // Calculate position based on index
        // For 3 items: positions are roughly 0.375rem, 33.33%, 66.66%
        if (totalButtons === 3) {
            if (index === 0) bg.style.left = '0.375rem';
            else if (index === 1) bg.style.left = '33.33%';
            else if (index === 2) bg.style.left = '66.66%';
        } else {
            // Generic calculation
            const percentage = (100 / totalButtons) * index;
            bg.style.left = index === 0 ? '0.375rem' : `${percentage}%`;
        }

        // Update button styles
        buttons.forEach(b => {
            b.classList.remove('font-semibold', 'text-gray-900');
            b.classList.add('font-medium', 'text-gray-500');
        });

        btn.classList.remove('font-medium', 'text-gray-500');
        btn.classList.add('font-semibold', 'text-gray-900');

        // Dispatch custom event
        const event = new CustomEvent('segment-change', {
            detail: { index, button: btn }
        });
        container.dispatchEvent(event);
    }
};

// Global function for inline usage
function moveSegment(index, btn) {
    SegmentSystem.move(index, btn);
}

export { SegmentSystem, moveSegment };
