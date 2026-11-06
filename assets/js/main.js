/**
 * assets/js/main.js - STRINGS SPORTS Interactive Engine
 */

document.addEventListener('DOMContentLoaded', () => {
    initPageLoader();
    initCyberCanvas();
    initNavbarScroll();
    initMobileDrawer();
    initBackToTop();
    initFlightSimulator();
    initQuickInquiryModal();
    initProductFilters();
    initFaqAccordion();
    initContactFormAjax();
    initQuickInquiryAjax();
});

/* ----------------------------------------------------
   0. STRINGS LOGO PAGE PRELOADER
------------------------------------------------------- */
function initPageLoader() {
    const loader = document.getElementById('pageLoader');
    if (!loader) return;

    const hideLoader = () => {
        setTimeout(() => {
            loader.classList.add('loader-hidden');
        }, 450);
    };

    if (document.readyState === 'complete') {
        hideLoader();
    } else {
        window.addEventListener('load', hideLoader, { once: true });
        // Safety fallback so loader never blocks page if an asset is slow
        setTimeout(hideLoader, 1800);
    }
}


/* ----------------------------------------------------
   1. CYBER STRINGS BACKGROUND CANVAS (Aerodynamic Waves)
------------------------------------------------------- */
function initCyberCanvas() {
    const canvas = document.getElementById('cyberStringsCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let width, height;
    let step = 0;

    function resize() {
        width = canvas.width = window.innerWidth;
        height = canvas.height = window.innerHeight;
    }
    window.addEventListener('resize', resize);
    resize();

    // Wave parameters
    const lines = [
        { color: 'rgba(0, 240, 255, 0.12)', speed: 0.008, amp: 55, freq: 0.002, offset: 0 },
        { color: 'rgba(0, 180, 255, 0.08)', speed: 0.006, amp: 75, freq: 0.0015, offset: 1.5 },
        { color: 'rgba(0, 255, 230, 0.05)', speed: 0.012, amp: 40, freq: 0.003, offset: 3.2 }
    ];

    function draw() {
        ctx.clearRect(0, 0, width, height);

        // Grid lines (subtle cyber net)
        ctx.strokeStyle = 'rgba(0, 240, 255, 0.03)';
        ctx.lineWidth = 1;
        const gridSize = 70;
        for (let x = 0; x < width; x += gridSize) {
            ctx.beginPath();
            ctx.moveTo(x, 0);
            ctx.lineTo(x, height);
            ctx.stroke();
        }
        for (let y = 0; y < height; y += gridSize) {
            ctx.beginPath();
            ctx.moveTo(0, y);
            ctx.lineTo(width, y);
            ctx.stroke();
        }

        // Animated Harmonic String Waves
        lines.forEach(line => {
            ctx.beginPath();
            ctx.strokeStyle = line.color;
            ctx.lineWidth = 1.5;

            const midY = height * 0.45;
            for (let x = 0; x <= width; x += 15) {
                const angle = x * line.freq + step * line.speed + line.offset;
                const y = midY + Math.sin(angle) * line.amp + Math.cos(angle * 0.7) * (line.amp * 0.4);
                if (x === 0) ctx.moveTo(x, y);
                else ctx.lineTo(x, y);
            }
            ctx.stroke();
        });

        step++;
        requestAnimationFrame(draw);
    }
    draw();
}

/* ----------------------------------------------------
   2. NAVBAR SCROLL & BACK TO TOP
------------------------------------------------------- */
function initNavbarScroll() {
    const navbar = document.getElementById('navbar');
    const backToTop = document.getElementById('backToTop');

    window.addEventListener('scroll', () => {
        const scrolled = window.scrollY > 40;
        if (navbar) {
            navbar.classList.toggle('scrolled', scrolled);
        }
        if (backToTop) {
            backToTop.classList.toggle('visible', window.scrollY > 450);
        }
    });
}

function initBackToTop() {
    const backToTop = document.getElementById('backToTop');
    if (!backToTop) return;
    backToTop.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

/* ----------------------------------------------------
   3. MOBILE DRAWER NAVIGATION
------------------------------------------------------- */
function initMobileDrawer() {
    const menuBtn = document.getElementById('mobileMenuBtn');
    const drawer = document.getElementById('mobileDrawer');
    const closeBtn = document.getElementById('drawerCloseBtn');
    const backdrop = document.getElementById('drawerBackdrop');

    if (!menuBtn || !drawer) return;

    function openDrawer() {
        drawer.classList.add('open');
        drawer.setAttribute('aria-hidden', 'false');
        menuBtn.classList.add('active');
        menuBtn.setAttribute('aria-expanded', 'true');
        if (backdrop) backdrop.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
        drawer.classList.remove('open');
        drawer.setAttribute('aria-hidden', 'true');
        menuBtn.classList.remove('active');
        menuBtn.setAttribute('aria-expanded', 'false');
        if (backdrop) backdrop.classList.remove('active');
        document.body.style.overflow = '';
    }

    function toggleDrawer(e) {
        if (e) e.stopPropagation();
        if (drawer.classList.contains('open')) {
            closeDrawer();
        } else {
            openDrawer();
        }
    }

    menuBtn.addEventListener('click', toggleDrawer);
    if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
    if (backdrop) backdrop.addEventListener('click', closeDrawer);

    // Close drawer when any navigation link inside it is clicked
    drawer.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', closeDrawer);
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && drawer.classList.contains('open')) {
            closeDrawer();
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 992 && drawer.classList.contains('open')) {
            closeDrawer();
        }
    });
}

/* ----------------------------------------------------
   4. INTERACTIVE FLIGHT TRAJECTORY SIMULATOR (Aero Lab)
------------------------------------------------------- */
function initFlightSimulator() {
    const canvas = document.getElementById('trajectoryCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');

    let currentSpeed = 77;
    let currentTemp = 'moderate'; // cold, moderate, hot
    let animProgress = 0;
    let animId = null;

    const speedButtons = document.querySelectorAll('.speed-opt-btn');
    const tempSelect = document.getElementById('tempSelector');

    // UI elements to update
    const landingEl = document.getElementById('statLandingDistance');
    const apexEl = document.getElementById('statApexHeight');
    const speedKmhEl = document.getElementById('statInitialSpeed');
    const spinEl = document.getElementById('statSpinRpm');

    function updateSimulationParams() {
        // Adjust simulated physics data
        let baseDist = 12.8; // meters
        let apexHeight = 6.4; // meters
        let speedKmh = 398;
        let spinRpm = 420;

        if (currentSpeed === 76) {
            baseDist -= 0.65;
            speedKmh -= 12;
            spinRpm -= 15;
        } else if (currentSpeed === 78) {
            baseDist += 0.75;
            speedKmh += 15;
            spinRpm += 25;
        }

        if (currentTemp === 'cold') {
            baseDist -= 0.45;
        } else if (currentTemp === 'hot') {
            baseDist += 0.55;
        }

        if (landingEl) landingEl.textContent = baseDist.toFixed(2) + ' m';
        if (apexEl) apexEl.textContent = apexHeight.toFixed(1) + ' m';
        if (speedKmhEl) speedKmhEl.textContent = speedKmh + ' km/h';
        if (spinEl) spinEl.textContent = spinRpm + ' rpm';

        startTrajectoryAnimation(baseDist);
    }

    speedButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            speedButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentSpeed = parseInt(btn.dataset.speed, 10);
            updateSimulationParams();
        });
    });

    if (tempSelect) {
        tempSelect.addEventListener('change', (e) => {
            currentTemp = e.target.value;
            updateSimulationParams();
        });
    }

    function startTrajectoryAnimation(distanceMeters) {
        if (animId) cancelAnimationFrame(animId);
        animProgress = 0;

        function animate() {
            animProgress += 0.02;
            if (animProgress > 1) animProgress = 1;

            drawFlightCurve(distanceMeters, animProgress);

            if (animProgress < 1) {
                animId = requestAnimationFrame(animate);
            }
        }
        animate();
    }

    function drawFlightCurve(distMeters, progress) {
        const w = canvas.width = canvas.parentElement.clientWidth;
        const h = canvas.height = canvas.parentElement.clientHeight;

        ctx.clearRect(0, 0, w, h);

        const groundY = h - 35;
        const startX = 60;
        const maxCourtX = w - 60;
        const netX = (startX + maxCourtX) / 2;
        const netHeight = 55;

        // Draw Court Baseline & Net
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.2)';
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.moveTo(startX, groundY);
        ctx.lineTo(maxCourtX, groundY);
        ctx.stroke();

        // Draw Badminton Net
        ctx.strokeStyle = '#00f0ff';
        ctx.lineWidth = 3;
        ctx.beginPath();
        ctx.moveTo(netX, groundY);
        ctx.lineTo(netX, groundY - netHeight);
        ctx.stroke();

        // Net Mesh icon/marker
        ctx.fillStyle = '#00f0ff';
        ctx.beginPath();
        ctx.arc(netX, groundY - netHeight, 4, 0, Math.PI * 2);
        ctx.fill();

        ctx.fillStyle = '#94a3b8';
        ctx.font = '11px Rajdhani';
        ctx.fillText('REGULATION NET (1.55m)', netX - 45, groundY - netHeight - 8);

        // Boundary lines
        ctx.fillText('BASE LINE', startX - 10, groundY + 18);
        ctx.fillText('DOUBLES BACK LINE', maxCourtX - 60, groundY + 18);

        // Calculate landing X based on speed rating
        // Standard length mapping
        const landingX = startX + ((maxCourtX - startX) * (distMeters / 13.4));
        const apexY = groundY - 170;

        // Draw the full trajectory guide line (dashed)
        ctx.strokeStyle = 'rgba(0, 240, 255, 0.2)';
        ctx.setLineDash([5, 5]);
        ctx.beginPath();
        ctx.moveTo(startX, groundY - 20);
        ctx.quadraticCurveTo((startX + landingX) / 2, apexY, landingX, groundY);
        ctx.stroke();
        ctx.setLineDash([]);

        // Draw the animated actual flight line (solid glow)
        ctx.strokeStyle = '#00f0ff';
        ctx.lineWidth = 3;
        ctx.shadowColor = '#00f0ff';
        ctx.shadowBlur = 15;

        ctx.beginPath();
        ctx.moveTo(startX, groundY - 20);

        const currentT = progress;
        // Bezier points
        const p0 = { x: startX, y: groundY - 20 };
        const p1 = { x: (startX + landingX) / 2, y: apexY };
        const p2 = { x: landingX, y: groundY };

        // De Casteljau subdivision for partial quadratic bezier
        for (let t = 0; t <= currentT; t += 0.02) {
            const bx = (1 - t) * (1 - t) * p0.x + 2 * (1 - t) * t * p1.x + t * t * p2.x;
            const by = (1 - t) * (1 - t) * p0.y + 2 * (1 - t) * t * p1.y + t * t * p2.y;
            ctx.lineTo(bx, by);
        }
        ctx.stroke();
        ctx.shadowBlur = 0;

        // Draw Shuttlecock tip at head of trajectory
        const headX = (1 - currentT) * (1 - currentT) * p0.x + 2 * (1 - currentT) * currentT * p1.x + currentT * currentT * p2.x;
        const headY = (1 - currentT) * (1 - currentT) * p0.y + 2 * (1 - currentT) * currentT * p1.y + currentT * currentT * p2.y;

        ctx.fillStyle = '#ffffff';
        ctx.shadowColor = '#00f0ff';
        ctx.shadowBlur = 20;
        ctx.beginPath();
        ctx.arc(headX, headY, 6, 0, Math.PI * 2);
        ctx.fill();
        ctx.shadowBlur = 0;

        // Shuttlecock rotation angle visualization
        ctx.strokeStyle = '#00f0ff';
        ctx.lineWidth = 1.5;
        ctx.beginPath();
        ctx.arc(headX, headY, 12, 0, Math.PI * 1.5);
        ctx.stroke();
    }

    window.addEventListener('resize', () => {
        updateSimulationParams();
    });

    updateSimulationParams();
}

/* ----------------------------------------------------
   5. QUICK INQUIRY MODAL HANDLER
------------------------------------------------------- */
function initQuickInquiryModal() {
    const modal = document.getElementById('quickInquiryModal');
    if (!modal) return;

    const closeBtn = document.getElementById('closeInquiryModal');
    const modalTitle = document.getElementById('modalProductTitle');
    const subjectInput = document.getElementById('modalSubjectInput');

    window.openInquiryModal = function(productName = 'STRINGS S40') {
        if (modalTitle) modalTitle.textContent = `Order / Inquire for ${productName}`;
        if (subjectInput) subjectInput.value = `Order Inquiry: ${productName}`;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (closeBtn) closeBtn.addEventListener('click', closeModal);

    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });

    // Attach to any element with data-inquire-product attribute
    document.querySelectorAll('[data-inquire-product]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const prodName = btn.dataset.inquireProduct || 'STRINGS Shuttlecock';
            window.openInquiryModal(prodName);
        });
    });
}

/* ----------------------------------------------------
   6. PRODUCT PAGE FILTER & LIVE SEARCH
------------------------------------------------------- */
function initProductFilters() {
    const filterButtons = document.querySelectorAll('.filter-pill-btn');
    const searchInput = document.getElementById('productSearchInput');
    const productCards = document.querySelectorAll('.catalog-product-card');

    if (!productCards.length) return;

    let activeCategory = 'all';
    let searchQuery = '';

    function filterProducts() {
        let visibleCount = 0;
        productCards.forEach(card => {
            const cardCat = (card.dataset.category || '').toLowerCase();
            const cardSpeed = (card.dataset.speed || '').toLowerCase();
            const cardTitle = (card.dataset.title || '').toLowerCase();
            const cardModel = (card.dataset.model || '').toLowerCase();

            const matchesCategory = (activeCategory === 'all') || 
                                    cardCat.includes(activeCategory) || 
                                    cardSpeed === activeCategory;

            const matchesSearch = !searchQuery || 
                                  cardTitle.includes(searchQuery) || 
                                  cardModel.includes(searchQuery) || 
                                  cardCat.includes(searchQuery);

            if (matchesCategory && matchesSearch) {
                card.style.display = 'flex';
                card.style.animation = 'fadeInCard 0.4s ease forwards';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const noResultsEl = document.getElementById('noProductsNotice');
        if (noResultsEl) {
            noResultsEl.style.display = (visibleCount === 0) ? 'block' : 'none';
        }
    }

    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            filterButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            activeCategory = btn.dataset.filter.toLowerCase();
            filterProducts();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            searchQuery = e.target.value.trim().toLowerCase();
            filterProducts();
        });
    }
}

/* ----------------------------------------------------
   7. FAQ ACCORDION
------------------------------------------------------- */
function initFaqAccordion() {
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const header = item.querySelector('.faq-header');
        if (!header) return;
        header.addEventListener('click', () => {
            const isOpen = item.classList.contains('open');
            // Close others if desired, or keep toggle
            faqItems.forEach(i => i.classList.remove('open'));
            if (!isOpen) {
                item.classList.add('open');
            }
        });
    });
}

/* ----------------------------------------------------
   8. CONTACT FORM & INQUIRY AJAX SUBMISSION
------------------------------------------------------- */
function initContactFormAjax() {
    const contactForm = document.getElementById('contact-form');
    if (!contactForm) return;

    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const alertContainer = document.getElementById('alert-msg');
        const submitBtn = document.getElementById('submit_contact_btn') || contactForm.querySelector('button[type="submit"]');
        const originalBtnHtml = submitBtn ? submitBtn.innerHTML : '';

        // Form fields
        const nameInput = contactForm.querySelector('[name="name"]');
        const emailInput = contactForm.querySelector('[name="email"]');
        const messageInput = contactForm.querySelector('[name="message"]');

        const nameVal = nameInput ? nameInput.value.trim() : '';
        const emailVal = emailInput ? emailInput.value.trim() : '';
        const messageVal = messageInput ? messageInput.value.trim() : '';

        // Client-side validation
        if (!nameVal) {
            showAlert(alertContainer, 'danger', 'Please enter your full name or club organization.');
            if (nameInput) nameInput.focus();
            return;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailVal || !emailRegex.test(emailVal)) {
            showAlert(alertContainer, 'danger', 'Please enter a valid email address.');
            if (emailInput) emailInput.focus();
            return;
        }

        if (!messageVal) {
            showAlert(alertContainer, 'danger', 'Please enter your message or order requirements.');
            if (messageInput) messageInput.focus();
            return;
        }

        // Show loading state on button
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> <span>Transmitting Inquiry...</span>';
        }

        const formData = new FormData(contactForm);

        fetch(contactForm.action || 'mailapi/contact.php', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            return response.json().then(data => ({
                ok: response.ok,
                status: response.status,
                data: data
            })).catch(() => ({
                ok: response.ok,
                status: response.status,
                data: { message: response.statusText }
            }));
        })
        .then(res => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }

            if (res.ok && res.data.status === 'success') {
                showAlert(alertContainer, 'success', res.data.message || 'Thank you! Your inquiry has been received.');
                contactForm.reset();
            } else {
                const errMsg = (res.data && res.data.message) ? res.data.message : 'Error transmitting inquiry. Please try again.';
                showAlert(alertContainer, 'danger', errMsg);
            }
        })
        .catch(err => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }
            showAlert(alertContainer, 'danger', 'Network error: Unable to transmit your inquiry. Please try again.');
        });
    });
}

function initQuickInquiryAjax() {
    const quickForm = document.getElementById('quickInquiryForm');
    if (!quickForm) return;

    quickForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const alertContainer = document.getElementById('modal-alert-msg');
        const submitBtn = document.getElementById('modalSubmitBtn') || quickForm.querySelector('button[type="submit"]');
        const originalBtnHtml = submitBtn ? submitBtn.innerHTML : '';

        const nameInput = quickForm.querySelector('[name="name"]');
        const emailInput = quickForm.querySelector('[name="email"]');

        const nameVal = nameInput ? nameInput.value.trim() : '';
        const emailVal = emailInput ? emailInput.value.trim() : '';

        if (!nameVal) {
            showAlert(alertContainer, 'danger', 'Please enter your name or club name.');
            if (nameInput) nameInput.focus();
            return;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailVal || !emailRegex.test(emailVal)) {
            showAlert(alertContainer, 'danger', 'Please enter a valid email address.');
            if (emailInput) emailInput.focus();
            return;
        }

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> <span>Submitting...</span>';
        }

        const formData = new FormData(quickForm);

        fetch(quickForm.action || 'mailapi/contact.php', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            return response.json().then(data => ({
                ok: response.ok,
                status: response.status,
                data: data
            })).catch(() => ({
                ok: response.ok,
                status: response.status,
                data: { message: response.statusText }
            }));
        })
        .then(res => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }

            if (res.ok && res.data.status === 'success') {
                showAlert(alertContainer, 'success', res.data.message || 'Inquiry submitted successfully!');
                quickForm.reset();
                setTimeout(() => {
                    const modal = document.getElementById('quickInquiryModal');
                    if (modal) modal.classList.remove('active');
                    if (alertContainer) alertContainer.innerHTML = '';
                    document.body.style.overflow = '';
                }, 2800);
            } else {
                const errMsg = (res.data && res.data.message) ? res.data.message : 'Error submitting inquiry. Please try again.';
                showAlert(alertContainer, 'danger', errMsg);
            }
        })
        .catch(err => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }
            showAlert(alertContainer, 'danger', 'Network error. Please try again.');
        });
    });
}

function showAlert(container, type, message) {
    if (!container) return;
    const iconClass = type === 'success' ? 'fa-circle-check' : 'fa-triangle-exclamation';
    const titleText = type === 'success' ? 'Success!' : 'Error:';
    container.innerHTML = `
        <div class="alert alert-${type}" style="margin-bottom: 1.5rem; animation: fadeInAlert 0.3s ease;">
            <i class="fa-solid ${iconClass}" style="font-size: 1.35rem;"></i>
            <div>
                <strong>${titleText}</strong> ${message}
            </div>
        </div>
    `;
    container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}
