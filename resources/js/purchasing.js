document.addEventListener('DOMContentLoaded', () => {

    // --- Modal Logic (Suppliers Page) ---
    const backdrop = document.getElementById('supplier-modal-backdrop');
    const modalContent = document.getElementById('supplier-modal-content');
    const openModalBtn = document.getElementById('open-supplier-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const cancelModalBtn = document.getElementById('cancel-modal-btn');
    const modalForm = document.getElementById('supplier-form');

    // MOD: Use class and animation logic
    const openModal = () => {
        if (backdrop) {
            // 1. Set display: flex immediately to start the transition
            backdrop.style.display = 'flex';
            // 2. Add 'is-open' class after a slight delay (or immediately) to trigger CSS transition
            setTimeout(() => {
                backdrop.classList.add('is-open');
            }, 10);
        }
    };

    // MOD: Use class and animation logic
    const closeModal = () => {
        if (backdrop) {
            backdrop.classList.remove('is-open');
            // Wait for the CSS transition (350ms defined in CSS) to finish before setting display: none
            setTimeout(() => {
                backdrop.style.display = 'none';
            }, 350); 
        }
        if (modalForm) modalForm.reset();
        // Reset modal title (if we were editing)
        const modalTitle = document.getElementById('modal-title');
        if (modalTitle) modalTitle.textContent = 'Add New Supplier';
    };

    if (openModalBtn) {
        openModalBtn.addEventListener('click', openModal);
    }
    
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeModal);
    }

    if (cancelModalBtn) {
        cancelModalBtn.addEventListener('click', closeModal);
    }

    if (backdrop) {
        backdrop.addEventListener('click', (e) => {
            // Check if the click is on the backdrop itself, not the content
            if (e.target === backdrop) {
                closeModal();
            }
        });
    }

    // Handle Edit buttons (dummy logic)
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', () => {
            // In a real app, you'd fetch data and fill the form
            const modalTitle = document.getElementById('modal-title');
            if (modalTitle) modalTitle.textContent = 'Edit Supplier';
            // Dummy data
            if(document.getElementById('company_name')) document.getElementById('company_name').value = 'บริษัท ยาดี จำกัด';
            if(document.getElementById('tax_id')) document.getElementById('tax_id').value = '1234567890123';
            if(document.getElementById('contact_person')) document.getElementById('contact_person').value = 'คุณสมชาย';
            openModal();
        });
    });


    // --- Sliding Toggle Filter Logic (PO Page) ---
    const slider = document.getElementById('po-status-filter');
    if (slider) {
        const buttons = slider.querySelectorAll('.toggle-btn');
        const activeBtn = slider.querySelector('.toggle-btn.active');

        const setSliderPosition = (targetButton) => {
            if (!targetButton) return;
            const targetOffset = targetButton.offsetLeft;
            const targetWidth = targetButton.offsetWidth;
            
            slider.style.setProperty('--slider-left', `${targetOffset}px`);
            slider.style.setProperty('--slider-width', `${targetWidth}px`);
        };

        // 1. Set initial position on load
        setSliderPosition(activeBtn);

        // 2. Add 'slider-ready' class *after* a short delay to enable transitions
        setTimeout(() => {
            slider.classList.add('slider-ready');
        }, 50);


        // 3. Add click event listeners
        buttons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                // Remove active class from all
                buttons.forEach(b => b.classList.remove('active'));
                
                // Add active class to clicked button
                const clickedBtn = e.currentTarget;
                clickedBtn.classList.add('active');
                
                // Move the slider
                setSliderPosition(clickedBtn);

                // In a real app, you'd also trigger the data filtering
                const filterValue = clickedBtn.dataset.filter;
                console.log('Filtering by:', filterValue);
            });
        });

        // 4. (Optional but good) Recalculate on window resize
        window.addEventListener('resize', () => {
            const currentActiveBtn = slider.querySelector('.toggle-btn.active');
            setSliderPosition(currentActiveBtn);
        });
    }


    // --- Goods Received (GR) Workflow Logic ---
    const poSearchView = document.getElementById('po-search-view');
    const poReceiveView = document.getElementById('po-receive-view');
    const searchPoBtn = document.getElementById('search-po-btn');
    const backToSearchBtn = document.getElementById('back-to-search-btn');
    const poLinks = document.querySelectorAll('.po-link');
    const goToReceiveBtn = document.getElementById('go-to-receive'); // Button from PO page

    const showReceiveView = () => {
        if (poSearchView) poSearchView.style.display = 'none';
        if (poReceiveView) poReceiveView.style.display = 'block';
        window.scrollTo(0, 0); // Scroll to top
    };

    const showSearchView = () => {
        if (poSearchView) poSearchView.style.display = 'block';
        if (poReceiveView) poReceiveView.style.display = 'none';
    };

    if (searchPoBtn) {
        searchPoBtn.addEventListener('click', () => {
            // In a real app, you'd validate the PO number first
            // and fetch its data. Here, we just toggle the view.
            showReceiveView();
        });
    }
    
    if (backToSearchBtn) {
        backToSearchBtn.addEventListener('click', showSearchView);
    }

    // Handle clicks from the "Awaiting" list
    poLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            // const poNumber = e.currentTarget.dataset.po;
            // Fetch data for poNumber...
            showReceiveView();
        });
    });

    // Handle click from PO page "Receive" button
    if (goToReceiveBtn) {
        goToReceiveBtn.addEventListener('click', (e) => {
            e.preventDefault();
            // This is on a different page, so we'd normally just redirect
            // For this demo, we assume this is a shortcut *if* it were on the same page
            // In reality, this would be a link:
            // window.location.href = '/purchasing/goods-received?po=PO-2025-002';
            
            // If we are already on the goods-received page (e.g. from PO list)
            // we simulate the click
            if (poSearchView) {
                showReceiveView();
            } else {
                // If this script is loaded on the PO page, it should redirect
                console.log('Redirecting to Goods Received page for this PO...');
                // Simulating a redirect for demo
                console.log('Redirecting to Goods Received page...');
            }
        });
    }

});