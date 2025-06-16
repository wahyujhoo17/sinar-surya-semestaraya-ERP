{{-- Toggle Children Function for Chart of Accounts --}}
<script>
    // Define function in global scope
    window.toggleChildren = function(button) {
        const row = button.closest('tr');
        const parentId = row.getAttribute('data-id');
        const childRows = document.querySelectorAll(`tr[data-parent="${parentId}"]`);
        const icon = button.querySelector('svg');

        let isExpanded = false;

        // Check if children are currently visible
        if (childRows.length > 0) {
            isExpanded = !childRows[0].classList.contains('hidden');
        }

        // Toggle visibility of child rows
        childRows.forEach(childRow => {
            if (isExpanded) {
                // Hide this child and all its descendants
                childRow.classList.add('hidden');

                // Also hide any grandchildren
                const childId = childRow.getAttribute('data-id');
                const grandchildRows = document.querySelectorAll(`tr[data-parent="${childId}"]`);
                grandchildRows.forEach(grandchildRow => {
                    grandchildRow.classList.add('hidden');
                });

                // Reset child toggle icons
                const childToggle = childRow.querySelector('.toggle-children svg');
                if (childToggle) {
                    childToggle.classList.remove('rotate-90');
                }
            } else {
                // Show only direct children
                childRow.classList.remove('hidden');
            }

            console.log('Toggling child with ID:', childRow.getAttribute('data-id'));
        });

        // Rotate the icon based on expanded state
        if (icon) {
            if (isExpanded) {
                icon.style.transform = '';
                icon.classList.remove('rotate-90');
            } else {
                icon.style.transform = 'rotate(90deg)';
                icon.classList.add('rotate-90');
            }
        }

        return false; // Prevent default button behavior
    };

    // Add DOMContentLoaded event to make sure the function is available
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Toggle function ready');
    });
</script>

// Search and filter functionality
document.addEventListener('DOMContentLoaded', function() {
const searchInput = document.getElementById('searchInput');
const filterKategori = document.getElementById('filterKategori');
const filterStatus = document.getElementById('filterStatus');
const accountsTable = document.getElementById('accountsTable');

// Function to filter the table
function filterTable() {
const searchText = searchInput.value.toLowerCase();
const kategoriFilter = filterKategori.value;
const statusFilter = filterStatus.value;

// Get all parent rows (level 0)
const parentRows = accountsTable.querySelectorAll('tr.parent-row');

parentRows.forEach(row => {
// Check if the parent row matches the filters
const kode = row.querySelector('td:nth-child(1)').textContent.trim().toLowerCase();
const nama = row.querySelector('td:nth-child(2)').textContent.trim().toLowerCase();
const kategori = row.getAttribute('data-kategori');
const status = row.getAttribute('data-status');

const matchesSearch = kode.includes(searchText) || nama.includes(searchText);
const matchesKategori = kategoriFilter === '' || kategori === kategoriFilter;
const matchesStatus = statusFilter === '' || status === statusFilter;

const showParent = matchesSearch && matchesKategori && matchesStatus;

// Toggle parent row visibility
if (showParent) {
row.classList.remove('hidden');
} else {
row.classList.add('hidden');
}

// Reset child rows visibility when filtering
const parentId = row.getAttribute('data-id');
const childRows = accountsTable.querySelectorAll(`tr[data-parent="${parentId}"]`);

// Hide all child rows when filtering
childRows.forEach(childRow => {
childRow.classList.add('hidden');
});

// Reset toggle icon
const toggleIcon = row.querySelector('.toggle-children svg');
if (toggleIcon) {
toggleIcon.classList.remove('rotate-90');
}
});
}

// Add event listeners
if (searchInput) {
searchInput.addEventListener('input', filterTable);
}

if (filterKategori) {
filterKategori.addEventListener('change', filterTable);
}

if (filterStatus) {
filterStatus.addEventListener('change', filterTable);
}
});
</script>
