// ─── SELECTORS ───────────────────────────────────
const modalOverlay = document.getElementById('modalOverlay');
const btnOpenModal = document.getElementById('btnOpenModal');
const btnCloseModal = document.getElementById('btnCloseModal');
const btnCancelModal = document.getElementById('btnCancelModal');
const addUserForm = document.getElementById('addUserForm');

const umTableBody = document.getElementById('umTableBody');
const umSearch = document.getElementById('umSearch');
const umRoleFilter = document.getElementById('umRoleFilter');

// ─── MODAL OPEN/CLOSE ────────────────────────────
function openModal() {
    modalOverlay.classList.add('open');
}

function closeModal() {
    modalOverlay.classList.remove('open');
}

btnOpenModal.addEventListener('click', openModal);
btnCloseModal.addEventListener('click', closeModal);
btnCancelModal.addEventListener('click', closeModal);

// Close modal on overlay click
modalOverlay.addEventListener('click', (e) => {
    if (e.target === modalOverlay) closeModal();
});

// ─── FILTER / SEARCH ─────────────────────────────
function filterTable() {
    const searchTerm = umSearch.value.toLowerCase();
    const roleTerm = umRoleFilter.value;

    const rows = umTableBody.querySelectorAll('tr');
    rows.forEach(row => {
        const name = row.dataset.name;
        const email = row.dataset.email;
        const role = row.dataset.role;

        const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
        const matchesRole = roleTerm === '' || role === roleTerm;

        row.style.display = (matchesSearch && matchesRole) ? '' : 'none';
    });
}

umSearch.addEventListener('input', filterTable);
umRoleFilter.addEventListener('change', filterTable);

// ─── HELPER FUNCTIONS ───────────────────────────
function getInitials(name) {
    const parts = name.trim().split(' ');
    let initials = '';
    parts.forEach(p => { if (p !== '') initials += p[0].toUpperCase(); });
    return initials.slice(0,2);
}

function roleBadgeClass(role) {
    switch(role) {
        case 'evacuee': return 'badge-blue';
        case 'center_manager': return 'badge-green';
        case 'coordinator': return 'badge-orange';
        default: return 'badge-yellow';
    }
}

function roleLabel(role) {
    switch(role) {
        case 'evacuee': return 'Evacuee';
        case 'center_manager': return 'Center Manager';
        case 'coordinator': return 'Coordinator';
        default: return role.charAt(0).toUpperCase() + role.slice(1);
    }
}

// ─── ADD USER (CLIENT-SIDE DEMO) ───────────────
addUserForm.addEventListener('submit', (e) => {
    e.preventDefault();

    const formData = new FormData(addUserForm);
    const full_name = formData.get('full_name').trim();
    const email = formData.get('email').trim();
    const phone = formData.get('phone').trim() || '—';
    const address = formData.get('address').trim() || '—';
    const role = formData.get('role');

    const tr = document.createElement('tr');
    tr.dataset.name = full_name.toLowerCase();
    tr.dataset.email = email.toLowerCase();
    tr.dataset.role = role;

    tr.innerHTML = `
        <td>
            <div class="um-name-cell">
                <div class="um-avatar">${getInitials(full_name)}</div>
                <div>
                    <div class="um-name">${full_name}</div>
                    <div class="um-email">${email}</div>
                </div>
            </div>
        </td>
        <td><span class="badge ${roleBadgeClass(role)}">${roleLabel(role)}</span></td>
        <td class="hide-sm">${phone}</td>
        <td class="hide-sm">${address}</td>
        <td class="hide-sm">${new Date().toLocaleDateString()}</td>
        <td>
            <div class="um-actions">
                <button class="um-act-btn" title="Edit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                </button>
                <button class="um-act-btn del" title="Delete">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                    </svg>
                </button>
            </div>
        </td>
    `;

    umTableBody.appendChild(tr);
    addUserForm.reset();
    closeModal();
    filterTable(); // in case filter/search is active
});

// ─── DELETE BUTTON (CLIENT-SIDE DEMO) ──────────
umTableBody.addEventListener('click', (e) => {
    if (e.target.closest('.del')) {
        const tr = e.target.closest('tr');
        if (confirm('Are you sure you want to delete this user?')) {
            tr.remove();
        }
    }
});

// ─── EDIT BUTTON (DEMO HOOK) ───────────────────
umTableBody.addEventListener('click', (e) => {
    if (e.target.closest('.um-act-btn') && !e.target.closest('.del')) {
        alert('Edit user functionality can be implemented here.');
    }
});
