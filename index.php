<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gutscheinverwaltung</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Datepicker CSS -->
    <link rel="stylesheet" href="assets/css/flatpickr.min.css">
    
    <style>
        .amount-tile {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .amount-tile:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .amount-tile.selected {
            background-color: #0d6efd;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex align-items-center justify-content-center mb-4">
            <img src="assets/images/logo.png" alt="Logo" height="50" class="me-3">
            <h1 class="mb-0">Gutscheinverwaltung</h1>
        </div>
        <!-- Suchfilter -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label for="date-filter" class="form-label">Datum</label>
                        <input type="text" class="form-control" id="date-filter">
                    </div>
                    <div class="col-md-4">
                        <label for="serial-filter" class="form-label">Seriennummer</label>
                        <input type="text" class="form-control" id="serial-filter">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button class="btn btn-primary me-2" id="search-btn">Suchen</button>
                        <button class="btn btn-secondary" id="reset-filter-btn">Filter zurücksetzen</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aktionsbuttons -->
        <div class="mb-4">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newVoucherModal">
                Neuer Gutschein
            </button>
        </div>

        <!-- Ergebnistabelle -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Datum</th>
                        <th>Seriennummer</th>
                        <th>Betrag</th>
                        <th>Bemerkung</th>
                        <th>Aktionen</th>
                    </tr>
                </thead>
                <tbody id="voucher-table-body">
                    <!-- Wird dynamisch gefüllt -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal für neuen Gutschein -->
    <div class="modal fade" id="newVoucherModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Neuer Gutschein</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Betrag auswählen</label>
                            <div class="row" id="amount-tiles">
                                <!-- Wird dynamisch gefüllt -->
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="new-date" class="form-label">Datum</label>
                        <input type="text" class="form-control" id="new-date">
                    </div>
                    <div class="mb-3">
                        <label for="new-serial" class="form-label">Seriennummer</label>
                        <input type="text" class="form-control" id="new-serial">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="button" class="btn btn-primary" id="save-voucher">Speichern</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal für Bemerkungen -->
    <div class="modal fade" id="editRemarksModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bemerkung bearbeiten</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit-voucher-id">
                    <div class="mb-3">
                        <label for="voucher-remarks" class="form-label">Bemerkung</label>
                        <textarea class="form-control" id="voucher-remarks" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="button" class="btn btn-primary" id="save-remarks">Speichern</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/flatpickr.js"></script>
    <script src="assets/js/flatpickr-de.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>