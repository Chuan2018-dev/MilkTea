// Milk Tea Shop - Admin JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Sidebar toggle
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.admin-sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    }

    // Initialize tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    // Initialize popovers
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));

    // Confirm delete
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    });

    // Image preview
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const preview = document.getElementById(this.id + 'Preview');
            if (preview && this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });

    // Select all checkboxes
    const selectAllCheckbox = document.getElementById('select-all');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            document.querySelectorAll('.select-item').forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    // Bulk action
    const bulkActionForm = document.getElementById('bulk-action-form');
    if (bulkActionForm) {
        bulkActionForm.addEventListener('submit', function(e) {
            const selectedItems = document.querySelectorAll('.select-item:checked');
            if (selectedItems.length === 0) {
                e.preventDefault();
                alert('Please select at least one item.');
                return false;
            }
            
            const action = document.getElementById('bulk-action').value;
            if (!action) {
                e.preventDefault();
                alert('Please select an action.');
                return false;
            }
            
            if (!confirm('Are you sure you want to perform this action on selected items?')) {
                e.preventDefault();
                return false;
            }
        });
    }

    // Admin auto-sync (polls backend and refreshes when data changes)
    const syncUrl = document.body?.dataset?.adminSyncUrl;
    const syncInterval = Math.max(parseInt(document.body?.dataset?.adminSyncInterval || '5000', 10), 3000);
    let hasUnsavedChanges = false;

    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('input', () => {
            hasUnsavedChanges = true;
        });
        form.addEventListener('change', () => {
            hasUnsavedChanges = true;
        });
        form.addEventListener('submit', () => {
            hasUnsavedChanges = false;
        });
    });

    if (syncUrl) {
        let lastHash = null;
        let isPolling = false;

        const fetchSyncHash = async () => {
            const response = await fetch(syncUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) {
                throw new Error(`Sync request failed with status ${response.status}`);
            }

            const data = await response.json();
            return data.hash || null;
        };

        const startAutoSync = async () => {
            try {
                lastHash = await fetchSyncHash();
            } catch (error) {
                console.error('Initial admin sync check failed:', error);
            }

            setInterval(async () => {
                if (isPolling || document.hidden || hasUnsavedChanges) {
                    return;
                }

                isPolling = true;

                try {
                    const nextHash = await fetchSyncHash();

                    if (lastHash && nextHash && lastHash !== nextHash) {
                        showToast('New updates detected. Syncing...', 'info');
                        setTimeout(() => {
                            window.location.reload();
                        }, 350);
                        return;
                    }

                    if (nextHash) {
                        lastHash = nextHash;
                    }
                } catch (error) {
                    console.error('Admin auto-sync failed:', error);
                } finally {
                    isPolling = false;
                }
            }, syncInterval);
        };

        startAutoSync();
    }
});

// Show toast notification
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
    }

    const toastId = 'toast-' + Date.now();
    const bgClass = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info';
    
    const toastHtml = `
        <div id="${toastId}" class="toast align-items-center ${bgClass} text-white" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    document.getElementById('toast-container').insertAdjacentHTML('beforeend', toastHtml);
    
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
    toast.show();
    
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}

// Format currency
function formatCurrency(amount) {
    return '$' + parseFloat(amount).toFixed(2);
}

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Print order
function printOrder(orderId) {
    const printWindow = window.open('/admin/orders/' + orderId + '/print', '_blank', 'width=400,height=600');
    printWindow.focus();
}

// Update order status
function updateOrderStatus(orderId, status) {
    fetch('/admin/orders/' + orderId + '/status', {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Order status updated!', 'success');
            location.reload();
        } else {
            showToast(data.message || 'Failed to update status', 'error');
        }
    })
    .catch(error => {
        console.error('Error updating status:', error);
        showToast('Failed to update order status', 'error');
    });
}

// Update payment status
function updatePaymentStatus(orderId, paymentStatus) {
    fetch('/admin/orders/' + orderId + '/payment-status', {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ payment_status: paymentStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Payment status updated!', 'success');
            location.reload();
        } else {
            showToast(data.message || 'Failed to update payment status', 'error');
        }
    })
    .catch(error => {
        console.error('Error updating payment status:', error);
        showToast('Failed to update payment status', 'error');
    });
}
