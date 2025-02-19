document.addEventListener('DOMContentLoaded', function() {
    // Datepicker Initialisierung
    flatpickr("#date-filter", {
        locale: "de",
        dateFormat: "d.m.Y",
        allowInput: true,
        onChange: function(selectedDates, dateStr, instance) {
            // Nur suchen, wenn die Auswahl über den Kalender erfolgte
            if (dateStr && !instance.isOpen) {
                searchVouchers();
            }
        }
    });

    flatpickr("#new-date", {
        locale: "de",
        dateFormat: "d.m.Y",
        defaultDate: "today",
        allowInput: true
    });

    // Beträge für Kacheln laden
    loadAmountTiles();

    // Event Listener für Suche
    document.getElementById('search-btn').addEventListener('click', searchVouchers);

    // Event Listener für neuen Gutschein speichern
    document.getElementById('save-voucher').addEventListener('click', saveNewVoucher);

    // Event Listener für Bemerkungen speichern
    document.getElementById('save-remarks').addEventListener('click', saveRemarks);

    // Event Listener für Reset Filter Button
    document.getElementById('reset-filter-btn').addEventListener('click', resetFilter);

    // Enter-Taste für Datum-Filter
    document.getElementById('date-filter').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchVouchers();
        }
    });

    // Enter-Taste für Seriennummer-Filter
    document.getElementById('serial-filter').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchVouchers();
        }
    });
});

// Laden der Betrags-Kacheln
function loadAmountTiles() {
    fetch('api/get_amounts.php')
        .then(response => response.json())
        .then(amounts => {
            const container = document.getElementById('amount-tiles');
            container.innerHTML = amounts.map(amount => `
                <div class="col-4 mb-3">
                    <div class="card amount-tile text-center py-3" data-amount="${Number(amount.value)}">
                        <div class="card-body">
                            <h5 class="card-title">${Number(amount.value).toFixed(2)} CHF</h5>
                        </div>
                    </div>
                </div>
            `).join('');

            // Event Listener für Kacheln
            document.querySelectorAll('.amount-tile').forEach(tile => {
                tile.addEventListener('click', function() {
                    document.querySelectorAll('.amount-tile').forEach(t => t.classList.remove('selected'));
                    this.classList.add('selected');
                });
            });
        });
}

// Suche nach Gutscheinen
function searchVouchers() {
    const date = document.getElementById('date-filter').value;
    const serial = document.getElementById('serial-filter').value;

    fetch('api/search_vouchers.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ date, serial })
    })
    .then(response => response.json())
    .then(vouchers => {
        const tbody = document.getElementById('voucher-table-body');
        tbody.innerHTML = vouchers.map(voucher => {
            const amount = Number(voucher.amount);
            
            return `
                <tr>
                    <td>${formatDate(voucher.issue_date)}</td>
                    <td>${voucher.serial_number}</td>
                    <td>${amount.toFixed(2)} CHF</td>
                    <td>${voucher.remarks || ''}</td>
                    <td>
                        <button class="btn btn-sm btn-primary me-2 edit-remarks" data-id="${voucher.id}">
                            Bearbeiten
                        </button>
                        <button class="btn btn-sm btn-danger archive-btn" data-id="${voucher.id}">
                            Löschen
                        </button>
                    </td>
                </tr>
            `;
        }).join('');

        // Event Listener für Bearbeiten-Buttons
        document.querySelectorAll('.edit-remarks').forEach(button => {
            button.addEventListener('click', function() {
                const voucherId = this.getAttribute('data-id');
                openEditRemarksModal(voucherId);
            });
        });

        // Event Listener für Archivieren-Buttons
        document.querySelectorAll('.archive-btn').forEach(button => {
            button.addEventListener('click', function() {
                const voucherId = this.getAttribute('data-id');
                archiveVoucher(voucherId);
            });
        });
    });
}

// Neuen Gutschein speichern
function saveNewVoucher() {
    const selectedTile = document.querySelector('.amount-tile.selected');
    if (!selectedTile) {
        alert('Bitte wählen Sie einen Betrag aus');
        return;
    }

    const amount = selectedTile.getAttribute('data-amount');
    const date = document.getElementById('new-date').value;
    const serial = document.getElementById('new-serial').value;

    if (!date || !serial) {
        alert('Bitte füllen Sie alle Felder aus');
        return;
    }

    fetch('api/save_voucher.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ amount, date, serial })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            $('#newVoucherModal').modal('hide');
            document.getElementById('new-serial').value = '';
            document.querySelectorAll('.amount-tile').forEach(tile => tile.classList.remove('selected'));
            searchVouchers();
        } else {
            alert('Fehler beim Speichern des Gutscheins');
        }
    });
}

// Bemerkungsmodal öffnen
function openEditRemarksModal(voucherId) {
    fetch(`api/get_voucher.php?id=${voucherId}`)
        .then(response => response.json())
        .then(voucher => {
            document.getElementById('edit-voucher-id').value = voucherId;
            document.getElementById('voucher-remarks').value = voucher.remarks || '';
            $('#editRemarksModal').modal('show');
        });
}

// Bemerkungen speichern
function saveRemarks() {
    const voucherId = document.getElementById('edit-voucher-id').value;
    const remarks = document.getElementById('voucher-remarks').value;

    fetch('api/save_remarks.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            id: voucherId,
            remarks: remarks
        })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            $('#editRemarksModal').modal('hide');
            searchVouchers();
        } else {
            alert('Fehler beim Speichern der Bemerkung');
        }
    });
}

// Einzelnen Gutschein archivieren
function archiveVoucher(voucherId) {
    if (confirm('Möchten Sie diesen Gutschein wirklich löschen?')) {
        fetch('api/archive_vouchers.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ ids: [voucherId] })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                searchVouchers();
            } else {
                alert('Fehler beim löschen des Gutscheins');
            }
        });
    }
}

// Filter zurücksetzen
function resetFilter() {
    document.getElementById('date-filter').value = '';
    document.getElementById('serial-filter').value = '';
    document.getElementById('voucher-table-body').innerHTML = '';
}

// Datum formatieren
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('de-CH');
}

// Modal Reset Event
document.getElementById('newVoucherModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('new-serial').value = '';
    document.querySelectorAll('.amount-tile').forEach(tile => tile.classList.remove('selected'));
});