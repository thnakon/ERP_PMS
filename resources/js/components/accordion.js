/**
 * Oboun ERP - Accordion System
 */

const AccordionSystem = {
    /**
     * Toggle an accordion item
     * @param {string} id - The accordion content ID
     */
    toggle(id) {
        const content = document.getElementById(id);
        const icon = document.getElementById(`icon-${id}`);

        if (!content) return;

        if (content.style.maxHeight) {
            // Close
            content.style.maxHeight = null;
            content.classList.remove('open');
            if (icon) {
                icon.style.transform = 'rotate(0deg)';
                icon.classList.remove('text-ios-blue');
            }
        } else {
            // Open
            content.style.maxHeight = content.scrollHeight + 'px';
            content.classList.add('open');
            if (icon) {
                icon.style.transform = 'rotate(180deg)';
                icon.classList.add('text-ios-blue');
            }
        }
    },

    /**
     * Close all accordions in a group
     * @param {string} groupId - The accordion group container ID
     */
    closeAll(groupId) {
        const group = document.getElementById(groupId);
        if (!group) return;

        const contents = group.querySelectorAll('.accordion-content');
        contents.forEach(content => {
            content.style.maxHeight = null;
            content.classList.remove('open');

            const icon = document.getElementById(`icon-${content.id}`);
            if (icon) {
                icon.style.transform = 'rotate(0deg)';
                icon.classList.remove('text-ios-blue');
            }
        });
    }
};

// Global function for inline usage
function toggleAccordion(id) {
    AccordionSystem.toggle(id);
}

export { AccordionSystem, toggleAccordion };
