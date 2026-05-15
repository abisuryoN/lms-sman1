/**
 * LMS SMAN 1 - Core Application JS
 */

// ── Sidebar & Layout Logic ──────────────────

/**
 * Toggles the mobile sidebar drawer
 */
function toggleSidebar() {
    const sidebar = document.getElementById('sidebarWrapper');
    const overlay = document.getElementById('sidebarOverlay');
    if (!sidebar) return;

    if (sidebar.style.display === 'none' || !sidebar.classList.contains('open')) {
        sidebar.style.display = 'block';
        setTimeout(() => {
            sidebar.classList.add('open');
            overlay.classList.add('show');
        }, 10);
    } else {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
        setTimeout(() => sidebar.style.display = 'none', 300);
    }
}

/**
 * Toggles the desktop sidebar collapse state
 */
function toggleSidebarCollapse() {
    document.body.classList.toggle('sidebar-collapsed');
    const isCollapsed = document.body.classList.contains('sidebar-collapsed');
    localStorage.setItem('sidebar_collapsed', isCollapsed ? 'yes' : 'no');
}

/**
 * Toggles the upward-opening user dropdown in the sidebar
 */
function toggleBottomUserDropdown(e) {
    if (e) e.stopPropagation();
    const dd = document.getElementById('bottomUserDropdown');
    const section = document.getElementById('sidebarUserSection');
    if (dd) {
        dd.classList.toggle('show');
        section.classList.toggle('open');
    }
    // Close top navbar dropdown if open
    const topDd = document.getElementById('userDropdown');
    if (topDd) topDd.classList.remove('show');
}

// ── Custom Select System ────────────────────

/**
 * Initializes custom dropdowns for all native select elements
 */
function initCustomSelects() {
    const selects = document.querySelectorAll('select:not([data-no-custom])');
    selects.forEach(select => {
        if (select.closest('.custom-select-wrapper') || select.classList.contains('swal2-select')) return;
        
        const wrapper = document.createElement('div');
        wrapper.className = 'custom-select-wrapper';
        
        if (select.classList.contains('form-control-sm')) wrapper.classList.add('select-sm');
        if (select.getAttribute('style')) {
            wrapper.setAttribute('style', select.getAttribute('style'));
        }
        
        select.parentNode.insertBefore(wrapper, select);
        wrapper.appendChild(select);

        const trigger = document.createElement('div');
        trigger.className = 'custom-select-trigger';
        const initialText = select.options[select.selectedIndex]?.text || 'Pilih...';
        trigger.innerHTML = `<span class="trigger-text">${initialText}</span><i class="fas fa-chevron-down trigger-icon"></i>`;
        wrapper.appendChild(trigger);

        const optionsContainer = document.createElement('div');
        optionsContainer.className = 'custom-select-options';
        wrapper.appendChild(optionsContainer);

        function updateOptions() {
            optionsContainer.innerHTML = '';
            Array.from(select.options).forEach((option, index) => {
                const opt = document.createElement('div');
                opt.className = 'custom-select-option';
                if (option.selected) opt.classList.add('selected');
                opt.innerHTML = `<span>${option.text}</span>${option.selected ? '<i class="fas fa-check" style="font-size:10px"></i>' : ''}`;
                
                opt.addEventListener('click', (e) => {
                    e.stopPropagation();
                    select.selectedIndex = index;
                    select.dispatchEvent(new Event('change', { bubbles: true }));
                    trigger.querySelector('.trigger-text').innerText = option.text;
                    optionsContainer.classList.remove('show');
                    trigger.classList.remove('open');
                });
                optionsContainer.appendChild(opt);
            });
        }

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = optionsContainer.classList.contains('show');
            
            document.querySelectorAll('.custom-select-options').forEach(c => {
                if (c !== optionsContainer) c.classList.remove('show');
            });
            document.querySelectorAll('.custom-select-trigger').forEach(t => {
                if (t !== trigger) t.classList.remove('open');
            });

            if (!isOpen) {
                updateOptions();
                const rect = trigger.getBoundingClientRect();
                const spaceBelow = window.innerHeight - rect.bottom;
                const listHeight = Math.min(select.options.length * 40, 220);
                
                if (spaceBelow < listHeight && rect.top > listHeight) {
                    optionsContainer.classList.add('open-upwards');
                } else {
                    optionsContainer.classList.remove('open-upwards');
                }
                optionsContainer.classList.add('show');
                trigger.classList.add('open');
                
                const selected = optionsContainer.querySelector('.selected');
                if (selected) {
                    optionsContainer.scrollTop = selected.offsetTop - 10;
                }
            } else {
                optionsContainer.classList.remove('show');
                trigger.classList.remove('open');
            }
        });

        select.addEventListener('change', () => {
            trigger.querySelector('.trigger-text').innerText = select.options[select.selectedIndex]?.text || '';
        });
    });
}

// ── Initialization ──────────────────────────

document.addEventListener('DOMContentLoaded', function () {
    // 1. Sidebar Persistance
    if (localStorage.getItem('sidebar_collapsed') === 'yes' && window.innerWidth > 768) {
        document.body.classList.add('sidebar-collapsed');
    }

    // 2. Custom Selects
    initCustomSelects();

    // 2.5 Sidebar Auto-Scroll to Active Link
    const activeSidebarLink = document.querySelector('.sidebar-menu .sidebar-link.active');
    const sidebarMenu = document.querySelector('.sidebar-menu');
    if (activeSidebarLink && sidebarMenu) {
        const linkRect = activeSidebarLink.getBoundingClientRect();
        const menuRect = sidebarMenu.getBoundingClientRect();
        if (linkRect.bottom > menuRect.bottom || linkRect.top < menuRect.top) {
            sidebarMenu.scrollTop = activeSidebarLink.offsetTop - (menuRect.height / 2) + (linkRect.height / 2);
        }
    }

    // 3. Global Click Handlers (Close Dropdowns)
    document.addEventListener('click', function (e) {
        // Bottom User Dropdown
        const bottomDd = document.getElementById('bottomUserDropdown');
        const bottomSection = document.getElementById('sidebarUserSection');
        if (bottomDd && !e.target.closest('#sidebarUserSection')) {
            bottomDd.classList.remove('show');
            if (bottomSection) bottomSection.classList.remove('open');
        }

        // Top Navbar Dropdown
        const topDd = document.getElementById('userDropdown');
        if (topDd && !e.target.closest('.navbar-user')) {
            topDd.classList.remove('show');
        }

        // Custom Selects
        document.querySelectorAll('.custom-select-options').forEach(c => c.classList.remove('show'));
        document.querySelectorAll('.custom-select-trigger').forEach(t => t.classList.remove('open'));
    });

    // 4. Global Form Validation (Anti-Browser Popup)
    initGlobalValidation();
});

/**
 * Global Form Validation System
 */
function initGlobalValidation() {
    // Disable native validation popups
    const applyNoValidate = () => {
        document.querySelectorAll('form:not([novalidate])').forEach(form => {
            form.setAttribute('novalidate', true);
        });
    };

    applyNoValidate();
    // Re-apply if content changes (for Livewire/AJAX)
    const observer = new MutationObserver(applyNoValidate);
    observer.observe(document.body, { childList: true, subtree: true });

    // Handle Global Submit
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.tagName !== 'FORM' || form.hasAttribute('data-no-validation')) return;

        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        // Clear previous errors
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        
        requiredFields.forEach(field => {
            let isFieldValid = true;
            
            // Basic required check
            if (field.type === 'checkbox' || field.type === 'radio') {
                const group = form.querySelectorAll(`input[name="${field.name}"]`);
                const checked = Array.from(group).some(input => input.checked);
                if (!checked) isFieldValid = false;
            } else if (field.value.trim() === '') {
                isFieldValid = false;
            }
            
            if (!isFieldValid) {
                isValid = false;
                field.classList.add('is-invalid');
                
                // Add error message
                const errorMsg = document.createElement('div');
                errorMsg.className = 'invalid-feedback';
                
                // Get Field Label
                let labelText = '';
                const label = form.querySelector(`label[for="${field.id}"]`) || field.closest('label');
                if (label) {
                    labelText = label.innerText.replace('*', '').replace(':', '').trim();
                } else {
                    let rawName = field.getAttribute('placeholder') || field.getAttribute('name') || 'Field ini';
                    // Clean up common database names like kelas_id -> Kelas
                    labelText = rawName.replace('_id', '').replace('_', ' ').trim();
                    labelText = labelText.charAt(0).toUpperCase() + labelText.slice(1);
                }

                // Format Message
                if (field.type === 'file') {
                    errorMsg.innerText = "File wajib diunggah.";
                } else if (field.tagName === 'SELECT') {
                    errorMsg.innerText = `${labelText} wajib dipilih.`;
                } else {
                    errorMsg.innerText = `${labelText} wajib diisi.`;
                }
                
                // Position Message
                if (field.closest('.custom-select-wrapper')) {
                    const wrapper = field.closest('.custom-select-wrapper');
                    const trigger = wrapper.querySelector('.custom-select-trigger');
                    if (trigger) trigger.classList.add('is-invalid');
                    wrapper.appendChild(errorMsg);
                } else if (field.parentNode.classList.contains('input-group')) {
                    field.parentNode.parentNode.appendChild(errorMsg);
                } else {
                    field.parentNode.appendChild(errorMsg);
                }
                
                errorMsg.style.display = 'block';
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            e.stopPropagation();
            const firstError = form.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    }, true); // Capture phase to beat other listeners

    // Clear error on input
    document.addEventListener('input', function(e) {
        const field = e.target;
        field.classList.remove('is-invalid');
        
        // Handle custom select
        if (field.closest('.custom-select-wrapper')) {
            const trigger = field.closest('.custom-select-wrapper').querySelector('.custom-select-trigger');
            if (trigger) trigger.classList.remove('is-invalid');
        }

        const parent = field.closest('.custom-select-wrapper') || field.parentNode;
        const error = parent.querySelector('.invalid-feedback');
        if (error) error.remove();
    }, true);
}
