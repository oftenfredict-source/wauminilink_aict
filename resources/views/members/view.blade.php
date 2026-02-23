@extends('layouts.index')

@section('styles')
    <style>
        /* Force modal and backdrop to appear on top */
        .modal-backdrop.show {
            z-index: 2050 !important;
        }

        .modal.show {
            z-index: 2100 !important;
            display: block !important;
        }

        #archiveMemberModal {
            z-index: 2100 !important;
        }

        /* Members Sub-Nav Styles */
        .members-subnav .nav-link {
            transition: opacity 0.2s ease, border-color 0.2s ease;
            border-bottom: 2px solid transparent;
            padding-bottom: 0.5rem;
        }

        .members-subnav .nav-link:hover {
            opacity: 1 !important;
            border-bottom: 2px solid rgba(255, 255, 255, 0.7);
        }

        .members-subnav .navbar-toggler:focus {
            box-shadow: none;
        }



        /* Compact Filter Section Styles */
        #filtersForm {
            transition: all 0.3s ease;
        }

        #filtersForm .card-header {
            transition: background-color 0.2s ease;
        }

        #filterBody {
            transition: all 0.3s ease;
        }

        /* Desktop: Always show filters, make header non-clickable */
        @media (min-width: 769px) {
            .filter-header {
                cursor: default !important;
                pointer-events: none !important;
            }

            .filter-header .fa-chevron-down {
                display: none !important;
            }

            #filterBody {
                display: block !important;
            }
        }

        /* Mobile: Collapsible */
        @media (max-width: 768px) {
            .filter-header {
                cursor: pointer !important;
                pointer-events: auto !important;
            }

            #filterBody {
                display: none;
            }

            #filterToggleIcon {
                font-size: 1.1rem !important;
                width: 24px !important;
                height: 24px !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                cursor: pointer !important;
            }
        }

        /* Compact Filter Section Styles */
        #filtersForm {
            transition: all 0.3s ease;
        }

        #filtersForm .card-header {
            transition: background-color 0.2s ease;
        }

        #filterBody {
            transition: all 0.3s ease;
        }

        /* Desktop: Always show filters, make header non-clickable */
        @media (min-width: 769px) {
            .filter-header {
                cursor: default !important;
                pointer-events: none !important;
            }

            .filter-header .fa-chevron-down {
                display: none !important;
            }

            #filterBody {
                display: block !important;
            }
        }

        /* Mobile: Collapsible */
        @media (max-width: 768px) {
            .filter-header {
                cursor: pointer !important;
                pointer-events: auto !important;
            }

            #filterBody {
                display: none;
            }

            #filterToggleIcon {
                font-size: 1.1rem !important;
                width: 24px !important;
                height: 24px !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                cursor: pointer !important;
                transition: transform 0.3s ease !important;
                flex-shrink: 0 !important;
            }
        }

        /* Compact Actions Section Styles */
        .actions-card {
            transition: all 0.3s ease;
        }

        .actions-card .card-header {
            user-select: none;
            transition: background-color 0.2s ease;
        }

        .actions-card .card-header:hover {
            background-color: #f8f9fa !important;
        }

        .actions-card .card-header i {
            transition: transform 0.3s ease;
        }

        .actions-card .card-header h1 {
            color: #212529 !important;
        }

        .actions-card .card-header h1 i {
            color: #212529 !important;
        }

        #actionsBody {
            transition: all 0.3s ease;
        }

        #actionsBody .btn-sm {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }

        /* Desktop: Always show actions, make header non-clickable */
        @media (min-width: 769px) {
            .actions-header {
                cursor: default !important;
                pointer-events: none !important;
            }

            .actions-header .fa-chevron-down {
                display: none !important;
            }

            #actionsBody {
                display: block !important;
            }
        }

        /* Desktop Sidebar Toggle Button - Ensure proper size */
        @media (min-width: 769px) {
            #sidebarToggle {
                font-size: 1.5rem !important;
                padding: 0.5rem !important;
                min-width: 44px !important;
                min-height: 44px !important;
            }

            #sidebarToggle i {
                font-size: 1.5rem !important;
            }
        }

        /* Mobile: Collapsible */
        @media (max-width: 768px) {
            .actions-header {
                cursor: pointer !important;
                pointer-events: auto !important;
            }

            #actionsBody {
                display: none;
                transition: all 0.3s ease;
            }

            #actionsToggleIcon {
                display: block !important;
                font-size: 1.1rem !important;
                width: 24px !important;
                height: 24px !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                cursor: pointer !important;
                transition: transform 0.3s ease !important;
                flex-shrink: 0 !important;
            }
        }

        #filtersForm .input-group-text {
            border-right: none;
        }

        #filtersForm .form-control:focus,
        #filtersForm .form-select:focus {
            border-left: none;
            box-shadow: none;
        }

        #filtersForm .form-control:focus+.input-group-text,
        #filtersForm .input-group:focus-within .input-group-text {
            border-color: #86b7fe;
        }

        .logo-white-section {
            background-color: white !important;
            border-radius: 8px;
            margin: 8px 0;
            padding: 8px 16px !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .logo-white-section:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .navbar-brand .logo {
            transition: all 0.3s ease;
        }

        .navbar-brand .logo:hover {
            transform: scale(1.05);
        }

        .navbar-brand {
            min-height: 60px;
            display: flex !important;
            align-items: center !important;
        }

        .card-header {
            color: white !important;
            font-weight: 600;
        }

        /* Override for actions card header - white background needs dark text */
        .actions-card .card-header {
            color: #212529 !important;
        }

        .actions-card .card-header h1 {
            color: #212529 !important;
        }

        .actions-card .card-header h1 i {
            color: #212529 !important;
        }

        /* Reduce gap between topbar and content */
        #layoutSidenav_content main {
            padding-top: 0 !important;
        }

        .container-fluid {
            padding-top: 0 !important;
        }

        .card .small.text-white-50 {
            color: white !important;
            font-weight: 500;
        }

        /* Interactive table styling for details tables */
        .table.interactive-table tbody tr {
            transition: background-color 0.2s ease, box-shadow 0.2s ease;
        }

        .table.interactive-table tbody tr:hover {
            background-color: #f8f9ff;
        }

        .table.interactive-table tbody tr td:first-child {
            border-left: 4px solid #940000;
        }

        /* Slightly wider member details modal */
        #memberDetailsModal .modal-dialog {
            max-width: 700px;
        }

        #memberDetailsModal .modal-footer {
            background: linear-gradient(135deg, #940000 0%, #ff4d4d 100%);
            border-top: 0;
            color: #ffffff;
        }

        #memberDetailsModal .modal-footer a.emca-link {
            color: #ffffff;
            text-decoration: none;
        }

        #memberDetailsModal .modal-footer a.emca-link:hover {
            text-decoration: underline;
            opacity: 0.95;
        }

        /* QR styling */
        #inlineQrImg {
            border: 3px solid #940000;
            border-radius: 8px;
            padding: 4px;
            background: #ffffff;
        }

        #qrSpinner {
            width: 2.5rem;
            height: 2.5rem;
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            #layoutSidenav_content {
                margin-top: -50px !important;
            }

            .container-fluid {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }

            /* Actions card improvements */
            .actions-card {
                margin-bottom: 1rem !important;
                transition: all 0.3s ease;
            }

            .actions-card .card-header {
                padding: 0.5rem 0.75rem !important;
                user-select: none;
                transition: background-color 0.2s ease;
            }

            .actions-card .card-header:hover {
                background-color: #f8f9fa !important;
            }

            .actions-card .card-header h1 {
                font-size: 1.25rem !important;
                margin: 0 !important;
            }

            .actions-card .card-body {
                padding: 0.75rem !important;
            }

            #actionsBody .btn-sm {
                font-size: 0.8125rem !important;
                padding: 0.375rem 0.625rem !important;
            }

            /* Header adjustments */
            h1 {
                font-size: 1.25rem !important;
            }

            /* Hide button text on mobile, show only icons */
            .btn-mobile-icon-only {
                padding: 0.5rem !important;
                min-width: 44px !important;
                height: 44px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }

            .btn-mobile-icon-only .btn-text {
                display: none !important;
            }

            .btn-mobile-icon-only i {
                margin: 0 !important;
                font-size: 1rem !important;
            }

            /* View toggle buttons */
            .btn-group {
                width: 100% !important;
            }

            .btn-group .btn {
                flex: 1 !important;
            }

            /* Make tabs scrollable on mobile */
            .nav-tabs {
                overflow-x: auto;
                flex-wrap: nowrap;
                -webkit-overflow-scrolling: touch;
                display: flex !important;
                border-bottom: 2px solid #dee2e6;
                padding-bottom: 0;
            }

            .nav-tabs .nav-item {
                white-space: nowrap;
                flex-shrink: 0;
                min-width: auto;
            }

            .nav-tabs .nav-link {
                padding: 0.75rem 1rem !important;
                font-size: 0.875rem !important;
                border-radius: 0.5rem 0.5rem 0 0 !important;
            }

            /* Tab content */
            .tab-content {
                padding: 1rem 0.5rem !important;
                border: none !important;
            }

            /* Table responsive improvements */
            .table-responsive {
                border: none;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table {
                font-size: 0.875rem;
                min-width: 600px;
            }

            .table th,
            .table td {
                padding: 0.5rem 0.5rem;
                white-space: nowrap;
            }

            /* Card view improvements */
            .card-view-item {
                margin-bottom: 1rem;
            }

            /* Modal improvements */
            .modal-dialog {
                margin: 0.5rem !important;
                max-width: calc(100% - 1rem) !important;
            }

            .modal-dialog.modal-lg {
                max-width: calc(100% - 1rem) !important;
            }

            .modal-content {
                border-radius: 0.5rem;
            }

            .modal-header,
            .modal-body,
            .modal-footer {
                padding: 1rem !important;
            }

            /* Sidebar Toggle Button - Match size with other toggle buttons */
            #sidebarToggle {
                font-size: 1.1rem !important;
                padding: 0.5rem !important;
                min-width: 40px !important;
                min-height: 40px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                margin-right: 0.75rem !important;
                margin-left: 0 !important;
                order: -1 !important;
            }

            #sidebarToggle i {
                font-size: 1.1rem !important;
            }

            /* Ensure navbar has proper padding on mobile to prevent cutoff */
            .sb-topnav {
                padding-left: 0.75rem !important;
                padding-right: 0.5rem !important;
                overflow-x: hidden !important;
                position: relative !important;
                max-width: 100vw !important;
                width: 100% !important;
            }

            /* Ensure navbar container doesn't cut off content */
            body.sb-nav-fixed .sb-topnav {
                margin-left: 0 !important;
                width: 100% !important;
                max-width: 100vw !important;
            }

            /* Ensure navbar content doesn't overflow */
            .sb-topnav .navbar-nav,
            .sb-topnav .d-flex {
                max-width: 100% !important;
                overflow-x: hidden !important;
            }

            /* Welcome message on mobile */
            .sb-topnav .navbar-text {
                font-size: 0.85rem !important;
                margin-left: 0.5rem !important;
                margin-right: auto !important;
                flex: 1 !important;
                min-width: 0 !important;
                white-space: nowrap !important;
            }

            /* Ensure profile dropdown menu is hidden by default, visible when active */
            .sb-topnav .dropdown-menu {
                position: absolute !important;
                z-index: 1050 !important;
                right: 0 !important;
                left: auto !important;
                margin-top: 0.5rem !important;
                min-width: 180px !important;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
                background-color: #fff !important;
                border: 1px solid rgba(0, 0, 0, 0.15) !important;
                border-radius: 0.375rem !important;
                display: none !important;
                opacity: 0 !important;
                visibility: hidden !important;
            }

            .sb-topnav .dropdown-menu.show {
                display: block !important;
                opacity: 1 !important;
                visibility: visible !important;
            }

            /* Ensure dropdown items are visible */
            .sb-topnav .dropdown-menu .dropdown-item {
                padding: 0.5rem 1rem !important;
                font-size: 0.9rem !important;
                white-space: nowrap !important;
                color: #212529 !important;
                display: block !important;
            }

            .sb-topnav .dropdown-menu .dropdown-item:hover {
                background-color: #f8f9fa !important;
            }

            /* Hide logo on mobile */
            .sb-topnav .navbar-brand,
            .sb-topnav .logo-white-section {
                display: none !important;
            }

            /* Ensure navbar nav items are visible and don't shrink */
            .sb-topnav .navbar-nav {
                flex-shrink: 0 !important;
                display: flex !important;
                align-items: center !important;
                margin-left: auto !important;
            }

            .sb-topnav .navbar-nav .nav-item {
                flex-shrink: 0 !important;
                display: flex !important;
                align-items: center !important;
            }

            /* Profile dropdown icon - ensure it's always visible */
            #navbarDropdown {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                padding: 0.5rem !important;
                min-width: 40px !important;
                min-height: 40px !important;
            }

            #navbarDropdown i {
                font-size: 1.1rem !important;
                display: block !important;
            }

            /* Notification icon spacing on mobile */
            #notificationDropdown {
                margin-right: 0.5rem !important;
            }

            /* Filter section improvements - Compact */
            #filtersForm {
                margin-bottom: 1rem !important;
            }

            #filtersForm .card-header {
                padding: 0.75rem 1rem !important;
            }

            #filtersForm .card-body {
                padding: 1rem !important;
            }

            #filtersForm .form-label {
                font-size: 0.75rem !important;
                margin-bottom: 0.25rem !important;
            }

            #filtersForm .form-control,
            #filtersForm .form-select {
                font-size: 0.875rem !important;
                padding: 0.375rem 0.5rem !important;
            }

            #filtersForm .input-group-sm {
                height: auto;
            }

            #filtersForm .btn-sm {
                padding: 0.375rem 0.75rem !important;
                font-size: 0.875rem !important;
            }

            /* Action buttons in table */
            .table .btn {
                padding: 0.375rem 0.5rem;
                font-size: 0.75rem;
            }

            .table .btn i {
                margin: 0 !important;
            }

            .table .btn .btn-text {
                display: none;
            }

            /* Better spacing */
            .mb-3 {
                margin-bottom: 1rem !important;
            }

            .mt-4 {
                margin-top: 1rem !important;
            }
        }

        .card-header.bg-gradient-primary {
            background: linear-gradient(45deg, #940000, #ff4d4d) !important;
        }

        .btn-primary {
            background-color: #940000 !important;
            border-color: #940000 !important;
        }

        .btn-primary:hover {
            background-color: #7a0000 !important;
            border-color: #7a0000 !important;
        }

        .btn-outline-primary {
            color: #940000 !important;
            border-color: #940000 !important;
        }

        .btn-outline-primary:hover {
            background-color: #940000 !important;
            color: white !important;
        }

        .bg-primary {
            background-color: #940000 !important;
        }

        .text-primary {
            color: #940000 !important;
        }

        .nav-tabs .nav-link.active {
            color: #940000 !important;
            border-bottom: 2px solid #940000 !important;
        }

        @media (max-width: 576px) {

            /* Extra small devices */
            #layoutSidenav_content {
                margin-top: -50px !important;
            }

            .container-fluid {
                padding-left: 0.25rem !important;
                padding-right: 0.25rem !important;
            }

            /* Actions card on mobile */
            .actions-card .card-header {
                padding: 0.5rem 0.625rem !important;
            }

            /* Toggle icons - Extra small mobile */
            #actionsToggleIcon,
            #filterToggleIcon {
                font-size: 1rem !important;
                width: 22px !important;
                height: 22px !important;
            }

            .actions-card .card-header h1 {
                font-size: 1.1rem !important;
            }

            .actions-card .card-body {
                padding: 0.75rem 0.5rem !important;
            }

            #actionsBody .d-flex {
                gap: 0.5rem !important;
            }

            #actionsBody .btn-sm {
                font-size: 0.75rem !important;
                padding: 0.375rem 0.5rem !important;
            }

            h2 {
                font-size: 1.25rem !important;
            }

            .btn {
                font-size: 0.8125rem !important;
                padding: 0.5rem 0.625rem !important;
            }

            .btn-mobile-icon-only {
                padding: 0.5rem !important;
                min-width: 40px !important;
                height: 40px !important;
            }

            .table {
                font-size: 0.75rem;
            }

            .tab-badge {
                font-size: 0.7rem !important;
                padding: 2px 6px !important;
                margin-left: 4px !important;
            }

            /* Sidebar Toggle Button - Extra Small Mobile */
            #sidebarToggle {
                font-size: 1rem !important;
                padding: 0.45rem !important;
                min-width: 38px !important;
                min-height: 38px !important;
                margin-right: 0.5rem !important;
                margin-left: 0 !important;
                order: -1 !important;
            }

            #sidebarToggle i {
                font-size: 1rem !important;
            }

            /* Ensure navbar has proper padding on extra small mobile */
            .sb-topnav {
                padding-left: 0.5rem !important;
                padding-right: 0.25rem !important;
                overflow-x: hidden !important;
                position: relative !important;
                max-width: 100vw !important;
                width: 100% !important;
            }

            /* Ensure navbar container doesn't cut off content on extra small */
            body.sb-nav-fixed .sb-topnav {
                margin-left: 0 !important;
                width: 100% !important;
                max-width: 100vw !important;
            }

            /* Ensure navbar content doesn't overflow on extra small */
            .sb-topnav .navbar-nav,
            .sb-topnav .d-flex {
                max-width: 100% !important;
                overflow-x: hidden !important;
            }

            /* Welcome message on extra small mobile */
            .sb-topnav .navbar-text {
                font-size: 0.8rem !important;
                margin-left: 0.25rem !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
            }

            /* Ensure profile dropdown menu is hidden by default on extra small mobile */
            .sb-topnav .dropdown-menu {
                position: absolute !important;
                z-index: 1050 !important;
                right: 0 !important;
                left: auto !important;
                margin-top: 0.5rem !important;
                min-width: 160px !important;
                max-width: calc(100vw - 1rem) !important;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
                background-color: #fff !important;
                border: 1px solid rgba(0, 0, 0, 0.15) !important;
                border-radius: 0.375rem !important;
                display: none !important;
                opacity: 0 !important;
                visibility: hidden !important;
            }

            .sb-topnav .dropdown-menu.show {
                display: block !important;
                opacity: 1 !important;
                visibility: visible !important;
            }

            /* Ensure dropdown items are visible on extra small */
            .sb-topnav .dropdown-menu .dropdown-item {
                padding: 0.5rem 1rem !important;
                font-size: 0.85rem !important;
                white-space: nowrap !important;
                color: #212529 !important;
                display: block !important;
            }

            .sb-topnav .dropdown-menu .dropdown-item:hover {
                background-color: #f8f9fa !important;
            }

            /* Ensure navbar doesn't clip dropdown on extra small */
            .sb-topnav {
                overflow: visible !important;
            }

            .sb-topnav .navbar-nav {
                overflow: visible !important;
            }

            /* Hide logo on extra small mobile */
            .sb-topnav .navbar-brand,
            .sb-topnav .logo-white-section {
                display: none !important;
            }

            /* Ensure navbar nav items are visible on extra small mobile */
            .sb-topnav .navbar-nav {
                flex-shrink: 0 !important;
                display: flex !important;
                align-items: center !important;
                margin-left: auto !important;
            }

            .sb-topnav .navbar-nav .nav-item {
                flex-shrink: 0 !important;
                display: flex !important;
                align-items: center !important;
            }

            /* Profile dropdown icon - ensure it's visible on extra small */
            #navbarDropdown {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                padding: 0.45rem !important;
                min-width: 38px !important;
                min-height: 38px !important;
            }

            #navbarDropdown i {
                font-size: 1rem !important;
                display: block !important;
            }

            /* Notification icon spacing on extra small mobile */
            #notificationDropdown {
                margin-right: 0.5rem !important;
            }

            /* Make tabs more compact */
            .nav-tabs {
                padding: 0 0.25rem;
            }

            .nav-tabs .nav-link {
                padding: 0.625rem 0.75rem !important;
                font-size: 0.8125rem !important;
            }

            /* Tab content */
            .tab-content {
                padding: 0.75rem 0.25rem !important;
            }

            /* Mobile card improvements */
            .mobile-card-row {
                padding: 0.75rem !important;
            }

            .mobile-card-row .card-body-row {
                grid-template-columns: 1fr !important;
                gap: 0.5rem !important;
            }

            /* Card view full width on mobile */
            .card-view-item {
                margin-bottom: 0.75rem;
            }

            /* Better spacing for filters */
            .card-body {
                padding: 0.75rem 0.5rem !important;
            }

            /* Filter form improvements - Extra compact on mobile */
            #filtersForm .card-body {
                padding: 0.75rem 0.5rem !important;
            }

            #filtersForm .row.g-2 {
                margin: 0 !important;
            }

            #filtersForm .row.g-2>[class*="col-"] {
                padding-left: 0.375rem !important;
                padding-right: 0.375rem !important;
                margin-bottom: 0.5rem !important;
            }

            #filtersForm .mb-3 {
                margin-bottom: 0.75rem !important;
            }

            #filtersForm .form-label {
                font-size: 0.7rem !important;
            }

            #filtersForm .form-control,
            #filtersForm .form-select {
                font-size: 0.8125rem !important;
                padding: 0.25rem 0.5rem !important;
            }

            #filtersForm .input-group-sm .input-group-text {
                padding: 0.25rem 0.5rem !important;
                font-size: 0.8125rem !important;
            }

            /* Button group full width */
            .d-flex.gap-2 {
                width: 100% !important;
            }

            .d-flex.gap-2>* {
                flex: 1 1 auto !important;
                min-width: 0 !important;
            }

            /* Modal full screen on very small devices */
            .modal-dialog {
                margin: 0 !important;
                max-width: 100% !important;
            }

            .modal-dialog.modal-lg {
                max-width: 100% !important;
                height: 100vh !important;
            }

            .modal-content {
                border-radius: 0 !important;
                height: 100% !important;
                display: flex !important;
                flex-direction: column !important;
            }

            .modal-body {
                flex: 1 !important;
                overflow-y: auto !important;
            }

            /* Table horizontal scroll indicator */
            .table-responsive {
                position: relative;
            }

            .table-responsive::after {
                content: '← Swipe to see more →';
                display: block;
                text-align: center;
                padding: 0.5rem;
                color: #6c757d;
                font-size: 0.7rem;
                background: #f8f9fa;
                border-top: 1px solid #dee2e6;
                font-weight: 500;
            }

            /* Modal full screen on very small devices */
            .modal-dialog {
                margin: 0 !important;
                max-width: 100% !important;
                height: 100vh !important;
            }

            .modal-content {
                border-radius: 0 !important;
                height: 100% !important;
                display: flex !important;
                flex-direction: column !important;
            }

            .modal-body {
                flex: 1 !important;
                overflow-y: auto !important;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Navbar Brand - Hidden on Mobile -->
    <div class="container-fluid px-4 pt-0">

        <!-- Bootstrap Members Section Navigation -->
        <nav class="navbar navbar-expand-md mb-3 mt-1 rounded shadow-sm members-subnav"
            style="background: linear-gradient(135deg, #940000 0%, #7a0000 100%);">
            <div class="container-fluid px-3">
                <span class="navbar-brand text-white fw-bold py-1" style="font-size:1rem;">
                    <i class="fas fa-users me-2"></i>Members
                </span>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#membersSubNav" aria-controls="membersSubNav" aria-expanded="false"
                    aria-label="Toggle navigation" style="color:#fff;">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="membersSubNav">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('members.view') ? 'fw-bold border-bottom border-2 border-white' : 'opacity-75' }}"
                                href="{{ route('members.view') }}">
                                <i class="fas fa-list me-1"></i> View Members
                            </a>
                        </li>
                        @if(auth()->user()->hasPermission('members.create') || auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('members.add') ? 'fw-bold border-bottom border-2 border-white' : 'opacity-75' }}"
                                    href="{{ route('members.add') }}">
                                    <i class="fas fa-user-plus me-1"></i> Add Member
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('leaders.index') ? 'fw-bold border-bottom border-2 border-white' : 'opacity-75' }}"
                                href="{{ route('leaders.index') }}">
                                <i class="fas fa-user-tie me-1"></i> Leadership
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('attendance.index') ? 'fw-bold border-bottom border-2 border-white' : 'opacity-75' }}"
                                href="{{ route('attendance.index') }}">
                                <i class="fas fa-calendar-check me-1"></i> Attendance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('attendance.statistics') ? 'fw-bold border-bottom border-2 border-white' : 'opacity-75' }}"
                                href="{{ route('attendance.statistics') }}">
                                <i class="fas fa-chart-bar me-1"></i> Attendance Stats
                            </a>
                        </li>
                    </ul>
                    <span class="navbar-text text-white opacity-75 d-none d-md-block" style="font-size:0.8rem;">
                        <i class="fas fa-circle me-1" style="color:#4caf50; font-size:0.6rem;"></i>
                        {{ $members->count() }} Active Members
                    </span>
                </div>
            </div>
        </nav>
        <!-- End Members Navigation -->

        <!-- Page Title and Quick Actions - Compact Collapsible -->
        <div class="card border-0 shadow-sm mb-2 mt-1 actions-card">
            <div class="card-header bg-white border-bottom p-2 px-3 d-flex align-items-center justify-content-between actions-header"
                onclick="toggleActions()">
                <div class="d-flex align-items-center gap-2">
                    <h1 class="mb-0 mt-2" style="font-size: 1.5rem;"><i class="fas fa-users me-2"></i>Members</h1>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-chevron-down text-muted d-md-none" id="actionsToggleIcon"></i>
                </div>
            </div>
            <div class="card-body p-3" id="actionsBody">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('attendance.index') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-users me-1"></i>
                        <span class="d-none d-sm-inline">Record Attendance</span>
                        <span class="d-sm-none">Attendance</span>
                    </a>
                    <a href="{{ route('attendance.statistics') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-chart-bar me-1"></i>
                        <span class="d-none d-sm-inline">Statistics</span>
                        <span class="d-sm-none">Stats</span>
                    </a>
                    <div class="btn-group btn-group-sm" role="group" aria-label="View toggle">
                        <button type="button" class="btn btn-outline-secondary active" id="listViewBtn"
                            onclick="switchView('list')">
                            <i class="fas fa-list"></i>
                            <span class="d-none d-md-inline ms-1">List</span>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="cardViewBtn"
                            onclick="switchView('card')">
                            <i class="fas fa-th-large"></i>
                            <span class="d-none d-md-inline ms-1">Card</span>
                        </button>
                    </div>
                    @if(auth()->user()->hasPermission('members.create') || auth()->user()->isAdmin())
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addChildModal">
                            <i class="fas fa-child me-1"></i>
                            <span class="d-none d-sm-inline">Add Child</span>
                            <span class="d-sm-none">Child</span>
                        </button>
                        <a href="{{ route('members.add') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-user-plus me-1"></i>
                            <span class="d-none d-sm-inline">Add Member</span>
                            <span class="d-sm-none">Add</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- List View -->
        <div id="listView">
            <!-- Tabs and main table section -->
            @php
                $permanentCount = $totalPermanent ?? $members->where('membership_type', 'permanent')->count();
                $temporaryCount = $totalTemporary ?? $members->where('membership_type', 'temporary')->count();
                $childrenCount = ($children ?? collect())->count();
                $archivedCount = ($archivedMembers ?? collect())->count();
            @endphp
            <style>
                .tab-badge {
                    background: linear-gradient(90deg, #7a0000 0%, #940000 100%);
                    color: #fff;
                    font-size: 0.95em;
                    font-weight: 600;
                    border-radius: 12px;
                    padding: 2px 10px;
                    margin-left: 6px;
                    box-shadow: 0 1px 4px rgba(91, 42, 134, 0.10);
                    vertical-align: middle;
                    letter-spacing: 0.02em;
                    transition: background 0.2s;
                }

                .nav-tabs .nav-link.active .tab-badge {
                    background: linear-gradient(90deg, #940000 0%, #7a0000 100%);
                    color: #fff;
                }
            </style>
            <ul class="nav nav-tabs" id="memberTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="permanent-tab" data-bs-toggle="tab" data-bs-target="#permanent"
                        type="button" role="tab">
                        Permanent <span class="tab-badge">{{ $permanentCount }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="temporary-tab" data-bs-toggle="tab" data-bs-target="#temporary"
                        type="button" role="tab">
                        Temporary <span class="tab-badge">{{ $temporaryCount }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="children-tab" data-bs-toggle="tab" data-bs-target="#children" type="button"
                        role="tab">
                        Children <span class="tab-badge">{{ $childrenCount }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="archived-tab" data-bs-toggle="tab" data-bs-target="#archived" type="button"
                        role="tab">
                        Archived <span class="tab-badge">{{ $archivedCount }}</span>
                    </button>
                </li>
            </ul>
            <div class="tab-content border-bottom border-start border-end p-3" id="memberTabsContent">
                <div class="tab-pane fade show active" id="permanent" role="tabpanel">
                    @include('members.partials.main-table', ['members' => $permanentMembers ?? collect(), 'showArchive' => true])
                </div>
                <div class="tab-pane fade" id="temporary" role="tabpanel">
                    @include('members.partials.main-table', ['members' => $temporaryMembers ?? collect(), 'showArchive' => true])
                </div>
                <div class="tab-pane fade" id="children" role="tabpanel">
                    <div class="card">
                        <div class="card-body p-0">
                            @if($childrenCount > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Age</th>
                                                <th>Gender</th>
                                                <th>Date of Birth</th>
                                                <th>Parent/Guardian</th>
                                                <th>Age Group</th>
                                                <th class="text-end" style="width: 100px;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($children as $child)
                                                @php
                                                    $age = (int) $child->getAge();
                                                    $ageGroup = $child->getAgeGroup();
                                                @endphp
                                                <tr>
                                                    <td class="text-muted">{{ $loop->iteration }}</td>
                                                    <td>
                                                        <strong>{{ $child->full_name }}</strong>
                                                    </td>
                                                    <td>{{ $age }} years</td>
                                                    <td>
                                                        <span class="badge bg-{{ $child->gender === 'male' ? 'primary' : 'info' }}">
                                                            {{ ucfirst($child->gender) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $child->date_of_birth ? $child->date_of_birth->format('M d, Y') : '—' }}
                                                    </td>
                                                    <td>
                                                        @if($child->member)
                                                            <a href="javascript:void(0);"
                                                                onclick="viewDetails({{ $child->member->id }})"
                                                                class="text-decoration-none text-primary" style="cursor: pointer;">
                                                                <i class="fas fa-user me-1"></i>{{ $child->member->full_name }}
                                                                <span class="badge bg-success ms-1"
                                                                    style="font-size: 0.7em;">Member</span>
                                                            </a>
                                                        @elseif($child->parent_name)
                                                            <div>
                                                                <i class="fas fa-user-friends me-1 text-warning"></i>
                                                                <strong>{{ $child->parent_name }}</strong>
                                                                <span class="badge bg-warning text-dark ms-1"
                                                                    style="font-size: 0.7em;">Non-Member</span>
                                                            </div>
                                                            @if($child->parent_phone)
                                                                <small class="text-muted">
                                                                    <i class="fas fa-phone me-1"></i>{{ $child->parent_phone }}
                                                                </small>
                                                            @endif
                                                            @if($child->parent_relationship)
                                                                <br><small class="text-muted">
                                                                    <i class="fas fa-link me-1"></i>{{ $child->parent_relationship }}
                                                                </small>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($ageGroup === 'infant')
                                                            <span class="badge bg-secondary">Infant (&lt;3)</span>
                                                        @elseif($ageGroup === 'sunday_school')
                                                            <span class="badge bg-success">Sunday School (3-12)</span>
                                                        @elseif($ageGroup === 'teenager')
                                                            <span class="badge bg-warning text-dark">Teenager (13-20)</span>
                                                        @else
                                                            <span class="badge bg-dark">Adult (21+)</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="d-flex flex-row gap-1 justify-content-end align-items-center"
                                                            style="flex-wrap: nowrap;">
                                                            @if($child->member)
                                                                <button type="button" class="btn btn-sm btn-outline-info"
                                                                    onclick="if(typeof window.viewDetails === 'function') { window.viewDetails({{ $child->member->id }}); } else { alert('View details function is not available. Please refresh the page.'); }"
                                                                    title="View Parent Details">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            @endif
                                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                                onclick="if(typeof window.deleteChild === 'function') { window.deleteChild({{ $child->id }}); } else { alert('Delete function is not available. Please refresh the page.'); }"
                                                                title="Delete Child">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-child fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No children registered yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="archived" role="tabpanel">
                    @include('members.partials.main-table', ['members' => $archivedMembers ?? collect(), 'showArchive' => false, 'isArchived' => true])
                </div>
            </div>
        </div>

        <!-- Card View -->
        <div id="cardView" style="display: none;">
            @include('members.partials.card-view', ['members' => $members, 'archivedMembers' => $archivedMembers ?? collect()])
        </div>
    </div>
@endsection

@section('modals')
    <!-- Details Modal -->
    <div class="modal fade" id="memberDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
                <div class="modal-header text-white"
                    style="background: linear-gradient(135deg, #940000 0%, #7a0000 100%); border: none;">
                    <h5 class="modal-title d-flex align-items-center gap-2"><i class="fas fa-id-card"
                            aria-label="Member details"></i><span>Member Details</span></h5>
                    <div class="ms-auto d-flex gap-2 align-items-center">
                        <button class="btn btn-sm btn-outline-light" id="btnCopyAllDetails" title="Copy all details"
                            aria-label="Copy all details"><i class="fas fa-copy"></i></button>
                        <div class="vr opacity-50 mx-1"></div>
                        <button class="btn btn-sm btn-light" id="btnDownloadExcel" title="Download Excel"><i
                                class="fas fa-file-excel text-success"></i></button>
                        <button class="btn btn-sm btn-light" id="btnDownloadPDF" title="Download PDF"><i
                                class="fas fa-file-pdf text-danger"></i></button>
                        <button class="btn btn-sm btn-light" id="btnPrintDetails" title="Print"><i
                                class="fas fa-print text-secondary"></i></button>
                    </div>
                    <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light" id="memberDetailsBody">
                    <div class="text-center text-muted py-4">Loading...</div>
                </div>
                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <div class="small">
                        <span class="me-1">Powered by</span>
                        <a href="https://emca.tech/#" target="_blank" rel="noopener" class="emca-link fw-semibold"
                            style="color: #940000 !important;">EmCa Technologies</a>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-light" id="btnAttendanceHistory"
                            onclick="viewAttendanceHistory()" style="display: none;">
                            <i class="fas fa-calendar-check me-1"></i>Attendance History
                        </button>
                        <button type="button" class="btn btn-outline-light" id="btnIdCard" onclick="viewIdCard()"
                            style="display: none;">
                            <i class="fas fa-id-card me-1"></i>ID Card
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Member Modal -->
    <div class="modal fade" id="memberEditModal" tabindex="-1" aria-labelledby="editMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header text-white border-0"
                    style="background: linear-gradient(135deg, #940000 0%, #ff4d4d 100%); padding: 1.5rem;">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        <i class="fas fa-user-edit"></i>
                        <span>Edit Member Information</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light" style="padding: 2rem;">
                    <form id="editMemberForm" method="POST" action="#" class="needs-validation" novalidate
                        onsubmit="event.preventDefault(); event.stopPropagation(); return false;">
                        <input type="hidden" id="edit_member_id" name="member_id">
                        <input type="hidden" name="_method" value="PUT">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit_full_name" class="form-label fw-semibold">Full Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" id="edit_full_name" name="full_name"
                                    required>
                                <div class="invalid-feedback">Please provide a valid full name.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_email" class="form-label fw-semibold">Email Address</label>
                                <input type="email" class="form-control form-control-lg" id="edit_email" name="email">
                                <div class="invalid-feedback">Please provide a valid email address.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_phone_number" class="form-label fw-semibold">Phone Number <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" id="edit_phone_number"
                                    name="phone_number" required>
                                <div class="invalid-feedback">Please provide a valid phone number.</div>
                            </div>
                            <div class="col-md-3">
                                <label for="edit_gender" class="form-label fw-semibold">Gender <span
                                        class="text-danger">*</span></label>
                                <select class="form-select form-select-lg" id="edit_gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                <div class="invalid-feedback">Please select a gender.</div>
                            </div>
                            <div class="col-md-3">
                                <label for="edit_date_of_birth" class="form-label fw-semibold">Date of Birth <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control form-control-lg" id="edit_date_of_birth"
                                    name="date_of_birth" required>
                                <div class="invalid-feedback">Please provide a valid date of birth.</div>
                            </div>
                            <div class="col-md-4">
                                <label for="edit_education_level" class="form-label fw-semibold">Education Level</label>
                                <select class="form-select form-select-lg" id="edit_education_level" name="education_level">
                                    <option value="">Select Education</option>
                                    <option value="primary">Primary</option>
                                    <option value="secondary">Secondary</option>
                                    <option value="chuo_cha_kati">Chuo cha Kati</option>
                                    <option value="university">University</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="edit_profession" class="form-label fw-semibold">Profession</label>
                                <input type="text" class="form-control form-control-lg" id="edit_profession"
                                    name="profession">
                            </div>
                            <div class="col-md-4">
                                <label for="edit_nida_number" class="form-label fw-semibold">NIDA Number</label>
                                <input type="text" class="form-control form-control-lg" id="edit_nida_number"
                                    name="nida_number" minlength="20" maxlength="20">
                            </div>
                            <div class="col-md-6">
                                <label for="edit_membership_type" class="form-label fw-semibold">Membership Type <span
                                        class="text-danger">*</span></label>
                                <select class="form-select form-select-lg" id="edit_membership_type" name="membership_type"
                                    required>
                                    <option value="permanent">Permanent</option>
                                    <option value="temporary">Temporary</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_member_type" class="form-label fw-semibold">Member Type</label>
                                <select class="form-select form-select-lg" id="edit_member_type" name="member_type">
                                    <option value="">Select Type</option>
                                    <option value="father">Father</option>
                                    <option value="mother">Mother</option>
                                    <option value="independent">Independent</option>
                                </select>
                            </div>

                            <!-- Family/Guardian Information -->
                            <div class="col-12">
                                <hr class="my-4">
                                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-users me-2"></i>Family & Guardian
                                    Information</h6>
                            </div>
                            <div class="col-md-4">
                                <label for="edit_living_with_family" class="form-label fw-semibold">Living with
                                    Family</label>
                                <select class="form-select form-select-lg" id="edit_living_with_family"
                                    name="living_with_family">
                                    <option value="">Select</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="edit_family_relationship" class="form-label fw-semibold">Family
                                    Relationship</label>
                                <input type="text" class="form-control form-control-lg" id="edit_family_relationship"
                                    name="family_relationship">
                            </div>
                            <div class="col-md-4">
                                <label for="edit_guardian_name" class="form-label fw-semibold">Guardian Name</label>
                                <input type="text" class="form-control form-control-lg" id="edit_guardian_name"
                                    name="guardian_name">
                            </div>
                            <div class="col-md-4">
                                <label for="edit_guardian_phone" class="form-label fw-semibold">Guardian Phone</label>
                                <input type="text" class="form-control form-control-lg" id="edit_guardian_phone"
                                    name="guardian_phone">
                            </div>
                            <div class="col-md-4">
                                <label for="edit_guardian_relationship" class="form-label fw-semibold">Guardian
                                    Relationship</label>
                                <input type="text" class="form-control form-control-lg" id="edit_guardian_relationship"
                                    name="guardian_relationship">
                            </div>

                            <!-- Marital/Spouse Information -->
                            <div class="col-12">
                                <hr class="my-4">
                                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-heart me-2"></i>Marital & Spouse
                                    Information</h6>
                            </div>
                            <div class="col-md-4">
                                <label for="edit_marital_status" class="form-label fw-semibold">Marital Status</label>
                                <select class="form-select form-select-lg" id="edit_marital_status" name="marital_status">
                                    <option value="">Select Status</option>
                                    <option value="married">Married</option>
                                    <option value="divorced">Divorced</option>
                                    <option value="widowed">Widowed</option>
                                    <option value="separated">Separated</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="edit_spouse_full_name" class="form-label fw-semibold">Spouse Full Name</label>
                                <input type="text" class="form-control form-control-lg" id="edit_spouse_full_name"
                                    name="spouse_full_name">
                            </div>
                            <div class="col-md-4">
                                <label for="edit_spouse_date_of_birth" class="form-label fw-semibold">Spouse Date of
                                    Birth</label>
                                <input type="date" class="form-control form-control-lg" id="edit_spouse_date_of_birth"
                                    name="spouse_date_of_birth">
                            </div>
                            <div class="col-md-4">
                                <label for="edit_spouse_education_level" class="form-label fw-semibold">Spouse Education
                                    Level</label>
                                <select class="form-select form-select-lg" id="edit_spouse_education_level"
                                    name="spouse_education_level">
                                    <option value="">Select Education</option>
                                    <option value="primary">Primary</option>
                                    <option value="secondary">Secondary</option>
                                    <option value="chuo_cha_kati">Chuo cha Kati</option>
                                    <option value="university">University</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="edit_spouse_profession" class="form-label fw-semibold">Spouse Profession</label>
                                <input type="text" class="form-control form-control-lg" id="edit_spouse_profession"
                                    name="spouse_profession">
                            </div>
                            <div class="col-md-4">
                                <label for="edit_spouse_nida_number" class="form-label fw-semibold">Spouse NIDA
                                    Number</label>
                                <input type="text" class="form-control form-control-lg" id="edit_spouse_nida_number"
                                    name="spouse_nida_number" minlength="20" maxlength="20">
                            </div>
                            <div class="col-md-4">
                                <label for="edit_spouse_email" class="form-label fw-semibold">Spouse Email</label>
                                <input type="email" class="form-control form-control-lg" id="edit_spouse_email"
                                    name="spouse_email">
                            </div>
                            <div class="col-md-4">
                                <label for="edit_spouse_phone_number" class="form-label fw-semibold">Spouse Phone
                                    Number</label>
                                <input type="text" class="form-control form-control-lg" id="edit_spouse_phone_number"
                                    name="spouse_phone_number">
                            </div>
                            <div class="col-md-4">
                                <label for="edit_spouse_church_member" class="form-label fw-semibold">Spouse Church
                                    Member</label>
                                <select class="form-select form-select-lg" id="edit_spouse_church_member"
                                    name="spouse_church_member">
                                    <option value="">Select</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-white border-0" style="padding: 1.5rem;">
                    <button type="button" class="btn btn-outline-secondary btn-lg px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" id="editMemberSubmitBtn" class="btn btn-primary btn-lg px-4"
                        style="background: linear-gradient(135deg, #940000 0%, #ff4d4d 100%); border: none;"
                        onclick="if(typeof handleEditFormSubmit === 'function') { handleEditFormSubmit(event); } else { console.error('handleEditFormSubmit not defined'); }">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Compact Add Member Modal (icon-triggered in table header) -->
    <div class="modal fade" id="addMemberModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="modal-header text-white"
                    style="background: linear-gradient(135deg, #940000 0%, #ff4d4d 100%); border: none;">
                    <h5 class="modal-title d-flex align-items-center gap-2"><i class="fas fa-user-plus"></i><span>Register
                            New Member</span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light">
                    <form id="quickAddMemberForm">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="add_full_name" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Gender</label>
                                <select class="form-select" id="add_gender">
                                    <option value="">Select</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" id="add_phone_number">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="add_email">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Region</label>
                                <select id="add_region" class="form-select"></select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">District</label>
                                <select id="add_district" class="form-select"></select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ward</label>
                                <select id="add_ward" class="form-select"></select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tribe</label>
                                <select id="add_tribe" class="form-select"></select>
                            </div>
                            <div class="col-md-6" id="add_other_tribe_group" style="display:none;">
                                <label class="form-label">Other Tribe</label>
                                <input type="text" class="form-control" id="add_other_tribe">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Child Modal -->
    <div class="modal fade" id="addChildModal" tabindex="-1" aria-labelledby="addChildModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-white border-0"
                    style="background: linear-gradient(135deg, #940000 0%, #ff4d4d 100%); padding: 1.5rem;">
                    <h5 class="modal-title" id="addChildModalLabel"><i class="fas fa-child me-2"></i>Add Child</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" style="background: #fffafa;">
                    <form id="addChildForm">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Child's Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="child_full_name" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="form-select" id="child_gender" required>
                                    <option value="">Select</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="child_date_of_birth" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Age</label>
                                <input type="text" class="form-control" id="child_age" readonly>
                            </div>

                            <div class="col-12">
                                <hr>
                                <h6 class="mb-3"><i class="fas fa-user-friends me-2"></i>Parent/Guardian Information</h6>
                            </div>

                            <div class="col-md-12">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="parent_type" id="parent_member"
                                        value="member" checked>
                                    <label class="form-check-label" for="parent_member">
                                        Parent is a Church Member
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="parent_type" id="parent_non_member"
                                        value="non_member">
                                    <label class="form-check-label" for="parent_non_member">
                                        Parent is NOT a Church Member
                                    </label>
                                </div>
                            </div>

                            <!-- Member Parent Fields -->
                            <div id="memberParentFields">
                                <div class="col-md-12">
                                    <label class="form-label">Select Parent Member <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="child_member_id">
                                        <option value="">Select Member</option>
                                        @foreach($members->flatten() as $member)
                                            <option value="{{ $member->id }}">{{ $member->full_name }}
                                                ({{ $member->member_id }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Non-Member Parent Fields -->
                            <div id="nonMemberParentFields" style="display: none;">
                                <div class="col-md-6">
                                    <label class="form-label">Parent/Guardian Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="child_parent_name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Parent/Guardian Phone</label>
                                    <input type="text" class="form-control" id="child_parent_phone">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Relationship to Child</label>
                                    <select class="form-select" id="child_parent_relationship">
                                        <option value="">Select Relationship</option>
                                        <option value="Father">Father</option>
                                        <option value="Mother">Mother</option>
                                        <option value="Guardian">Guardian</option>
                                        <option value="Grandfather">Grandfather</option>
                                        <option value="Grandmother">Grandmother</option>
                                        <option value="Uncle">Uncle</option>
                                        <option value="Aunt">Aunt</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary px-4" onclick="saveChild()">
                        <i class="fas fa-save me-2"></i>Save Child
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Archive Modal (should be included once per page, not per row) -->
    <div class="modal fade" id="archiveMemberModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-archive me-2"></i>Archive Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="archiveMemberForm">
                        <input type="hidden" id="archive_member_id">
                        <div class="mb-3">
                            <label for="archive_reason" class="form-label">Reason for archiving</label>
                            <textarea class="form-control" id="archive_reason" name="reason" rows="3" required></textarea>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning">Archive</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script>     // =======================================          =====
        // EDIT FORM SUBMIT HANDLER - DEFINE FIRST
        // ============================================
        // CRITICAL: Define this function immediately so inline onclick can use it
        window.handleEditFormSubmit = function (e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
            }

            console.log('✓ Submit button clicked - processing form submission');

            const form = document.getElementById('editMemberForm');
            if (!form) {
                console.error('Form not found');
                return;
            }

            // Check form validity
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                const firstInvalid = form.querySelector(':invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalid.focus();
                }
                return;
            }

            // Get member ID
            let memberId = window.currentEditMember?.id;
            if (!memberId) {
                const hiddenIdInput = document.getElementById('edit_member_id');
                if (hiddenIdInput && hiddenIdInput.value) {
                    memberId = parseInt(hiddenIdInput.value);
                    console.log('Got member ID from hidden input:', memberId);
                }
            }

            if (!memberId) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Member ID not found. Please close the modal, refresh the page, and try again.'
                    });
                } else {
                    alert('Member ID not found. Please close the modal, refresh the page, and try again.');
                }
                return;
            }

            // Collect form data
            const formData = new FormData(form);
            formData.append('_method', 'PUT');
            formData.append('_token', '{{ csrf_token() }}');

            const emailValue = document.getElementById('edit_email')?.value.trim() || '';
            formData.set('email', emailValue);

            console.log('Submitting edit form for member ID:', memberId);

            // Show loading
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Updating Member...',
                    text: 'Please wait while we save your changes',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }

            // Submit
            const updateUrl = `{{ url('/members') }}/${memberId}`;
            fetch(updateUrl, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData,
                cache: 'no-cache'
            })
                .then(response => {
                    return response.text().then(text => {
                        try {
                            const json = JSON.parse(text);
                            if (!response.ok) {
                                if (response.status === 422 && json.errors) {
                                    const errorMessages = Object.entries(json.errors)
                                        .map(([field, messages]) => `${field}: ${Array.isArray(messages) ? messages.join(', ') : messages}`)
                                        .join('\n');
                                    throw new Error('Validation failed:\n' + errorMessages);
                                }
                                throw new Error(json.message || json.error || 'Update failed');
                            }
                            return json;
                        } catch (e) {
                            if (text.includes('<!DOCTYPE') || text.includes('<html')) {
                                throw new Error('Server returned HTML instead of JSON. Check server logs.');
                            }
                            throw new Error('Invalid response format: ' + text.substring(0, 100));
                        }
                    });
                })
                .then(data => {
                    if (data.success) {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('memberEditModal'));
                        if (modal) modal.hide();

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Member information updated successfully',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                const cleanUrl = window.location.pathname;
                                window.history.replaceState({}, '', cleanUrl);
                                setTimeout(() => {
                                    window.location.href = cleanUrl;
                                }, 50);
                            });
                        } else {
                            alert('Member updated successfully!');
                            window.location.reload();
                        }
                    } else {
                        throw new Error(data.message || 'Update failed');
                    }
                })
                .catch(error => {
                    console.error('Error updating member:', error);
                    let errorMessage = error.message || 'Failed to update member. Please check your input and try again.';
                    if (errorMessage.includes('\n')) {
                        errorMessage = errorMessage.split('\n').join('<br>');
                    }
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Update Failed',
                            html: `<p>${errorMessage}</p><p class="text-muted small mt-2">Check the browser console (F12) for more details.</p>`,
                            confirmButtonText: 'OK',
                            width: '500px'
                        });
                    } else {
                        alert('Update failed: ' + errorMessage);
                    }
                });
        };

        // ============================================
        // ARCHIVE MEMBER FUNCTION - DEFINE EARLY
        // ============================================
        // Make confirmDelete globally accessible immediately
        window.confirmDelete = function (id) {
            console.log('confirmDelete called with ID:', id);
            if (!id) {
                console.error('confirmDelete: No ID provided');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Member ID is required',
                        confirmButtonText: 'OK'
                    });
                } else {
                    alert('Member ID is required');
                }
                return;
            }
            console.log('Attempting to archive member with ID:', id);

            // Check if we're in the archived tab
            const isArchived = document.querySelector('.nav-link[href="#archived"]')?.classList.contains('active');
            console.log('Is archived tab:', isArchived);

            // Show reason input form
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Archive Member',
                    html: `
                                                            <div class="mb-3">
                                                                <label for="archive-reason" class="form-label">Reason for archiving:</label>
                                                                <textarea id="archive-reason" class="form-control" rows="3" placeholder="Please provide a reason for archiving this member..." required></textarea>
                                                            </div>
                                                            <div class="alert alert-info">
                                                                <i class="fas fa-info-circle me-2"></i>
                                                                <strong>Note:</strong> The member will be moved to archived status and all their financial records will be preserved.
                                                            </div>
                                                        `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Archive Member',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#ffc107',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true,
                    focusConfirm: false,
                    preConfirm: () => {
                        const reason = document.getElementById('archive-reason').value.trim();
                        if (!reason) {
                            Swal.showValidationMessage('Please provide a reason for archiving');
                            return false;
                        }
                        return reason;
                    }
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        const reason = result.value;
                        console.log('Archive confirmed with reason:', reason);

                        // Show loading
                        Swal.fire({
                            title: 'Archiving Member...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit archive request
                        const formData = new FormData();
                        formData.append('reason', reason);
                        formData.append('_method', 'DELETE');

                        fetch(`{{ url('/members') }}/${id}/archive`, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        })
                            .then(response => {
                                if (!response.ok) {
                                    return response.json().then(data => {
                                        throw new Error(data.message || 'Archive failed');
                                    });
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Member Archived',
                                        text: 'The member has been successfully archived.',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    throw new Error(data.message || 'Archive failed');
                                }
                            })
                            .catch(error => {
                                console.error('Archive error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Archive Failed',
                                    text: error.message || 'Failed to archive member. Please try again.',
                                    confirmButtonText: 'OK'
                                });
                            });
                    }
                });
            } else {
                const reason = prompt('Please provide a reason for archiving this member:');
                if (reason) {
                    const formData = new FormData();
                    formData.append('reason', reason);
                    formData.append('_method', 'DELETE');

                    fetch(`{{ url('/members') }}/${id}/archive`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Member archived successfully');
                                window.location.reload();
                            } else {
                                alert('Archive failed: ' + (data.message || 'Unknown error'));
                            }
                        })
                        .catch(error => {
                            console.error('Archive error:', error);
                            alert('Failed to archive member. Please try again.');
                        });
                }
            }
        };

        // ============================================
        // RESTORE MEMBER FUNCTION - DEFINE EARLY
        // ============================================
        // Make restoreMember globally accessible immediately
        window.restoreMember = function (memberId) {
            if (!memberId) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Member ID is required',
                        confirmButtonText: 'OK'
                    });
                } else {
                    alert('Member ID is required');
                }
                return;
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Restore Member',
                    text: 'Are you sure you want to restore this member? They will be moved back to active members.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Restore',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#28a745',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Restoring...',
                            text: 'Please wait while we restore the member.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        fetch(`{{ url('/members/archived') }}/${memberId}/restore`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                            .then(response => {
                                if (response.ok) {
                                    return response.json();
                                } else if (response.status === 403) {
                                    return response.json().then(data => {
                                        throw new Error(data.message || 'You do not have permission to restore members.');
                                    });
                                } else {
                                    return response.json().then(data => {
                                        throw new Error(data.message || `Server error: ${response.status}`);
                                    }).catch(() => {
                                        throw new Error(`Server error: ${response.status}`);
                                    });
                                }
                            })
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Member Restored',
                                        text: data.message || 'Member has been restored successfully.',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Restore Failed',
                                        text: data.message || 'Please try again.',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Restore error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Restore Failed',
                                    text: error.message || 'An error occurred while restoring the member.',
                                    confirmButtonText: 'OK'
                                });
                            });
                    }
                });
            } else {
                if (confirm('Are you sure you want to restore this member?')) {
                    fetch(`{{ url('/members/archived') }}/${memberId}/restore`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Member restored successfully');
                                location.reload();
                            } else {
                                alert('Restore failed: ' + (data.message || 'Unknown error'));
                            }
                        })
                        .catch(error => {
                            console.error('Restore error:', error);
                            alert('Failed to restore member. Please try again.');
                        });
                }
            }
        };

        // ============================================
        // DELETE CHILD FUNCTION - DEFINE EARLY
        // ============================================
        // Make deleteChild globally accessible immediately
        window.deleteChild = function (childId) {
            if (!childId) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Child ID is required',
                        confirmButtonText: 'OK'
                    });
                } else {
                    alert('Child ID is required');
                }
                return;
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Delete Child',
                    text: 'Are you sure you want to delete this child? This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#dc3545',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait while we delete the child.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        fetch(`{{ url('/children') }}/${childId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                            .then(response => {
                                if (response.ok) {
                                    return response.json();
                                } else {
                                    return response.json().then(data => {
                                        throw new Error(data.message || `Server error: ${response.status}`);
                                    }).catch(() => {
                                        throw new Error(`Server error: ${response.status}`);
                                    });
                                }
                            })
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Child Deleted',
                                        text: data.message || 'Child has been deleted successfully.',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    throw new Error(data.message || 'Delete failed');
                                }
                            })
                            .catch(error => {
                                console.error('Delete child error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Delete Failed',
                                    text: error.message || 'An error occurred while deleting the child.',
                                    confirmButtonText: 'OK'
                                });
                            });
                    }
                });
            } else {
                if (confirm('Are you sure you want to delete this child?')) {
                    fetch(`{{ url('/children') }}/${childId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Child deleted successfully');
                                location.reload();
                            } else {
                                alert('Delete failed: ' + (data.message || 'Unknown error'));
                            }
                        })
                        .catch(error => {
                            console.error('Delete child error:', error);
                            alert('Failed to delete child. Please try again.');
                        });
                }
            }
        };

        // ============================================
        // FORM SUBMISSION PREVENTION - RUNS FIRST
        // ============================================
        // CRITICAL: Prevent form from submitting to /members/view
        // This must run BEFORE anything else
        (function () {
            // Override form submit immediately - multiple layers
            const preventSubmit = function (e) {
                if (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                }
                console.error('BLOCKED: Form tried to submit to /members/view');
                return false;
            };

            // Function to secure a form
            const secureForm = function (f) {
                if (!f || f.dataset.secured === 'true') return;
                f.method = 'GET'; // Change method to GET so it can't POST
                f.action = 'javascript:void(0);'; // Change action
                f.onsubmit = preventSubmit;
                f.addEventListener('submit', preventSubmit, true); // Capture phase
                f.addEventListener('submit', preventSubmit, false); // Bubble phase
                f.dataset.secured = 'true';
                console.log('✓ Form secured:', f.id);
            };

            // Try to attach immediately
            const form = document.getElementById('editMemberForm');
            if (form) {
                secureForm(form);
            }

            // Also attach when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function () {
                    const f = document.getElementById('editMemberForm');
                    if (f) secureForm(f);
                });
            }

            // Watch for form being added to DOM (MutationObserver)
            const observer = new MutationObserver(function (mutations) {
                const f = document.getElementById('editMemberForm');
                if (f) secureForm(f);
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        })();

        // ============================================
        // URL CLEANUP - RUNS FIRST, BEFORE ANYTHING ELSE
        // ============================================
        // Remove ALL form data parameters from URL immediately
        (function cleanUrlImmediately() {
            const urlParams = new URLSearchParams(window.location.search);
            const allowedParams = ['tab', 'show', 'search', 'gender', 'region', 'district', 'ward'];

            // Complete list of ALL form field names that should NEVER be in URL
            const formFields = [
                'member_id', 'full_name', 'email', 'phone_number', 'date_of_birth',
                'education_level', 'profession', 'nida_number', 'membership_type', 'member_type',
                'living_with_family', 'family_relationship', 'guardian_name', 'guardian_phone',
                'guardian_relationship', 'marital_status', 'spouse_full_name', 'spouse_date_of_birth',
                'spouse_education_level', 'spouse_profession', 'spouse_nida_number', 'spouse_email',
                'spouse_phone_number', 'spouse_church_member', 'tribe', 'other_tribe', 'street',
                'address', 'spouse_tribe', 'spouse_other_tribe'
            ];

            let urlChanged = false;

            // Remove ALL form field parameters
            for (const [key, value] of urlParams.entries()) {
                // Remove if it's a form field (regardless of value, even empty)
                if (formFields.includes(key)) {
                    urlParams.delete(key);
                    urlChanged = true;
                }
                // Also remove empty values from allowed params (like empty gender="")
                else if (allowedParams.includes(key) && (!value || value.trim() === '')) {
                    urlParams.delete(key);
                    urlChanged = true;
                }
            }

            // Update URL immediately if it changed
            if (urlChanged) {
                const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
                window.history.replaceState({}, '', newUrl);
                console.log('✓ URL cleaned - removed form data parameters');
            }
        })();

        // CRITICAL: Define action functions FIRST, before anything else, to ensure they're always available
        // This prevents "Function is not available" errors

        // Define resetPassword function immediately
        window.resetPassword = function (memberId) {
            console.log('resetPassword function called with memberId:', memberId);
            if (!memberId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Member ID is required'
                });
                return;
            }

            Swal.fire({
                title: 'Reset Password',
                text: 'Are you sure you want to reset this member\'s password? A new password will be generated and sent via SMS if available.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Reset Password',
                cancelButtonText: 'Cancel',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    const baseUrl = '{{ url("/") }}';
                    const url = `${baseUrl}/members/${memberId}/reset-password`;
                    console.log('Resetting password for member ID:', memberId);
                    console.log('Request URL:', url);

                    return fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(async response => {
                            if (!response.ok) {
                                const contentType = response.headers.get('content-type');
                                if (contentType && contentType.includes('application/json')) {
                                    const errorData = await response.json();
                                    throw new Error(errorData.message || `Server error: ${response.status} ${response.statusText}`);
                                } else {
                                    const text = await response.text();
                                    if (response.status === 404) {
                                        throw new Error('Route not found. Please check if the route is properly configured.');
                                    } else if (response.status === 419) {
                                        throw new Error('CSRF token mismatch. Please refresh the page and try again.');
                                    } else if (response.status === 403) {
                                        throw new Error('You do not have permission to perform this action.');
                                    } else {
                                        throw new Error(`Server error: ${response.status} ${response.statusText}`);
                                    }
                                }
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (!data.success) {
                                throw new Error(data.message || 'Failed to reset password');
                            }
                            return data;
                        })
                        .catch(error => {
                            Swal.showValidationMessage(`Request failed: ${error.message}`);
                        });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    const data = result.value;
                    let message = `Password reset successfully!\n\n`;
                    message += `New Password: <strong>${data.password}</strong>\n\n`;

                    if (data.sms_sent) {
                        message += `✓ SMS sent to ${data.phone_number || 'member'}`;
                    } else {
                        message += `⚠ SMS could not be sent. Please share the password manually.`;
                    }

                    Swal.fire({
                        title: 'Password Reset Successful',
                        html: message,
                        icon: 'success',
                        confirmButtonText: 'Copy Password',
                        showCancelButton: true,
                        cancelButtonText: 'Close'
                    }).then((copyResult) => {
                        if (copyResult.isConfirmed) {
                            navigator.clipboard.writeText(data.password).then(() => {
                                Swal.fire({
                                    title: 'Copied!',
                                    text: 'Password copied to clipboard',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }).catch(() => {
                                const tempInput = document.createElement('input');
                                tempInput.value = data.password;
                                document.body.appendChild(tempInput);
                                tempInput.select();
                                document.execCommand('copy');
                                document.body.removeChild(tempInput);
                                Swal.fire({
                                    title: 'Copied!',
                                    text: 'Password copied to clipboard',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            });
                        }
                    });
                }
            });
        };

        // Define openEdit function
        window.openEdit = function (id) {
            console.log('[FULL] openEdit called with ID:', id);
            if (!id) {
                console.error('openEdit: No ID provided');
                return;
            }
            fetch(`{{ url('/members') }}/${id}`, {
                headers: { 'Accept': 'application/json' },
                cache: 'no-cache'
            })
                .then(r => {
                    if (!r.ok) throw new Error('HTTP ' + r.status);
                    return r.json();
                })
                .then(m => {
                    console.log('Member data loaded for edit:', m);
                    // Store in global variable
                    window.currentEditMember = m;
                    console.log('✓ Stored member in window.currentEditMember, ID:', m.id);

                    // Open edit modal first
                    const editModal = document.getElementById('memberEditModal');
                    if (editModal) {
                        const modal = bootstrap.Modal.getOrCreateInstance(editModal);

                        // Populate form when modal is fully shown
                        const populateHandler = function () {
                            console.log('Modal shown, populating form with data:', m);
                            // Ensure member is stored BEFORE populating form
                            window.currentEditMember = m;
                            console.log('✓ Member stored in window.currentEditMember before populating, ID:', m.id);

                            // Small delay to ensure DOM is ready
                            setTimeout(() => {
                                populateEditForm(m);
                                // Verify member ID is stored after populating
                                console.log('After populateEditForm - window.currentEditMember:', window.currentEditMember);
                                console.log('Member ID available:', window.currentEditMember?.id);

                                // Double-check: if somehow lost, restore it
                                if (!window.currentEditMember || !window.currentEditMember.id) {
                                    console.warn('Member ID lost, restoring...');
                                    window.currentEditMember = m;
                                }
                            }, 100);
                        };

                        // Remove any existing listeners first
                        editModal.removeEventListener('shown.bs.modal', populateHandler);
                        editModal.addEventListener('shown.bs.modal', populateHandler, { once: true });

                        // Show modal
                        modal.show();
                    } else {
                        console.error('memberEditModal not found');
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Edit modal not found. Please refresh the page.'
                            });
                        }
                    }
                })
                .catch(err => {
                    console.error('Error loading member for edit:', err);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to load member',
                            text: err && err.message ? err.message : 'Please try again.'
                        });
                    }
                });
        };

        // Function to populate edit form with member data
        function populateEditForm(m) {
            console.log('=== Starting to populate edit form ===');
            console.log('Member data:', m);

            if (!m) {
                console.error('No member data provided to populateEditForm');
                return;
            }

            // Ensure member is stored globally
            window.currentEditMember = m;

            // Also store in hidden input if it exists
            const hiddenIdInput = document.getElementById('edit_member_id');
            if (hiddenIdInput) {
                hiddenIdInput.value = m.id || '';
                console.log('✓ Stored member ID in hidden input:', m.id);
            }

            // Personal Information
            console.log('Setting personal information...');
            setValue('edit_full_name', m.full_name || '');
            setValue('edit_email', m.email || '');
            setValue('edit_phone_number', m.phone_number || '');
            setValue('edit_gender', m.gender || '');
            setValue('edit_date_of_birth', m.date_of_birth ? m.date_of_birth.split('T')[0] : '');
            setValue('edit_education_level', m.education_level || '');
            setValue('edit_profession', m.profession || '');
            setValue('edit_nida_number', m.nida_number || '');
            setValue('edit_membership_type', m.membership_type || 'permanent');
            setValue('edit_member_type', m.member_type || '');

            // Family/Guardian Information
            console.log('Setting family/guardian information...');
            setValue('edit_living_with_family', m.living_with_family || '');
            setValue('edit_family_relationship', m.family_relationship || '');
            setValue('edit_guardian_name', m.guardian_name || '');
            setValue('edit_guardian_phone', m.guardian_phone || '');
            setValue('edit_guardian_relationship', m.guardian_relationship || '');

            // Marital/Spouse Information
            console.log('Setting marital/spouse information...');
            setValue('edit_marital_status', m.marital_status || '');
            setValue('edit_spouse_full_name', m.spouse_full_name || '');
            setValue('edit_spouse_date_of_birth', m.spouse_date_of_birth ? m.spouse_date_of_birth.split('T')[0] : '');
            setValue('edit_spouse_education_level', m.spouse_education_level || '');
            setValue('edit_spouse_profession', m.spouse_profession || '');
            setValue('edit_spouse_nida_number', m.spouse_nida_number || '');
            setValue('edit_spouse_email', m.spouse_email || '');
            setValue('edit_spouse_phone_number', m.spouse_phone_number || '');
            setValue('edit_spouse_church_member', m.spouse_church_member || '');

            // Remove validation classes when populating
            const form = document.getElementById('editMemberForm');
            if (form) {
                form.classList.remove('was-validated');
                console.log('✓ Validation classes removed');
            } else {
                console.error('❌ editMemberForm not found!');
            }

            // Verify some key fields were set
            const fullNameEl = document.getElementById('edit_full_name');
            if (fullNameEl) {
                console.log('✓ Full name field found and set to:', fullNameEl.value);
            } else {
                console.error('❌ edit_full_name field NOT FOUND in DOM!');
            }

            console.log('=== Edit form population complete ===');
        }

        // Helper function to set form field values
        function setValue(id, value) {
            const el = document.getElementById(id);
            if (el) {
                el.value = value || '';
                // Trigger change event to ensure any listeners are notified
                el.dispatchEvent(new Event('change', { bubbles: true }));
            } else {
                console.warn(`Element with ID '${id}' not found when trying to set value:`, value);
            }
        }

        // Define viewDetails function IMMEDIATELY so it's available when buttons are clicked
        window.viewDetails = function (id) {
            console.log('viewDetails called with ID:', id);
            if (!id) {
                console.error('viewDetails: No ID provided');
                return;
            }
            fetch(`{{ url('/members') }}/${id}`, {
                headers: { 'Accept': 'application/json' },
                cache: 'no-cache'
            })
                .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
                .then(async m => {
                    console.log('Member details loaded:', m);
                    // Store member data globally for use in other functions
                    if (typeof window.currentDetailsMember === 'undefined') {
                        window.currentDetailsMember = null;
                    }
                    window.currentDetailsMember = m;

                    // Check if we have spouse details from the API response
                    if (m.spouse_details) {
                        console.log('Spouse details loaded from API:', m.spouse_details);
                        m.mainMemberSpouseInfo = m.spouse_details;
                    } else if (m.main_member_details) {
                        console.log('Main member details loaded from API:', m.main_member_details);
                        m.mainMemberSpouseInfo = m.main_member_details;
                    }
                    // Determine if archived (by checking if member_snapshot exists)
                    let isArchived = false;
                    let snap = null;
                    let archiveReason = null;
                    if (m.member_snapshot) {
                        isArchived = true;
                        snap = m.member_snapshot;
                        archiveReason = m.reason || null;
                    }
                    const data = isArchived ? snap : m;

                    // Helper functions
                    const actionCell = (content, actionsHtml = '') => `<div class="d-flex align-items-center justify-content-between">${content}<span class="ms-2 d-inline-flex gap-2">${actionsHtml}</span></div>`;
                    const badge = (text, tone = 'secondary') => `<span class="badge bg-${tone}">${text}</span>`;
                    const copyBtn = (text, title, icon) => `<button type="button" class="btn btn-sm btn-outline-secondary" onclick="navigator.clipboard.writeText('${(text || '').toString().replace(/'/g, "&#39;")}').then(()=>Swal.fire({ icon:'success', title:'Copied', timer:900, showConfirmButton:false })).catch(()=>Swal.fire({ icon:'error', title:'Copy failed' }))" title="${title}" aria-label="${title}"><i class="${icon}"></i></button>`;
                    const mailto = (email) => {
                        if (!email) return '';
                        const raw = String(email).trim();
                        const escaped = raw.replace(/[&"<>]/g, c => ({ '&': '&amp;', '"': '&quot;', '<': '&lt;', '>': '&gt;' }[c]));
                        return `<a href="mailto:${escaped}" onclick="window.location.href=this.href; return false;" class="btn btn-sm btn-outline-primary" title="Send email" aria-label="Send email"><i class="fas fa-paper-plane"></i></a>`;
                    };
                    const telto = (phone) => {
                        if (!phone) return '';
                        const sanitized = String(phone).replace(/[^+\d]/g, '');
                        return `<a href="tel:${sanitized}" onclick="window.location.href=this.href; return false;" class="btn btn-sm btn-outline-primary" title="Call" aria-label="Call"><i class="fas fa-phone"></i></a>`;
                    };
                    const mapsBtn = (q) => q ? `<a href="#" onclick="return handleAction(()=>window.open('https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(q)}','_blank'))" class="btn btn-sm btn-outline-success" title="Open in Maps" aria-label="Open in Maps"><i class="fas fa-map-marked-alt"></i></a>` : '';
                    const row = (icon, label, value, actions = '') => `
                                                            <tr>
                                                                <td class="text-muted text-nowrap"><i class="${icon} me-2" aria-hidden="true"></i>${label}</td>
                                                                <td class="fw-semibold">${actionCell(value || '—', actions)}</td>
                                                            </tr>`;

                    // Format date helper (use global if available, otherwise simple format)
                    const formatDateDisplay = (dateStr) => {
                        if (!dateStr) return '—';
                        if (typeof window.formatDateDisplay === 'function') {
                            return window.formatDateDisplay(dateStr);
                        }
                        try {
                            const d = new Date(dateStr);
                            return d.toLocaleDateString();
                        } catch (e) {
                            return dateStr;
                        }
                    };

                    // Compose QR payload with all fields
                    const lines = [
                        `Full Name: ${data.full_name || '-'}`,
                        `Member ID: ${data.member_id || '-'}`,
                        `Membership Type: ${data.membership_type || '-'}`,
                        `Member Type: ${data.member_type || '-'}`,
                        `Phone: ${data.phone_number || '-'}`,
                        `Email: ${data.email || '-'}`,
                        `Gender: ${data.gender ? data.gender.charAt(0).toUpperCase() + data.gender.slice(1) : '-'}`,
                        `Date of Birth: ${formatDateDisplay(data.date_of_birth)}`,
                        `Education Level: ${data.education_level || '-'}`,
                        `Profession: ${data.profession || '-'}`,
                        `NIDA Number: ${data.nida_number || '-'}`,
                        `Region: ${data.region || '-'}`,
                        `District: ${data.district || '-'}`,
                        `Ward: ${data.ward || '-'}`,
                        `Street: ${data.street || '-'}`,
                        `Address: ${data.address || '-'}`,
                        `Living with family: ${data.living_with_family || '-'}`,
                        `Family relationship: ${data.family_relationship || '-'}`,
                        `Tribe: ${(data.tribe || '-') + (data.other_tribe ? ` (${data.other_tribe})` : '')}`,
                    ];
                    if ((data.membership_type === 'temporary' || (data.membership_type === 'permanent' && data.member_type === 'independent')) && (data.guardian_name || data.guardian_phone || data.guardian_relationship)) {
                        lines.push(`Guardian Name: ${data.guardian_name || '-'}`);
                        lines.push(`Guardian Phone: ${data.guardian_phone || '-'}`);
                        lines.push(`Guardian Relationship: ${data.guardian_relationship || '-'}`);
                    }
                    if (archiveReason) {
                        lines.push(`Archive Reason: ${archiveReason}`);
                    }
                    const qrPayload = lines.join('\n');

                    // Build HTML
                    // Get profile picture URL - handle both old storage path and new assets path
                    const baseUrl = '{{ url("/") }}';
                    let profilePictureUrl = '';
                    if (data.profile_picture) {
                        if (data.profile_picture.startsWith('assets/images/')) {
                            profilePictureUrl = `${baseUrl}/${data.profile_picture}`;
                        } else {
                            profilePictureUrl = `${baseUrl}/storage/${data.profile_picture}`;
                        }
                    }

                    let html = `<div id="memberDetailsPrint" class="p-2">
                                                            <div class="d-flex justify-content-center gap-4 mb-3">
                                                                ${profilePictureUrl ? `
                                                                <div class="text-center">
                                                                    <img src="${profilePictureUrl}" alt="Passport Photo" class="img-thumbnail" style="width: 150px; height: 180px; object-fit: cover; border: 2px solid #7a0000; border-radius: 8px;"/>
                                                                    <div class="text-muted small mt-1">Passport Photo</div>
                                                                </div>
                                                                ` : ''}
                                                                <div class="text-center">
                                                                    <img id="inlineQrImg" alt="Member details QR" width="120" height="120"/>
                                                                    <div class="text-muted small mt-1">Scan for details</div>
                                                                </div>
                                                            </div>
                                                            <div class="small text-uppercase text-muted mt-2 mb-1">Personal</div>
                                                            <table class="table table-bordered table-striped align-middle interactive-table"><tbody>
                                                                ${row('fas fa-user', 'Full Name', data.full_name)}
                                                                ${row('fas fa-id-badge', 'Member ID', data.member_id, copyBtn(data.member_id, 'Copy ID', 'fas fa-copy'))}
                                                                ${row('fas fa-id-card', 'Membership Type', data.membership_type)}
                                                                ${row('fas fa-user-tag', 'Member Type', data.member_type)}
                                                                ${row('fas fa-phone', 'Phone', data.phone_number, telto(data.phone_number) + copyBtn(data.phone_number, 'Copy phone', 'fas fa-copy'))}
                                                                ${row('fas fa-envelope', 'Email', data.email, mailto(data.email) + copyBtn(data.email, 'Copy email', 'fas fa-copy'))}
                                                                ${row('fas fa-venus-mars', 'Gender', data.gender ? badge(data.gender.charAt(0).toUpperCase() + data.gender.slice(1), (data.gender || '').toLowerCase() === 'male' ? 'primary' : 'danger') : '—')}
                                                                ${row('fas fa-birthday-cake', 'Date of Birth', formatDateDisplay(data.date_of_birth))}
                                                                ${row('fas fa-graduation-cap', 'Education Level', data.education_level)}
                                                                ${row('fas fa-briefcase', 'Profession', data.profession)}
                                                                ${row('fas fa-id-card', 'NIDA Number', data.nida_number)}
                                                            </tbody></table>
                                                            <div class="small text-uppercase text-muted mt-3 mb-1">Location</div>
                                                            <table class="table table-bordered table-striped align-middle interactive-table"><tbody>
                                                                ${row('fas fa-map', 'Region', data.region ? badge(data.region, 'secondary') : '—', mapsBtn([data.region, 'Tanzania'].filter(Boolean).join(', ')))}
                                                                ${row('fas fa-city', 'District', data.district ? badge(data.district, 'secondary') : '—', mapsBtn([data.district, data.region, 'Tanzania'].filter(Boolean).join(', ')))}
                                                                ${row('fas fa-location-arrow', 'Ward', data.ward ? badge(data.ward, 'secondary') : '—', mapsBtn([data.ward, data.district, data.region, 'Tanzania'].filter(Boolean).join(', ')))}
                                                                ${row('fas fa-road', 'Street', data.street || '—', mapsBtn([data.street, data.ward, data.district, data.region, 'Tanzania'].filter(Boolean).join(', ')))}
                                                                ${row('fas fa-address-card', 'Address', data.address || '—', mapsBtn([data.address, data.street, data.ward, data.district, data.region, 'Tanzania'].filter(Boolean).join(', ')))}
                                                            </tbody></table>
                                                            <div class="small text-uppercase text-muted mt-3 mb-1">Family</div>
                                                            <table class="table table-bordered table-striped align-middle interactive-table"><tbody>
                                                                ${(() => {
                            const hasSpouseDetails = data.spouse_details || data.main_member_details || data.spouse_full_name || data.spouse_phone_number || data.spouse_email;
                            const hasChildren = Array.isArray(data.children) && data.children.length > 0;
                            const inferred = (hasSpouseDetails || hasChildren) ? 'yes' : 'no';
                            const v = (data.living_with_family && typeof data.living_with_family === 'string') ? data.living_with_family.toLowerCase() : '';
                            const value = v === 'yes' || v === 'no' ? v : inferred;
                            const pretty = value === 'yes' ? 'Yes' : (value === 'no' ? 'No' : '—');
                            return row('fas fa-users', 'Living with family', pretty);
                        })()}
                                                                ${row('fas fa-user-friends', 'Family relationship', data.family_relationship)}
                                                                ${row('fas fa-flag', 'Tribe', (data.tribe || '') + (data.other_tribe ? ` (${data.other_tribe})` : ''))}
                                                            </tbody></table>`;

                    // Add spouse section if available
                    const hasSpouseDetails = data.spouse_details || data.main_member_details || data.spouse_full_name || data.spouse_email || data.spouse_phone_number;
                    if (hasSpouseDetails) {
                        let spouseData, spouseTitle, spouseTribe, spouseId;
                        if (data.spouse_details) {
                            spouseData = data.spouse_details;
                            spouseTitle = (data.gender === 'male' ? 'Wife' : 'Husband');
                            spouseTribe = (spouseData.tribe || '') + (spouseData.tribe === 'Other' && spouseData.other_tribe ? ` (${spouseData.other_tribe})` : '');
                            spouseId = spouseData.id;
                        } else if (data.main_member_details) {
                            spouseData = data.main_member_details;
                            spouseTitle = (data.gender === 'male' ? 'Husband' : 'Wife');
                            spouseTribe = (spouseData.tribe || '') + (spouseData.tribe === 'Other' && spouseData.other_tribe ? ` (${spouseData.other_tribe})` : '');
                            spouseId = spouseData.id;
                        } else {
                            spouseData = data;
                            spouseTitle = (data.member_type === 'father' ? 'Wife' : (data.member_type === 'mother' ? 'Husband' : 'Spouse'));
                            spouseTribe = (data.spouse_tribe || '') + (data.spouse_tribe === 'Other' && data.spouse_other_tribe ? ` (${data.spouse_other_tribe})` : '');
                            spouseId = data.spouse_member_id;
                        }
                        html += `
                                                            <div class="small text-uppercase text-muted mt-3 mb-1">${spouseTitle}</div>
                                                            <table class="table table-bordered table-striped align-middle interactive-table"><tbody>
                                                                ${row('fas fa-heart', 'Marital Status', (data.marital_status ? data.marital_status.charAt(0).toUpperCase() + data.marital_status.slice(1) : '—'))}
                                                                ${row('fas fa-user', spouseTitle + ' Name', spouseData.full_name || data.spouse_full_name)}
                                                                ${row('fas fa-church', spouseTitle + ' Church Member', data.spouse_church_member ? (data.spouse_church_member === 'yes' ? 'Yes' : 'No') : '—')}
                                                                ${row('fas fa-id-badge', spouseTitle + ' Member Status', spouseId ? `<a href="/members/view?id=${spouseId}" class="text-primary">View as Member</a>` : 'Not a church member')}
                                                                ${row('fas fa-birthday-cake', spouseTitle + ' DOB', formatDateDisplay(spouseData.date_of_birth || data.spouse_date_of_birth))}
                                                                ${row('fas fa-graduation-cap', spouseTitle + ' Education', spouseData.education_level || data.spouse_education_level)}
                                                                ${row('fas fa-briefcase', spouseTitle + ' Profession', spouseData.profession || data.spouse_profession)}
                                                                ${row('fas fa-id-card', spouseTitle + ' NIDA', spouseData.nida_number || data.spouse_nida_number)}
                                                                ${row('fas fa-envelope', spouseTitle + ' Email', spouseData.email || data.spouse_email, (spouseData.email || data.spouse_email) ? (mailto(spouseData.email || data.spouse_email) + copyBtn(spouseData.email || data.spouse_email, 'Copy email', 'fas fa-copy')) : '')}
                                                                ${row('fas fa-phone', spouseTitle + ' Phone', spouseData.phone_number || data.spouse_phone_number, (spouseData.phone_number || data.spouse_phone_number) ? (telto(spouseData.phone_number || data.spouse_phone_number) + copyBtn(spouseData.phone_number || data.spouse_phone_number, 'Copy phone', 'fas fa-copy')) : '')}
                                                                ${row('fas fa-flag', spouseTitle + ' Tribe', spouseTribe)}
                                                            </tbody></table>`;
                    }

                    // Guardian section
                    if ((data.membership_type === 'temporary' || (data.membership_type === 'permanent' && data.member_type === 'independent')) && (data.guardian_name || data.guardian_phone || data.guardian_relationship)) {
                        html += `<div class="small text-uppercase text-muted mt-3 mb-1">Guardian</div>
                                                            <table class="table table-bordered table-striped align-middle interactive-table"><tbody>
                                                                ${row('fas fa-user-shield', 'Guardian Name', data.guardian_name)}
                                                                ${row('fas fa-phone-square', 'Guardian Phone', data.guardian_phone)}
                                                                ${row('fas fa-users-cog', 'Relationship', data.guardian_relationship)}
                                                            </tbody></table>`;
                    }

                    // Children section
                    if (data.membership_type === 'permanent' && (data.member_type === 'father' || data.member_type === 'mother') && Array.isArray(data.children) && data.children.length > 0) {
                        html += `<div class="small text-uppercase text-muted mt-3 mb-1">Children</div>
                                                            <table class="table table-bordered table-striped align-middle interactive-table"><thead><tr><th>Name</th><th>Gender</th><th>Date of Birth</th></tr></thead><tbody>`;
                        data.children.forEach(child => {
                            html += `<tr><td>${child.full_name || '-'}</td><td>${child.gender || '-'}</td><td>${formatDateDisplay(child.date_of_birth)}</td></tr>`;
                        });
                        html += `</tbody></table>`;
                    }

                    // Archive info
                    if (isArchived) {
                        html += `<div class="small text-uppercase text-muted mt-3 mb-1">Archive Info</div>
                                                            <table class="table table-bordered table-striped align-middle interactive-table"><tbody>
                                                                ${row('fas fa-archive', 'Reason for Archiving', archiveReason || 'Not specified')}
                                                                ${row('fas fa-calendar-times', 'Archived Date', m.archived_at ? formatDateDisplay(m.archived_at) : '—')}
                                                            </tbody></table>`;
                    }
                    html += `</div>`;

                    // Update modal content
                    const memberDetailsBody = document.getElementById('memberDetailsBody');
                    if (memberDetailsBody) {
                        memberDetailsBody.innerHTML = html;
                    }

                    // Show attendance history button and store member ID
                    const attendanceBtn = document.getElementById('btnAttendanceHistory');
                    if (attendanceBtn) {
                        attendanceBtn.style.display = 'inline-block';
                        attendanceBtn.setAttribute('data-member-id', m.id);
                    }

                    const idCardBtn = document.getElementById('btnIdCard');
                    if (idCardBtn) {
                        idCardBtn.style.display = 'inline-block';
                        idCardBtn.setAttribute('data-member-id', m.id);
                    }

                    // Show modal
                    const detailsModalEl = document.getElementById('memberDetailsModal');
                    if (detailsModalEl) {
                        const modal = new bootstrap.Modal(detailsModalEl);

                        // Attach refresh handler when modal closes (attach it fresh each time)
                        const refreshHandler = function () {
                            console.log('Member details modal closed - refreshing page...');
                            // Remove the listener to prevent multiple refreshes
                            detailsModalEl.removeEventListener('hidden.bs.modal', refreshHandler);
                            // Refresh the page
                            window.location.reload();
                        };

                        // Remove any existing listeners and attach fresh one
                        detailsModalEl.removeEventListener('hidden.bs.modal', refreshHandler);
                        detailsModalEl.addEventListener('hidden.bs.modal', refreshHandler);

                        // Set QR image src
                        const qrData = encodeURIComponent(qrPayload);
                        setTimeout(() => {
                            const img = document.getElementById('inlineQrImg');
                            if (img) {
                                try {
                                    const spinner = document.createElement('div');
                                    spinner.id = 'qrSpinner';
                                    spinner.className = 'spinner-border text-primary';
                                    spinner.setAttribute('role', 'status');
                                    if (img.parentElement) img.parentElement.insertBefore(spinner, img);
                                    img.style.display = 'none';
                                    img.onload = () => { spinner && (spinner.style.display = 'none'); img.style.display = 'inline-block'; };
                                    img.onerror = () => { spinner && (spinner.style.display = 'none'); };
                                } catch (e) { }
                                img.src = `https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=${qrData}`;
                            }
                        }, 0);
                        modal.show();

                        // Attach actions for export/print (if functions exist)
                        const btnPrint = document.getElementById('btnPrintDetails');
                        const btnCsv = document.getElementById('btnDownloadExcel');
                        const btnPdf = document.getElementById('btnDownloadPDF');
                        if (btnPrint && typeof confirmThen === 'function' && typeof printMemberDetails === 'function') {
                            btnPrint.onclick = () => confirmThen('Proceed to print this member details?', () => printMemberDetails());
                        }
                        if (btnCsv && typeof confirmThen === 'function' && typeof downloadMemberCSV === 'function') {
                            btnCsv.onclick = () => confirmThen('Download details as CSV?', () => downloadMemberCSV(m));
                        }
                        if (btnPdf && typeof confirmThen === 'function' && typeof downloadMemberPDF === 'function') {
                            btnPdf.onclick = () => confirmThen('Generate a PDF of these details?', () => downloadMemberPDF());
                        }

                        // Copy all details
                        const btnCopyAll = document.getElementById('btnCopyAllDetails');
                        if (btnCopyAll && typeof confirmThen === 'function') {
                            btnCopyAll.onclick = () => confirmThen('Copy all details to clipboard?', () => {
                                navigator.clipboard.writeText(`${qrPayload}`).then(() => Swal.fire({ icon: 'success', title: 'Copied', timer: 900, showConfirmButton: false })).catch(() => Swal.fire({ icon: 'error', title: 'Copy failed' }));
                            });
                        }
                    }
                })
                .catch((err) => {
                    const memberDetailsBody = document.getElementById('memberDetailsBody');
                    if (memberDetailsBody) {
                        memberDetailsBody.innerHTML = `
                                                                <div class="text-danger">Failed to load member details. ${err && err.message ? '(' + err.message + ')' : ''}</div>
                                                                <div class="mt-2">
                                                                    <button class="btn btn-sm btn-outline-primary" onclick="window.viewDetails(${id})"><i class="fas fa-redo me-1"></i>Retry</button>
                                                                </div>`;
                    }
                    const detailsModalEl = document.getElementById('memberDetailsModal');
                    if (detailsModalEl) {
                        new bootstrap.Modal(detailsModalEl).show();
                    }
                });
        };

        console.log('✓ viewDetails and openEdit functions defined - available immediately');
    </script>
    <script>
        // Archive member logic (robust, attaches only once)
        let archiveMemberId = null;
        function openArchiveModal(id) {
            document.getElementById('archive_member_id').value = id;
            document.getElementById('archive_reason').value = '';
            var modalEl = document.getElementById('archiveMemberModal');
            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }

        // Attach submit handler only once
        const form = document.getElementById('archiveMemberForm');
        if (form && !form._archiveHandlerAttached) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const id = document.getElementById('archive_member_id').value;
                const reason = document.getElementById('archive_reason').value.trim();
                if (!reason) {
                    Swal.fire({ icon: 'warning', title: 'Please provide a reason.' });
                    return;
                }
                const formData = new FormData();
                formData.append('reason', reason);
                formData.append('_method', 'DELETE');
                fetch(`{{ url('/members') }}/${id}/archive`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                    .then(r => r.json())
                    .then(res => {
                        if (res.success) {
                            Swal.fire({ icon: 'success', title: 'Member archived', timer: 1200, showConfirmButton: false }).then(() => location.reload());
                        } else {
                            Swal.fire({ icon: 'error', title: 'Archive failed', text: res.message || 'Please try again.' });
                        }
                    })
                    .catch(() => Swal.fire({ icon: 'error', title: 'Network error' }));
            });
            form._archiveHandlerAttached = true;
        }
        // Additional cleanup after page fully loads (backup)
        setTimeout(function () {
            const urlParams = new URLSearchParams(window.location.search);
            const allowedParams = ['tab', 'show', 'search', 'gender', 'region', 'district', 'ward'];
            const formFields = [
                'member_id', 'full_name', 'email', 'phone_number', 'date_of_birth',
                'education_level', 'profession', 'nida_number', 'membership_type', 'member_type',
                'living_with_family', 'family_relationship', 'guardian_name', 'guardian_phone',
                'guardian_relationship', 'marital_status', 'spouse_full_name', 'spouse_date_of_birth',
                'spouse_education_level', 'spouse_profession', 'spouse_nida_number', 'spouse_email',
                'spouse_phone_number', 'spouse_church_member', 'tribe', 'other_tribe', 'street',
                'address', 'spouse_tribe', 'spouse_other_tribe'
            ];
            let urlChanged = false;

            for (const [key, value] of urlParams.entries()) {
                // Remove form fields
                if (formFields.includes(key)) {
                    urlParams.delete(key);
                    urlChanged = true;
                }
                // Remove empty allowed params
                else if (allowedParams.includes(key) && (!value || value.trim() === '')) {
                    urlParams.delete(key);
                    urlChanged = true;
                }
            }

            if (urlChanged) {
                const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
                window.history.replaceState({}, '', newUrl);
                console.log('✓ URL cleaned again (backup cleanup)');
            }
        }, 100);

        // Activate correct tab based on ?tab=permanent|temporary|archived
        (function () {
            function getQueryParam(name) {
                const url = new URL(window.location.href);
                return url.searchParams.get(name);
            }
            const tab = getQueryParam('tab');
            if (tab && ['permanent', 'temporary', 'children', 'archived'].includes(tab)) {
                const trigger = document.getElementById(tab + '-tab');
                if (trigger) {
                    // Bootstrap 5 tab activation
                    if (window.bootstrap && bootstrap.Tab) {
                        const tabObj = new bootstrap.Tab(trigger);
                        tabObj.show();
                    } else {
                        // fallback: click
                        trigger.click();
                    }
                }
            }
        })();

        // Auto-show member details modal if redirected from /members/{id}
        // Use URL parameter instead of session to avoid infinite loops
        (function () {
            const urlParams = new URLSearchParams(window.location.search);
            const showMemberId = urlParams.get('show');
            if (showMemberId) {
                const memberId = parseInt(showMemberId);
                let attempts = 0;
                const maxAttempts = 30; // 30 attempts * 200ms = 6 seconds max wait

                // Function to check if full implementation is loaded
                const checkAndShow = function () {
                    attempts++;

                    // Check if viewDetails function is available
                    if (typeof window.viewDetails === 'function') {
                        console.log('Full implementation loaded, showing member details for ID:', memberId);
                        // Remove the parameter from URL first to prevent re-triggering
                        urlParams.delete('show');
                        const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
                        window.history.replaceState({}, '', newUrl);

                        // Call viewDetails
                        try {
                            window.viewDetails(memberId);
                        } catch (e) {
                            console.error('Error calling viewDetails:', e);
                            // Fallback: try to find and click the view button
                            const viewBtn = document.querySelector(`[onclick*="viewDetails(${memberId})"]`);
                            if (viewBtn) {
                                viewBtn.click();
                            }
                        }
                    } else if (attempts < maxAttempts) {
                        // Try again after a short delay
                        setTimeout(checkAndShow, 200);
                    } else {
                        console.warn('Full implementation not loaded after', maxAttempts, 'attempts. Trying fallback.');
                        // Final fallback: try to find and click the view button
                        urlParams.delete('show');
                        const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
                        window.history.replaceState({}, '', newUrl);
                        const viewBtn = document.querySelector(`[onclick*="viewDetails(${memberId})"]`);
                        if (viewBtn) {
                            viewBtn.click();
                        } else {
                            console.error('Could not find view button for member ID:', memberId);
                        }
                    }
                };

                // Start checking after DOM is ready
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', function () {
                        setTimeout(checkAndShow, 500); // Wait 500ms after DOMContentLoaded
                    });
                } else {
                    // DOM already loaded, start checking immediately
                    setTimeout(checkAndShow, 500);
                }
            }
        })();
    </script>
    <script>
        // Globals to share state between details/print
        // Use window.currentDetailsMember instead of local variable to avoid conflicts
        if (typeof window.currentDetailsMember === 'undefined') {
            window.currentDetailsMember = null;
        }
        function formatDateDisplay(value) {
            if (!value) return '-';
            try {
                const d = new Date(value);
                if (!isNaN(d.getTime())) {
                    const dd = String(d.getDate()).padStart(2, '0');
                    const mm = String(d.getMonth() + 1).padStart(2, '0');
                    const yyyy = d.getFullYear();
                    return `${dd}/${mm}/${yyyy}`;
                }
            } catch (e) { }
            const datePart = String(value).split('T')[0];
            if (datePart && datePart.includes('-')) {
                const [y, m, d] = datePart.split('-');
                if (y && m && d) return `${d}/${m}/${y}`;
            }
            return datePart || '-';
        }
        function confirmThen(message, onConfirm) {
            Swal.fire({ title: message, icon: 'question', showCancelButton: true, confirmButtonText: 'Yes', cancelButtonText: 'No', confirmButtonColor: '#7a0000', cancelButtonColor: '#6c757d' }).then(r => { if (r.isConfirmed) { try { onConfirm && onConfirm(); } catch (e) { console.error(e); } } });
        }

        function handleAction(fn) { confirmThen('Proceed with this action?', fn); return false; }

        // View switching functionality
        function toggleActions() {
            // Only toggle on mobile devices
            if (window.innerWidth > 768) {
                return; // Don't toggle on desktop
            }

            const actionsBody = document.getElementById('actionsBody');
            const actionsIcon = document.getElementById('actionsToggleIcon');

            // Check computed style to see if it's visible
            const computedStyle = window.getComputedStyle(actionsBody);
            const isVisible = computedStyle.display !== 'none';

            if (isVisible) {
                actionsBody.style.display = 'none';
                actionsIcon.classList.remove('fa-chevron-up');
                actionsIcon.classList.add('fa-chevron-down');
            } else {
                actionsBody.style.display = 'block';
                actionsIcon.classList.remove('fa-chevron-down');
                actionsIcon.classList.add('fa-chevron-up');
            }
        }

        // Handle window resize
        window.addEventListener('resize', function () {
            const actionsBody = document.getElementById('actionsBody');
            const actionsIcon = document.getElementById('actionsToggleIcon');

            if (window.innerWidth > 768) {
                // Always show on desktop
                actionsBody.style.display = 'block';
                actionsIcon.classList.remove('fa-chevron-up');
                actionsIcon.classList.add('fa-chevron-down');
            } else {
                // On mobile, ensure it starts collapsed
                const computedStyle = window.getComputedStyle(actionsBody);
                if (computedStyle.display !== 'none' && !actionsBody.hasAttribute('data-user-opened')) {
                    actionsBody.style.display = 'none';
                    actionsIcon.classList.remove('fa-chevron-up');
                    actionsIcon.classList.add('fa-chevron-down');
                }
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function () {
            const actionsBody = document.getElementById('actionsBody');
            const actionsIcon = document.getElementById('actionsToggleIcon');

            if (window.innerWidth <= 768) {
                // Mobile: start collapsed
                actionsBody.style.display = 'none';
                actionsIcon.classList.remove('fa-chevron-up');
                actionsIcon.classList.add('fa-chevron-down');
            } else {
                // Desktop: always show
                actionsBody.style.display = 'block';
                actionsIcon.style.display = 'none';
            }
        });

        function switchView(view) {
            console.log('Switching to view:', view);
            const listView = document.getElementById('listView');
            const cardView = document.getElementById('cardView');
            const listBtn = document.getElementById('listViewBtn');
            const cardBtn = document.getElementById('cardViewBtn');

            console.log('Elements found:', { listView, cardView, listBtn, cardBtn });

            if (view === 'list') {
                listView.style.display = 'block';
                cardView.style.display = 'none';
                listBtn.classList.add('active');
                cardBtn.classList.remove('active');
                localStorage.setItem('memberViewPreference', 'list');
                console.log('Switched to list view');
            } else {
                listView.style.display = 'none';
                cardView.style.display = 'block';
                listBtn.classList.remove('active');
                cardBtn.classList.add('active');
                localStorage.setItem('memberViewPreference', 'card');
                console.log('Switched to card view');
            }
        }

        // Load saved view preference
        document.addEventListener('DOMContentLoaded', function () {
            console.log('DOM Content Loaded');
            const savedView = localStorage.getItem('memberViewPreference');
            console.log('Saved view preference:', savedView);
            if (savedView === 'card') {
                switchView('card');
            }

            // Test if buttons are working
            const listBtn = document.getElementById('listViewBtn');
            const cardBtn = document.getElementById('cardViewBtn');
            console.log('View buttons found:', { listBtn, cardBtn });

            if (listBtn) {
                listBtn.addEventListener('click', function () {
                    console.log('List view button clicked');
                    switchView('list');
                });
            }

            if (cardBtn) {
                cardBtn.addEventListener('click', function () {
                    console.log('Card view button clicked');
                    switchView('card');
                });
            }
        });

        // viewDetails is already defined in head - verify it's still there
        // Only redefine if it's completely missing (not if it exists but doesn't have 'fetch')
        // This prevents overwriting the full implementation
        if (typeof window.viewDetails !== 'function') {
            console.warn('viewDetails function missing, defining placeholder...');
            // Function should already be defined in head, but if not, define a placeholder
            window.viewDetails = function (id) {
                console.error('viewDetails function not properly loaded');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Function Not Available',
                        text: 'Please refresh the page.',
                        confirmButtonText: 'OK'
                    });
                }
            };
        } else {
            console.log('✓ viewDetails function verified - available');
        }

        // Use window.currentDetailsMember for backward compatibility
        // All references should use window.currentDetailsMember to avoid conflicts

        // Ensure openEdit sets state for chooser and header buttons
        // currentEditMember is now stored in window.currentEditMember (defined in head)
        // Use window.currentEditMember instead of local variable to avoid conflicts

        // openEdit is already defined in head - this is just for backward compatibility
        // But we'll keep it here in case it's called directly
        if (typeof window.openEdit !== 'function') {
            window.openEdit = function (id) {
                console.error('openEdit not properly loaded');
            };
        }

        // EDIT functionality removed by request

        // Wire chooser buttons to open respective modals with prefill
        // Ensure these are attached after DOM is ready
        // Legacy hook (old chooser buttons were removed). Keeping as no-op for safety.
        function setupEditButtonListeners() { /* no-op */ }

        // Setup edit button listeners when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupEditButtonListeners);
        } else {
            setupEditButtonListeners();
        }

        // Centralized handler for edit form submission - make it globally accessible
        // Define it immediately at the start of scripts section
        window.handleEditFormSubmit = function (e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
            }

            console.log('✓ Submit button clicked - processing form submission');

            const form = document.getElementById('editMemberForm');
            if (!form) {
                console.error('Form not found');
                return;
            }

            // Check form validity
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                const firstInvalid = form.querySelector(':invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalid.focus();
                }
                return;
            }

            // Get member ID
            let memberId = window.currentEditMember?.id;
            if (!memberId) {
                const hiddenIdInput = document.getElementById('edit_member_id');
                if (hiddenIdInput && hiddenIdInput.value) {
                    memberId = parseInt(hiddenIdInput.value);
                    console.log('Got member ID from hidden input:', memberId);
                }
            }

            if (!memberId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Member ID not found. Please close the modal, refresh the page, and try again.'
                });
                return;
            }

            // Collect form data
            const formData = new FormData(form);
            formData.append('_method', 'PUT');
            formData.append('_token', '{{ csrf_token() }}');

            const emailValue = document.getElementById('edit_email')?.value.trim() || '';
            formData.set('email', emailValue);

            console.log('Submitting edit form for member ID:', memberId);

            // Show loading
            Swal.fire({
                title: 'Updating Member...',
                text: 'Please wait while we save your changes',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit
            const updateUrl = `{{ url('/members') }}/${memberId}`;
            fetch(updateUrl, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData,
                cache: 'no-cache'
            })
                .then(response => {
                    return response.text().then(text => {
                        try {
                            const json = JSON.parse(text);
                            if (!response.ok) {
                                if (response.status === 422 && json.errors) {
                                    const errorMessages = Object.entries(json.errors)
                                        .map(([field, messages]) => `${field}: ${Array.isArray(messages) ? messages.join(', ') : messages}`)
                                        .join('\n');
                                    throw new Error('Validation failed:\n' + errorMessages);
                                }
                                throw new Error(json.message || json.error || 'Update failed');
                            }
                            return json;
                        } catch (e) {
                            if (text.includes('<!DOCTYPE') || text.includes('<html')) {
                                throw new Error('Server returned HTML instead of JSON. Check server logs.');
                            }
                            throw new Error('Invalid response format: ' + text.substring(0, 100));
                        }
                    });
                })
                .then(data => {
                    if (data.success) {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('memberEditModal'));
                        if (modal) modal.hide();

                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Member information updated successfully',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            const cleanUrl = window.location.pathname;
                            window.history.replaceState({}, '', cleanUrl);
                            setTimeout(() => {
                                window.location.href = cleanUrl;
                            }, 50);
                        });
                    } else {
                        throw new Error(data.message || 'Update failed');
                    }
                })
                .catch(error => {
                    console.error('Error updating member:', error);
                    let errorMessage = error.message || 'Failed to update member. Please check your input and try again.';
                    if (errorMessage.includes('\n')) {
                        errorMessage = errorMessage.split('\n').join('<br>');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        html: `<p>${errorMessage}</p><p class="text-muted small mt-2">Check the browser console (F12) for more details.</p>`,
                        confirmButtonText: 'OK',
                        width: '500px'
                    });
                });
        }

        // Setup edit member form submit handler - CRITICAL: Must prevent default form submission
        function setupFormSubmitHandlers() {
            const editForm = document.getElementById('editMemberForm');
            const submitBtn = document.getElementById('editMemberSubmitBtn');

            if (editForm) {
                // CRITICAL: Prevent any default form submission
                editForm.onsubmit = function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    console.error('BLOCKED: Form tried to submit normally');
                    return false;
                };

                editForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    console.error('BLOCKED: Form submit event caught and blocked');
                    return false;
                }, true); // Use capture phase
            }

            // Handle button click - use direct onclick for reliability
            if (submitBtn) {
                submitBtn.onclick = handleEditFormSubmit;
                console.log('✓ Submit button handler attached');
            } else {
                console.warn('Submit button not found - will retry when modal opens');
            }
        }

        // Attach handler when modal is shown - this is the most reliable way
        const editModal = document.getElementById('memberEditModal');
        if (editModal) {
            editModal.addEventListener('shown.bs.modal', function () {
                console.log('✓ Modal shown - setting up handlers');

                // Prevent form submission
                const form = document.getElementById('editMemberForm');
                if (form) {
                    form.method = 'GET';
                    form.action = 'javascript:void(0);';
                    form.onsubmit = function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                        console.error('BLOCKED: Form submit in modal');
                        return false;
                    };
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                        return false;
                    }, true);
                }

                // Attach button handler - wait a bit for DOM to be ready
                setTimeout(function () {
                    const btn = document.getElementById('editMemberSubmitBtn');
                    if (btn) {
                        // Remove any existing handlers by cloning
                        const newBtn = btn.cloneNode(true);
                        btn.parentNode.replaceChild(newBtn, btn);
                        const freshBtn = document.getElementById('editMemberSubmitBtn');

                        // Attach handler - use both addEventListener and onclick
                        freshBtn.addEventListener('click', handleEditFormSubmit);
                        freshBtn.onclick = handleEditFormSubmit; // Also set onclick as backup
                        console.log('✓ Submit button handler attached to fresh button');
                    } else {
                        console.error('Submit button not found after modal shown');
                    }
                }, 200);
            });
        }

        // Location cascading function removed - Location Information section removed from edit modal

        // Tribe dropdowns removed from edit modal

        // Setup form submit handlers IMMEDIATELY - don't wait for DOMContentLoaded
        // This is critical because the form might submit before DOMContentLoaded fires
        setupFormSubmitHandlers();

        // Also set up again when DOM is fully ready (in case form is added dynamically)
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function () {
                setTimeout(setupFormSubmitHandlers, 0);
            });
        } else {
            // DOM already ready, set up again to be safe
            setTimeout(setupFormSubmitHandlers, 0);
        }

        // Also attach handler when modal is shown (in case form is recreated)
        const editModal = document.getElementById('memberEditModal');
        if (editModal) {
            editModal.addEventListener('shown.bs.modal', function () {
                setTimeout(setupFormSubmitHandlers, 100);
            });
        }

        // Quick Add modal cascading + tribe
        function setupCascadingForAdd() {
            // Always reset to a clean state first
            resetAddMemberForm();
            ensureLocationsLoaded().then(() => {
                populateSelect(document.getElementById('add_region'), Object.keys(tzLocations), 'Select region');
                const regionEl = document.getElementById('add_region');
                const districtEl = document.getElementById('add_district');
                const wardEl = document.getElementById('add_ward');
                function updateDistricts() {
                    populateSelect(districtEl, regionEl.value ? Object.keys(tzLocations[regionEl.value] || {}) : [], 'Select district');
                    updateWards();
                }
                function updateWards() {
                    const wards = regionEl.value && districtEl.value ? (tzLocations[regionEl.value]?.[districtEl.value] || []) : [];
                    populateSelect(wardEl, wards, 'Select ward');
                }
                regionEl.onchange = updateDistricts;
                districtEl.onchange = updateWards;
            });
            populateSelect(document.getElementById('add_tribe'), tribeList, 'Select tribe');
            const tribeEl = document.getElementById('add_tribe');
            const otherGroup = document.getElementById('add_other_tribe_group');
            const otherInput = document.getElementById('add_other_tribe');
            tribeEl.onchange = () => { const show = tribeEl.value === 'Other'; otherGroup.style.display = show ? '' : 'none'; if (!show) otherInput.value = ''; };
        }

        function resetAddMemberForm() {
            // Reset form fields
            const form = document.getElementById('quickAddMemberForm');
            if (form && typeof form.reset === 'function') { form.reset(); }
            // Hide other tribe input
            const otherGroup = document.getElementById('add_other_tribe_group');
            if (otherGroup) otherGroup.style.display = 'none';
            // Clear any existing options in selects to avoid stale state
            ['add_region', 'add_district', 'add_ward', 'add_tribe'].forEach(id => { const s = document.getElementById(id); if (s) s.innerHTML = ''; });
            // Remove any validation classes/messages if present
            const modal = document.getElementById('addMemberModal');
            if (modal) {
                modal.querySelectorAll('.is-invalid, .is-valid').forEach(el => el.classList.remove('is-invalid', 'is-valid'));
                modal.querySelectorAll('.invalid-feedback, .valid-feedback').forEach(el => { el.textContent = ''; el.style.display = 'none'; });
            }
        }

        document.getElementById('addMemberModal').addEventListener('show.bs.modal', setupCascadingForAdd);
        // Ensure fresh state after cancel/close
        document.getElementById('addMemberModal').addEventListener('hidden.bs.modal', resetAddMemberForm);

        document.getElementById('quickAddMemberForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const fd = new FormData();
            fd.append('full_name', document.getElementById('add_full_name').value);
            fd.append('gender', document.getElementById('add_gender').value);
            fd.append('phone_number', document.getElementById('add_phone_number').value);
            fd.append('email', document.getElementById('add_email').value);
            fd.append('region', document.getElementById('add_region').value);
            fd.append('district', document.getElementById('add_district').value);
            fd.append('ward', document.getElementById('add_ward').value);
            const tribeVal = document.getElementById('add_tribe').value;
            fd.append('tribe', tribeVal === 'Other' ? '' : tribeVal);
            fd.append('other_tribe', document.getElementById('add_other_tribe').value);
            fetch(`{{ url('/members') }}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: fd })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        // Reset form state before reload just in case
                        resetAddMemberForm();
                        Swal.fire({ icon: 'success', title: 'Member registered', timer: 1400, showConfirmButton: false }).then(() => location.reload());
                    } else {
                        Swal.fire({ icon: 'error', title: 'Registration failed', text: res.message || 'Please review and try again.' });
                    }
                })
                .catch(() => Swal.fire({ icon: 'error', title: 'Network error' }));
        });

        function downloadArchiveReport(member, reason) {
            try {
                console.log('Starting download for member:', member);

                // Generate the report HTML
                const reportHTML = generateArchiveReportHTML(member, reason);
                console.log('Generated HTML length:', reportHTML.length);

                // Method 1: Try blob download first
                if (window.Blob && window.URL) {
                    try {
                        const blob = new Blob([reportHTML], {
                            type: 'text/html;charset=utf-8'
                        });
                        console.log('Created blob:', blob);

                        const url = window.URL.createObjectURL(blob);
                        console.log('Created URL:', url);

                        const link = document.createElement('a');
                        link.href = url;
                        link.download = `Member_Archive_Report_${member.member_id || member.id || 'Unknown'}_${new Date().toISOString().split('T')[0]}.html`;
                        link.style.display = 'none';

                        console.log('Download filename:', link.download);

                        document.body.appendChild(link);

                        // Trigger download
                        setTimeout(() => {
                            link.click();
                            console.log('Download triggered');

                            setTimeout(() => {
                                document.body.removeChild(link);
                                window.URL.revokeObjectURL(url);
                                console.log('Cleanup completed');
                            }, 100);
                        }, 100);

                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Report Downloaded',
                            text: 'Archive report has been downloaded successfully!',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        return;
                    } catch (blobError) {
                        console.log('Blob method failed, trying alternative:', blobError);
                    }
                }

                // Method 2: Fallback - open in new window and let user save
                const newWindow = window.open('', '_blank');
                if (newWindow) {
                    newWindow.document.write(reportHTML);
                    newWindow.document.close();
                    newWindow.focus();

                    // Show instructions
                    Swal.fire({
                        icon: 'info',
                        title: 'Report Opened',
                        html: `
                                                                    <p>The report has been opened in a new window.</p>
                                                                    <p><strong>To save the file:</strong></p>
                                                                    <ol class="text-start">
                                                                        <li>Press <kbd>Ctrl+S</kbd> (Windows) or <kbd>Cmd+S</kbd> (Mac)</li>
                                                                        <li>Choose a location to save the file</li>
                                                                        <li>The file will be saved as an HTML file</li>
                                                                    </ol>
                                                                `,
                        showConfirmButton: true,
                        confirmButtonText: 'Got it!'
                    });
                } else {
                    throw new Error('Could not open new window');
                }

            } catch (error) {
                console.error('Download error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Download Failed',
                    html: `
                                                                <p>There was an error downloading the report.</p>
                                                                <p><strong>Alternative options:</strong></p>
                                                                <ul class="text-start">
                                                                    <li>Use the "Print Report" option and save as PDF</li>
                                                                    <li>Copy the report content manually</li>
                                                                    <li>Try using a different browser</li>
                                                                </ul>
                                                            `,
                    showConfirmButton: true
                });
            }
        }

        function generateArchiveReportHTML(member, reason) {
            return `
                                                        <!DOCTYPE html>
                                                        <html lang="en">
                                                        <head>
                                                            <meta charset="UTF-8">
                                                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                                            <title>Member Archive Report - ${member.full_name}</title>
                                                            <style>
                                                                * {
                                                                    margin: 0;
                                                                    padding: 0;
                                                                    box-sizing: border-box;
                                                                }

                                                                body {
                                                                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                                                                    background: #f8f9fa;
                                                                    padding: 20px;
                                                                    line-height: 1.6;
                                                                }

                                                                .report-container {
                                                                    max-width: 600px;
                                                                    margin: 0 auto;
                                                                    background: white;
                                                                    border-radius: 15px;
                                                                    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                                                                    overflow: hidden;
                                                                }

                                                                .report-header {
                                                                    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
                                                                    color: white;
                                                                    padding: 25px;
                                                                    text-align: center;
                                                                }

                                                                .report-header h1 {
                                                                    font-size: 24px;
                                                                    margin-bottom: 10px;
                                                                    font-weight: 600;
                                                                }

                                                                .report-header .subtitle {
                                                                    font-size: 14px;
                                                                    opacity: 0.9;
                                                                }

                                                                .report-body {
                                                                    padding: 30px;
                                                                }

                                                                .member-info {
                                                                    background: #f8f9fa;
                                                                    border-radius: 10px;
                                                                    padding: 20px;
                                                                    margin-bottom: 25px;
                                                                }

                                                                .member-info h3 {
                                                                    color: #495057;
                                                                    margin-bottom: 15px;
                                                                    font-size: 18px;
                                                                    border-bottom: 2px solid #dee2e6;
                                                                    padding-bottom: 10px;
                                                                }

                                                                .info-grid {
                                                                    display: grid;
                                                                    grid-template-columns: 1fr 1fr;
                                                                    gap: 15px;
                                                                }

                                                                .info-item {
                                                                    display: flex;
                                                                    flex-direction: column;
                                                                }

                                                                .info-label {
                                                                    font-weight: 600;
                                                                    color: #6c757d;
                                                                    font-size: 12px;
                                                                    text-transform: uppercase;
                                                                    letter-spacing: 0.5px;
                                                                    margin-bottom: 5px;
                                                                }

                                                                .info-value {
                                                                    color: #212529;
                                                                    font-size: 14px;
                                                                    font-weight: 500;
                                                                }

                                                                .archive-reason {
                                                                    background: #fff3cd;
                                                                    border: 1px solid #ffeaa7;
                                                                    border-radius: 10px;
                                                                    padding: 20px;
                                                                    margin-bottom: 25px;
                                                                }

                                                                .archive-reason h3 {
                                                                    color: #856404;
                                                                    margin-bottom: 15px;
                                                                    font-size: 18px;
                                                                    display: flex;
                                                                    align-items: center;
                                                                }

                                                                .archive-reason h3::before {
                                                                    content: "📋";
                                                                    margin-right: 10px;
                                                                }

                                                                .reason-text {
                                                                    color: #856404;
                                                                    font-size: 14px;
                                                                    line-height: 1.6;
                                                                    background: white;
                                                                    padding: 15px;
                                                                    border-radius: 8px;
                                                                    border-left: 4px solid #ffc107;
                                                                }

                                                                .financial-note {
                                                                    background: #d1ecf1;
                                                                    border: 1px solid #bee5eb;
                                                                    border-radius: 10px;
                                                                    padding: 20px;
                                                                    text-align: center;
                                                                }

                                                                .financial-note h4 {
                                                                    color: #0c5460;
                                                                    margin-bottom: 10px;
                                                                    font-size: 16px;
                                                                }

                                                                .financial-note p {
                                                                    color: #0c5460;
                                                                    font-size: 14px;
                                                                    margin: 0;
                                                                }

                                                                .report-footer {
                                                                    background: #f8f9fa;
                                                                    padding: 20px;
                                                                    text-align: center;
                                                                    border-top: 1px solid #dee2e6;
                                                                }

                                                                .report-footer p {
                                                                    color: #6c757d;
                                                                    font-size: 12px;
                                                                    margin: 0;
                                                                }

                                                                .date-time {
                                                                    color: #6c757d;
                                                                    font-size: 12px;
                                                                    margin-top: 10px;
                                                                }

                                                                @media print {
                                                                    body {
                                                                        background: white;
                                                                        padding: 0;
                                                                    }

                                                                    .report-container {
                                                                        box-shadow: none;
                                                                        border-radius: 0;
                                                                    }
                                                                }
                                                            </style>
                                                        </head>
                                                        <body>
                                                            <div class="report-container">
                                                                <div class="report-header">
                                                                    <h1>📦 Member Archive Report</h1>
                                                                    <p class="subtitle">Member has been moved to archived status</p>
                                                                </div>

                                                                <div class="report-body">
                                                                    <div class="member-info">
                                                                        <h3>👤 Member Information</h3>
                                                                        <div class="info-grid">
                                                                            <div class="info-item">
                                                                                <span class="info-label">Full Name</span>
                                                                                <span class="info-value">${member.full_name || 'N/A'}</span>
                                                                            </div>
                                                                            <div class="info-item">
                                                                                <span class="info-label">Member ID</span>
                                                                                <span class="info-value">${member.member_id || 'N/A'}</span>
                                                                            </div>
                                                                            <div class="info-item">
                                                                                <span class="info-label">Phone Number</span>
                                                                                <span class="info-value">${member.phone_number || 'N/A'}</span>
                                                                            </div>
                                                                            <div class="info-item">
                                                                                <span class="info-label">Email</span>
                                                                                <span class="info-value">${member.email || 'N/A'}</span>
                                                                            </div>
                                                                            <div class="info-item">
                                                                                <span class="info-label">Gender</span>
                                                                                <span class="info-value">${member.gender ? member.gender.charAt(0).toUpperCase() + member.gender.slice(1) : 'N/A'}</span>
                                                                            </div>
                                                                            <div class="info-item">
                                                                                <span class="info-label">Membership Type</span>
                                                                                <span class="info-value">${member.membership_type ? member.membership_type.charAt(0).toUpperCase() + member.membership_type.slice(1) : 'N/A'}</span>
                                                                            </div>
                                                                            <div class="info-item">
                                                                                <span class="info-label">Date of Birth</span>
                                                                                <span class="info-value">${member.date_of_birth ? new Date(member.date_of_birth).toLocaleDateString() : 'N/A'}</span>
                                                                            </div>
                                                                            <div class="info-item">
                                                                                <span class="info-label">Registration Date</span>
                                                                                <span class="info-value">${member.created_at ? new Date(member.created_at).toLocaleDateString() : 'N/A'}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="archive-reason">
                                                                        <h3>Archive Reason</h3>
                                                                        <div class="reason-text">${reason}</div>
                                                                    </div>

                                                                    <div class="financial-note">
                                                                        <h4>💰 Financial Records Preserved</h4>
                                                                        <p>All financial records including tithes, offerings, donations, and pledges have been preserved and remain intact in the system.</p>
                                                                    </div>
                                                                </div>

                                                                <div class="report-footer">
                                                                    <p><strong>Waumini Link Church Management System</strong></p>
                                                                    <p class="date-time">Report generated on ${new Date().toLocaleString()}</p>
                                                                </div>
                                                            </div>
                                                        </body>
                                                        </html>
                                                    `;
        }

        function printArchiveReport(member, reason) {
            // Create a new window for printing
            const printWindow = window.open('', '_blank', 'width=800,height=600');

            // Generate the report HTML using the shared function
            const reportHTML = generateArchiveReportHTML(member, reason);

            // Write the HTML to the new window
            printWindow.document.write(reportHTML);
            printWindow.document.close();

            // Focus the window and trigger print dialog
            printWindow.focus();
            setTimeout(() => {
                printWindow.print();
            }, 500);
        }

        // confirmDelete is already defined at the top of the scripts section (line 1749)
        // The old complex version below has been removed to avoid conflicts
        // The simplified version at the top handles archiving directly
        const displayName = memberName ? ` ${memberName}` : '';
        // Show reason input form
        Swal.fire({
            title: 'Archive Member',
            html: `
                                                            <div class="mb-3">
                                                                <label for="archive-reason" class="form-label">Reason for archiving${displayName}:</label>
                                                                <textarea id="archive-reason" class="form-control" rows="3" placeholder="Please provide a reason for archiving this member..." required></textarea>
                                                            </div>
                                                            <div class="alert alert-info">
                                                                <i class="fas fa-info-circle me-2"></i>
                                                                <strong>Note:</strong> The member will be moved to archived status and all their financial records will be preserved.
                                                            </div>
                                                        `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Archive Member',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#dc3545',
            preConfirm: () => {
                const reason = document.getElementById('archive-reason').value.trim();
                if (!reason) {
                    Swal.showValidationMessage('Please provide a reason for archiving this member.');
                    return false;
                }
                return reason;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Archiving...',
                    text: 'Please wait while we archive the member.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Use different endpoint for archived vs active members
                const deleteUrl = isArchived ? `{{ url('/members/archived') }}/${id}` : `{{ url('/members') }}/${id}`;
                console.log('Delete URL:', deleteUrl);
                console.log('Archive reason:', result.value);

                // Prepare request body with reason
                const requestBody = {
                    reason: result.value
                };

                // Use a simple fetch request with proper error handling
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestBody)
                })
                    .then(response => {
                        console.log('Delete response status:', response.status);
                        if (response.ok) {
                            return response.json();
                        } else if (response.status === 403) {
                            // Handle 403 Forbidden - permission denied
                            return response.json().then(data => {
                                throw new Error(data.message || 'You do not have permission to archive members. Please contact your administrator.');
                            });
                        } else if (response.status === 404) {
                            throw new Error('Member not found');
                        } else if (response.status === 419) {
                            // CSRF token expired - reload page to get new token
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Refreshing...',
                                    text: 'Please wait while we refresh your session.',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    showConfirmButton: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });
                                setTimeout(() => {
                                    window.location.reload();
                                }, 500);
                            } else {
                                window.location.reload();
                            }
                            return;
                        } else if (response.status === 422) {
                            // Parse the 422 response to get the actual error message
                            return response.json().then(data => {
                                throw new Error(data.message || 'Validation error occurred');
                            });
                        } else {
                            // Try to parse error message from response
                            return response.json().then(data => {
                                throw new Error(data.message || `Server error: ${response.status}`);
                            }).catch(() => {
                                throw new Error(`Server error: ${response.status}`);
                            });
                        }
                    })
                    .then(data => {
                        console.log('Delete response data:', data);
                        if (data.success) {
                            // Remove the row from the table
                            const row = document.getElementById(`row-${id}`);
                            if (row) {
                                row.remove();
                            }

                            // Also remove from card view if it exists
                            const card = document.querySelector(`[data-member-id="${id}"]`);
                            if (card) {
                                card.remove();
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'Member Archived',
                                html: `
                                                                                    <div class="text-start">
                                                                                        <p><strong>Reason:</strong> ${result.value}</p>
                                                                                        <p>The member has been moved to archived status. All financial records (tithes, offerings, donations, pledges) have been preserved and remain intact.</p>
                                                                                    </div>
                                                                                `,
                                showConfirmButton: true,
                                showCancelButton: true,
                                showDenyButton: true,
                                confirmButtonText: '📄 Download Report',
                                denyButtonText: '🖨️ Print Report',
                                cancelButtonText: 'Close',
                                confirmButtonColor: '#28a745',
                                denyButtonColor: '#007bff',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                timer: 0
                            }).then((actionResult) => {
                                if (actionResult.isConfirmed) {
                                    downloadArchiveReport(data.member, result.value);
                                } else if (actionResult.isDenied) {
                                    printArchiveReport(data.member, result.value);
                                }

                                // Only reload after user has made a choice
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Delete failed',
                                text: data.message || 'Please try again.',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Delete error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message,
                            confirmButtonText: 'OK'
                        });
                    });
            }
        });
                                                        })

        // Simple client-side, real-time filtering
        function filterTable() {
            const q = (document.getElementById('searchInput').value || '').trim().toLowerCase();
            const gender = (document.getElementById('genderFilter').value || '').toLowerCase();
            const region = (document.getElementById('regionFilter').value || '').toLowerCase();
            const district = (document.getElementById('districtFilter').value || '').toLowerCase();
            const ward = (document.getElementById('wardFilter').value || '').toLowerCase();
            const rows = document.querySelectorAll('#membersTable tbody tr');
            let visibleIndex = 0;
            rows.forEach((row) => {
                const textMatch = [
                    row.dataset.name,
                    row.dataset.memberid,
                    row.dataset.phone,
                    row.dataset.email
                ].some(v => (v || '').includes(q));
                const genderMatch = !gender || (row.dataset.gender === gender);
                const regionMatch = !region || (row.dataset.region === region);
                const districtMatch = !district || (row.dataset.district === district);
                const wardMatch = !ward || (row.dataset.ward === ward);
                const show = textMatch && genderMatch && regionMatch && districtMatch && wardMatch;
                row.style.display = show ? '' : 'none';
                if (show) {
                    // Re-number visible rows client-side
                    const numberCell = row.querySelector('td');
                    if (numberCell) numberCell.textContent = String(++visibleIndex);
                }
            });
        }

        ['searchInput', 'genderFilter', 'regionFilter', 'districtFilter', 'wardFilter']
            .forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                const evt = id === 'searchInput' ? 'input' : 'change';
                el.addEventListener(evt, filterTable);
            });

        function downloadMemberCSV(m) {
            const headers = ['Full Name', 'Member ID', 'Phone', 'Email', 'Gender', 'Date of Birth', 'NIDA Number', 'Region', 'District', 'Ward', 'Street', 'Address', 'Living with family', 'Family relationship', 'Tribe', 'Other tribe'];
            const values = [
                m.full_name || '',
                m.member_id || '',
                m.phone_number || '',
                m.email || '',
                m.gender || '',
                m.date_of_birth || '',
                m.nida_number || '',
                m.region || '',
                m.district || '',
                m.ward || '',
                m.street || '',
                m.address || '',
                m.living_with_family || '',
                m.family_relationship || '',
                m.tribe || '',
                m.other_tribe || ''
            ];
            const csv = [headers.join(','), values.map(v => '"' + String(v).replace(/"/g, '""') + '"').join(',')].join('\n');
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = (m.member_id || 'member') + '.csv';
            a.click();
            URL.revokeObjectURL(url);
        }

        function printMemberDetails() {
            const content = document.getElementById('memberDetailsPrint');
            const w = window.open('', '_blank');
            const logoUrl = `{{ asset('assets/images/waumini_link_logo.png') }}`;
            const printedAt = new Date().toLocaleString();
            const printedBy = `{{ Auth::user()->name ?? 'User' }}`;
            const yearNow = new Date().getFullYear();
            const m = window.currentDetailsMember || null;
            const payload = (function (mm) {
                if (!mm) return ''; return [
                    'Full Name: ' + (mm.full_name || '-'),
                    'Member ID: ' + (mm.member_id || '-'),
                    'Phone: ' + (mm.phone_number || '-'),
                    'Email: ' + (mm.email || '-'),
                    'Gender: ' + (mm.gender ? mm.gender.charAt(0).toUpperCase() + mm.gender.slice(1) : '-'),
                    'Date of Birth: ' + formatDateDisplay(mm.date_of_birth),
                    'NIDA Number: ' + (mm.nida_number || '-'),
                    'Region: ' + (mm.region || '-'),
                    'District: ' + (mm.district || '-'),
                    'Ward: ' + (mm.ward || '-'),
                    'Street: ' + (mm.street || '-'),
                    'Address: ' + (mm.address || '-'),
                    'Living with family: ' + (mm.living_with_family || '-'),
                    'Family relationship: ' + (mm.family_relationship || '-'),
                    'Tribe: ' + ((mm.tribe || '-') + (mm.other_tribe ? (' (' + mm.other_tribe + ')') : ''))
                ].join('\n');
            })(m);

            // Prebuild section HTML using current window's data
            function row(label, value) { return '<tr><td>' + label + '</td><td><strong>' + (value ? String(value) : '—') + '</strong></td></tr>'; }
            let sectionsHtml = '';
            if (m) {
                sectionsHtml += '<div class="section-title">Personal</div>' +
                    '<table class="table"><tbody>' +
                    row('Full Name', m.full_name) +
                    row('Member ID', m.member_id) +
                    row('Phone', m.phone_number) +
                    row('Email', m.email) +
                    row('Gender', m.gender ? (m.gender.charAt(0).toUpperCase() + m.gender.slice(1)) : '') +
                    row('Date of Birth', formatDateDisplay(m.date_of_birth)) +
                    row('NIDA Number', m.nida_number) +
                    '</tbody></table>';

                sectionsHtml += '<div class="section-title">Location</div>' +
                    '<table class="table"><tbody>' +
                    row('Region', m.region) +
                    row('District', m.district) +
                    row('Ward', m.ward) +
                    row('Street', m.street) +
                    row('Address', m.address) +
                    '</tbody></table>';

                sectionsHtml += '<div class="section-title">Family</div>' +
                    '<table class="table"><tbody>' +
                    row('Living with family', m.living_with_family) +
                    row('Family relationship', m.family_relationship) +
                    row('Tribe', (m.tribe || '') + (m.other_tribe ? (' (' + m.other_tribe + ')') : '')) +
                    '</tbody></table>';

                // Add spouse details if present
                if (m.spouse_full_name || m.spouse_email || m.spouse_phone_number || m.spouse_profession || m.spouse_education_level || m.spouse_nida_number || m.spouse_date_of_birth || m.spouse_tribe) {
                    const spouseTitle = (m.member_type === 'father' ? 'Wife' : (m.member_type === 'mother' ? 'Husband' : 'Spouse'));
                    sectionsHtml += '<div class="section-title">' + spouseTitle + '</div>' +
                        '<table class="table"><tbody>' +
                        row('Marital Status', m.marital_status ? (m.marital_status.charAt(0).toUpperCase() + m.marital_status.slice(1)) : '—') +
                        row(spouseTitle + ' Name', m.spouse_full_name) +
                        row(spouseTitle + ' Church Member', m.spouse_church_member ? (m.spouse_church_member === 'yes' ? 'Yes' : 'No') : '—') +
                        row(spouseTitle + ' DOB', formatDateDisplay(m.spouse_date_of_birth)) +
                        row(spouseTitle + ' Education', m.spouse_education_level) +
                        row(spouseTitle + ' Profession', m.spouse_profession) +
                        row(spouseTitle + ' NIDA', m.spouse_nida_number) +
                        row(spouseTitle + ' Email', m.spouse_email) +
                        row(spouseTitle + ' Phone', m.spouse_phone_number) +
                        row(spouseTitle + ' Tribe', (m.spouse_tribe || '') + (m.spouse_other_tribe ? (' (' + m.spouse_other_tribe + ')') : '')) +
                        '</tbody></table>';
                }
            }

            w.document.write('<html><head><title>Member Details</title>');
            w.document.write('<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">');
            w.document.write('<style>\n@page { margin: 15mm; }\nbody{ -webkit-print-color-adjust: exact; print-color-adjust: exact; }\n.print-shell{ max-width: 980px; margin: 0 auto; }\n.header{ position:relative; padding:16px 18px; border-radius:10px; margin-bottom:18px; background: linear-gradient(135deg, #f4f6ff 0%, #ffffff 100%); border:1px solid #e9ecef; }\n.header-top{ display:flex; align-items:center; justify-content:space-between; }\n.brand{ display:flex; align-items:center; gap:12px; }\n.brand h2{ margin:0; color:#940000; }\n.badges{ display:none; }\n.qr-wrap{ text-align:right; }\n.qr{ width:120px; height:120px; border:3px solid #7a0000; border-radius:8px; padding:4px; background:#fff }\n.section-title{ font-size:12px; letter-spacing:1px; color:#6c757d; text-transform:uppercase; margin:18px 0 6px; }\n.table{ width:100%; border-collapse:separate; border-spacing:0; }\n.table th,.table td{ padding:10px 12px; vertical-align:top; }\n.table tbody tr:nth-child(odd){ background:#fbfbfe; }\n.table tbody tr td:first-child{ width:220px; color:#6c757d; border-left:4px solid #7a0000; }\n.footer{ margin-top:24px; padding-top:12px; border-top:1px dashed #ced4da; font-size:12px; color:#6c757d; text-align:center; }\n.footer a{ color:#7a0000; text-decoration:none; }\n.footer a:hover{ text-decoration:underline; }\n</style>');
            w.document.write('</head><body>');
            w.document.write('<div class="print-shell">');
            // Header
            w.document.write(`<div class="header">
                                                         <div class="header-top">
                                                             <div class="brand">
                                                                 <img src="${logoUrl}" style="height:48px"/>
                                                                 <div>
                                                                     <h2 class="mb-0">Member Details</h2>
                                                                 </div>
                                                             </div>
                                                             <div class="qr-wrap"><img id="printQrImg" class="qr" src="" alt="QR"/></div>
                                                         </div>
                                                     </div>`);

            // Sections (prebuilt)
            w.document.write(sectionsHtml);

            // Footer
            w.document.write(`<div class="footer">
                                                        Printed on ${printedAt} by ${printedBy} • © ${yearNow} Waumini Link • Powered by <a href="https://emca.tech/#" target="_blank" rel="noopener" style="color: #940000 !important;">EmCa Technologies</a>
                                                    </div>`);

            w.document.write('</div>');
            // Ensure QR loads before printing
            const qrUrlPromise = getQrDataUrl(payload, 120);
            w.document.write(`<script>\n(function(){\nvar done = false;\nfunction go(){ if(done) return; done = true; try{ window.print(); }catch(e){} setTimeout(function(){ window.close(); }, 200); }\nwindow.addEventListener('load', function(){ setTimeout(go, 400); });\n})();\n<\/script>`);
            w.document.write('</body></html>');
            w.document.close();
            w.focus();
            // After doc open, set QR and wait for load
            setTimeout(function () {
                qrUrlPromise.then(function (url) {
                    const fallback = 'https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=' + encodeURIComponent(payload || 'Member');
                    try {
                        const img = w.document.getElementById('printQrImg');
                        if (img) {
                            img.onload = function () { setTimeout(function () { try { w.print(); } catch (e) { } setTimeout(function () { try { w.close(); } catch (e) { } }, 200); }, 150); };
                            img.onerror = function () { setTimeout(function () { try { w.print(); } catch (e) { } setTimeout(function () { try { w.close(); } catch (e) { } }, 200); }, 200); };
                            img.src = url || fallback;
                        } else {
                            setTimeout(function () { try { w.print(); } catch (e) { } setTimeout(function () { try { w.close(); } catch (e) { } }, 200); }, 300);
                        }
                    } catch (e) { setTimeout(function () { try { w.print(); } catch (e) { } setTimeout(function () { try { w.close(); } catch (e) { } }, 200); }, 300); }
                });
            }, 50);
        }

        function downloadMemberPDF() {
            const m = window.currentDetailsMember || null;
            if (!m) return Swal.fire({ icon: 'error', title: 'Open details first' });
            // Build a hidden container to render into PDF
            const container = document.createElement('div');
            container.style.position = 'fixed';
            container.style.left = '-9999px';
            container.style.top = '0';
            container.style.width = '800px';
            container.innerHTML = '';
            const logoUrl = `{{ asset('assets/images/waumini_link_logo.png') }}`;
            const payload = [
                'Full Name: ' + (m.full_name || '-'),
                'Member ID: ' + (m.member_id || '-'),
                'Phone: ' + (m.phone_number || '-'),
                'Email: ' + (m.email || '-'),
                'Gender: ' + (m.gender ? m.gender.charAt(0).toUpperCase() + m.gender.slice(1) : '-'),
                'Date of Birth: ' + formatDateDisplay(m.date_of_birth),
                'NIDA Number: ' + (m.nida_number || '-'),
                'Region: ' + (m.region || '-'),
                'District: ' + (m.district || '-'),
                'Ward: ' + (m.ward || '-'),
                'Street: ' + (m.street || '-'),
                'Address: ' + (m.address || '-'),
                'Living with family: ' + (m.living_with_family || '-'),
                'Family relationship: ' + (m.family_relationship || '-'),
                'Tribe: ' + ((m.tribe || '-') + (m.other_tribe ? (' (' + m.other_tribe + ')') : ''))
            ].join('\n');
            // Generate data URL for QR to avoid CORS issues in PDF
            getQrDataUrl(payload, 120).then(function (qrDataUrl) {
                const qrImgSrc = qrDataUrl || ('https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=' + encodeURIComponent(payload));
                function row(label, value) { return '<tr><td style="width:220px;color:#6c757d;border-left:4px solid #7a0000; padding:8px 10px">' + label + '</td><td style="padding:8px 10px"><strong>' + (value ? String(value) : '—') + '</strong></td></tr>'; }
                let html = '';
                html += '<div style="border:1px solid #e9ecef;border-radius:10px;padding:14px 16px;margin-bottom:16px;background:linear-gradient(135deg,#f4f6ff 0%,#ffffff 100%)">' +
                    '<div style="display:flex;align-items:center;justify-content:space-between">' +
                    '<div style="display:flex;align-items:center;gap:12px">' +
                    '<img src="' + logoUrl + '" style="height:44px"/><div><h3 style="margin:0;color:#940000">Member Details</h3>' +
                    '</div></div></div>' +
                    '<div><img src="' + qrImgSrc + '" style="width:120px;height:120px;border:3px solid #7a0000;border-radius:8px;padding:4px;background:#fff"/></div>' +
                    '</div></div>';
                html += '<div style="font-size:12px;letter-spacing:1px;color:#6c757d;text-transform:uppercase;margin:14px 0 6px">Personal</div>';
                html += '<table style="width:100%;border-collapse:separate;border-spacing:0"><tbody>' +
                    row('Full Name', m.full_name) + row('Member ID', m.member_id) + row('Phone', m.phone_number) + row('Email', m.email) + row('Gender', m.gender ? (m.gender.charAt(0).toUpperCase() + m.gender.slice(1)) : '') + row('Date of Birth', formatDateDisplay(m.date_of_birth)) + row('NIDA Number', m.nida_number) +
                    '</tbody></table>';
                html += '<div style="font-size:12px;letter-spacing:1px;color:#6c757d;text-transform:uppercase;margin:14px 0 6px">Location</div>';
                html += '<table style="width:100%;border-collapse:separate;border-spacing:0"><tbody>' +
                    row('Region', m.region) + row('District', m.district) + row('Ward', m.ward) + row('Street', m.street) + row('Address', m.address) +
                    '</tbody></table>';
                html += '<div style="font-size:12px;letter-spacing:1px;color:#6c757d;text-transform:uppercase;margin:14px 0 6px">Family</div>';
                html += '<table style="width:100%;border-collapse:separate;border-spacing:0"><tbody>' +
                    row('Living with family', m.living_with_family) + row('Family relationship', m.family_relationship) + row('Tribe', (m.tribe || '') + (m.other_tribe ? (' (' + m.other_tribe + ')') : '')) +
                    '</tbody></table>';
                html += '<div style="margin-top:18px;padding-top:10px;border-top:1px dashed #ced4da;font-size:12px;color:#6c757d;text-align:center">Powered by <a href="https://emca.tech/#" target="_blank" style="color:#940000;text-decoration:none">EmCa Technologies</a></div>';
                container.innerHTML = html;
                document.body.appendChild(container);
                // Preload images before generating PDF
                const imgs = Array.from(container.querySelectorAll('img'));
                Promise.all(imgs.map(img => new Promise(res => { if (img.complete) return res(); img.onload = () => res(); img.onerror = () => res(); }))).then(() => {
                    // Load html2pdf and generate
                    function generate() {
                        window.html2pdf().set({ margin: 10, filename: (m.member_id || 'member') + '.pdf', image: { type: 'jpeg', quality: 0.98 }, html2canvas: { scale: 2, useCORS: true, allowTaint: true }, jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' } }).from(container).save().then(() => { document.body.removeChild(container); }).catch(() => { document.body.removeChild(container); Swal.fire({ icon: 'error', title: 'PDF failed' }); });
                    }
                    if (!window.html2pdf) {
                        const s = document.createElement('script');
                        s.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js';
                        s.onload = generate;
                        s.onerror = () => { document.body.removeChild(container); Swal.fire({ icon: 'error', title: 'Failed to load PDF lib' }); };
                        document.head.appendChild(s);
                    } else {
                        generate();
                    }
                });
            });
        }

        // Cascading selects for Region -> District -> Ward and Tribe
        let tzLocations = null;
        // Make tribeList globally accessible
        window.tribeList = ['Chaga', 'Sukuma', 'Haya', 'Nyakyusa', 'Makonde', 'Hehe', 'Other'];
        let tribeList = window.tribeList; // Keep local reference for backward compatibility

        // Make functions globally accessible
        window.ensureLocationsLoaded = function () {
            if (tzLocations) return Promise.resolve(tzLocations);
            return fetch(`{{ asset('data/tanzania-locations.json') }}`)
                .then(r => r.json())
                .then(json => { tzLocations = json; return tzLocations; });
        };

        window.populateSelect = function (selectEl, items, placeholder = 'Select') {
            if (!selectEl) return;
            selectEl.innerHTML = '';
            const opt = document.createElement('option');
            opt.value = '';
            opt.textContent = placeholder;
            selectEl.appendChild(opt);
            (items || []).forEach(v => {
                const o = document.createElement('option');
                o.value = v;
                o.textContent = v;
                selectEl.appendChild(o);
            });
        };

        // Keep local references for backward compatibility
        function ensureLocationsLoaded() {
            return window.ensureLocationsLoaded();
        }
        function populateSelect(selectEl, items, placeholder = 'Select') {
            return window.populateSelect(selectEl, items, placeholder);
        }
        function setupCascadingForEdit(prefill = {}) {
            ensureLocationsLoaded().then(data => {
                const regions = Object.keys(data || {});
                const regionEl = document.getElementById('edit_region');
                const districtEl = document.getElementById('edit_district');
                const wardEl = document.getElementById('edit_ward');
                populateSelect(regionEl, regions, 'Select region');
                if (prefill.region) regionEl.value = prefill.region;
                function updateDistricts() {
                    const r = regionEl.value;
                    const districts = r && data[r] ? Object.keys(data[r]) : [];
                    populateSelect(districtEl, districts, 'Select district');
                    updateWards();
                    if (prefill.district) districtEl.value = prefill.district;
                }
                function updateWards() {
                    const r = regionEl.value;
                    const d = districtEl.value;
                    const wards = r && d && data[r] && data[r][d] ? data[r][d] : [];
                    populateSelect(wardEl, wards, 'Select ward');
                    if (prefill.ward) wardEl.value = prefill.ward;
                }
                regionEl.onchange = updateDistricts;
                districtEl.onchange = updateWards;
                updateDistricts();
            });
            // Tribe
            const tribeEl = document.getElementById('edit_tribe');
            populateSelect(tribeEl, tribeList, 'Select tribe');
            if (prefill.tribe) tribeEl.value = tribeList.includes(prefill.tribe) ? prefill.tribe : 'Other';
            const otherGroup = document.getElementById('edit_other_tribe_group');
            const otherInput = document.getElementById('edit_other_tribe');
            function toggleOther() {
                const show = tribeEl.value === 'Other';
                otherGroup.style.display = show ? '' : 'none';
                if (!show) otherInput.value = '';
            }
            tribeEl.onchange = toggleOther;
            toggleOther();
            if (prefill.other_tribe) { otherGroup.style.display = ''; otherInput.value = prefill.other_tribe; }
        }

        // QR helper: load once and render
        let qrLibLoaded = false;

        // Preload QR lib early and accessibility: focus first actionable element when modals open
        ensureQrLib();
        document.getElementById('memberDetailsModal').addEventListener('shown.bs.modal', function () {
            const first = document.getElementById('btnHeaderEditPersonal') || document.getElementById('btnPrintDetails');
            first && first.focus();
        });

        // Attach refresh handler when modal closes - ensure it's attached after DOM loads
        function attachModalRefreshHandler() {
            const modal = document.getElementById('memberDetailsModal');
            if (modal) {
                // Remove any existing listeners by cloning the element
                const newModal = modal.cloneNode(true);
                modal.parentNode.replaceChild(newModal, modal);

                // Attach the event listener
                document.getElementById('memberDetailsModal').addEventListener('hidden.bs.modal', function () {
                    console.log('Member details modal closed - refreshing page...');
                    // Hide attendance history button when modal is closed
                    const attendanceBtn = document.getElementById('btnAttendanceHistory');
                    if (attendanceBtn) {
                        attendanceBtn.style.display = 'none';
                        attendanceBtn.removeAttribute('data-member-id');
                    }

                    const idCardBtn = document.getElementById('btnIdCard');
                    if (idCardBtn) {
                        idCardBtn.style.display = 'none';
                        idCardBtn.removeAttribute('data-member-id');
                    }

                    // Refresh the page when modal is closed
                    window.location.reload();
                });
                console.log('Modal refresh handler attached');
            } else {
                console.warn('memberDetailsModal not found, retrying...');
                setTimeout(attachModalRefreshHandler, 100);
            }
        }

        // Try to attach immediately
        attachModalRefreshHandler();

        // Also attach after DOM loads as backup
        document.addEventListener('DOMContentLoaded', function () {
            attachModalRefreshHandler();
        });

        // Set footer year
        document.getElementById('year').textContent = new Date().getFullYear();

        // Attendance history function
        function viewAttendanceHistory() {
            const attendanceBtn = document.getElementById('btnAttendanceHistory');
            const memberId = attendanceBtn.getAttribute('data-member-id');
            if (memberId) {
                window.open(`{{ url('/attendance/member') }}/${memberId}/history`, '_blank');
            }
        }

        function viewIdCard() {
            const idCardBtn = document.getElementById('btnIdCard');
            const memberId = idCardBtn.getAttribute('data-member-id');
            if (memberId) {
                window.open(`{{ url('/members') }}/${memberId}/identity-card`, '_blank');
            }
        }
        function ensureQrLib() {
            return new Promise((resolve) => {
                if (qrLibLoaded || window.QRCode) { qrLibLoaded = true; return resolve(); }
                const s = document.createElement('script');
                s.src = 'https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js';
                s.onload = () => { qrLibLoaded = true; resolve(); };
                document.head.appendChild(s);
            });
        }
        function renderQrToCanvas(canvasId, text, size = 96) {
            ensureQrLib().then(() => {
                setTimeout(() => {
                    const c = document.getElementById(canvasId);
                    if (!c || !window.QRCode) return;
                    QRCode.toCanvas(c, text, { width: size, margin: 1 }, function (err) {
                        if (err) {
                            console.error(err);
                            const holder = c.parentElement;
                            if (holder) holder.innerHTML = '<span class="badge bg-warning text-dark">QR unavailable</span>';
                        }
                    });
                }, 50);
            });
        }

        // Build a QR data URL for embedding (avoids CORS issues when printing/PDF)
        function getQrDataUrl(text, size = 120) {
            return new Promise((resolve) => {
                ensureQrLib().then(() => {
                    if (window.QRCode && QRCode.toDataURL) {
                        QRCode.toDataURL(text, { width: size, margin: 1 }, function (err, url) {
                            if (err) { console.error(err); resolve(''); }
                            else { resolve(url || ''); }
                        });
                    } else {
                        resolve('');
                    }
                });
            });
        }

        // resetPassword function is already defined at the top of the scripts section (line ~1508)
        // restoreMember is now defined at the top of the scripts section (line ~1899)
        // This duplicate definition has been removed to avoid conflicts

        // Assign other functions to window
        // NOTE: `window.openEdit` is already defined in the <head> section - DO NOT overwrite it here!
        // NOTE: `window.confirmDelete` is now defined directly above - no need to reassign
        // Only assign functions that are actually defined in this scope
        // confirmDelete is already window.confirmDelete, so skip it
        if (typeof resetPassword === 'function') {
            window.resetPassword = resetPassword;
        }
        // restoreMember is already window.restoreMember, so skip it
        console.log('✓ Member action functions assigned to window');
        console.log('✓ window.openEdit status:', typeof window.openEdit === 'function' ? 'AVAILABLE' : 'MISSING');

        // Final check after page loads to ensure functions are accessible
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                const functions = ['viewDetails', 'openEdit', 'confirmDelete', 'resetPassword', 'restoreMember', 'switchView'];
                functions.forEach(function (funcName) {
                    if (typeof window[funcName] === 'undefined') {
                        console.error('Function ' + funcName + ' is not defined!');
                    } else {
                        console.log('Function ' + funcName + ' is available');
                    }
                });
            }, 100);
        });
    </script>

    <script>
        // Initialize child form handlers when DOM is ready
        document.addEventListener('DOMContentLoaded', function () {
            // Calculate age from date of birth
            const dobInput = document.getElementById('child_date_of_birth');
            if (dobInput) {
                dobInput.addEventListener('change', function () {
                    const dob = new Date(this.value);
                    if (!isNaN(dob.getTime())) {
                        const today = new Date();
                        let age = today.getFullYear() - dob.getFullYear();
                        const monthDiff = today.getMonth() - dob.getMonth();
                        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                            age--;
                        }
                        const ageInput = document.getElementById('child_age');
                        if (ageInput) {
                            ageInput.value = age + ' years';
                        }
                    }
                });
            }

            // Toggle between member and non-member parent fields
            document.querySelectorAll('input[name="parent_type"]').forEach(radio => {
                radio.addEventListener('change', function () {
                    const memberFields = document.getElementById('memberParentFields');
                    const nonMemberFields = document.getElementById('nonMemberParentFields');
                    const memberSelect = document.getElementById('child_member_id');
                    const parentNameInput = document.getElementById('child_parent_name');

                    if (this.value === 'member') {
                        if (memberFields) memberFields.style.display = 'block';
                        if (nonMemberFields) nonMemberFields.style.display = 'none';
                        if (memberSelect) memberSelect.required = true;
                        if (parentNameInput) parentNameInput.required = false;
                    } else {
                        if (memberFields) memberFields.style.display = 'none';
                        if (nonMemberFields) nonMemberFields.style.display = 'block';
                        if (memberSelect) memberSelect.required = false;
                        if (parentNameInput) parentNameInput.required = true;
                    }
                });
            });
        });

        // Save child function
        function saveChild() {
            const form = document.getElementById('addChildForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const parentType = document.querySelector('input[name="parent_type"]:checked').value;
            const childData = {
                full_name: document.getElementById('child_full_name').value,
                gender: document.getElementById('child_gender').value,
                date_of_birth: document.getElementById('child_date_of_birth').value,
            };

            if (parentType === 'member') {
                childData.member_id = document.getElementById('child_member_id').value;
                if (!childData.member_id) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Please select a parent member' });
                    return;
                }
            } else {
                childData.parent_name = document.getElementById('child_parent_name').value;
                childData.parent_phone = document.getElementById('child_parent_phone').value;
                childData.parent_relationship = document.getElementById('child_parent_relationship').value;
                if (!childData.parent_name) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Please enter parent/guardian name' });
                    return;
                }
            }

            fetch('{{ route("children.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(childData)
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({ icon: 'success', title: 'Success', text: data.message, timer: 1500 });
                        document.getElementById('addChildForm').reset();
                        document.getElementById('child_age').value = '';
                        const modal = bootstrap.Modal.getInstance(document.getElementById('addChildModal'));
                        if (modal) modal.hide();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Failed to save child' });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const errorMessage = error.message || (error.errors ? JSON.stringify(error.errors) : 'An error occurred while saving the child');
                    Swal.fire({ icon: 'error', title: 'Error', text: errorMessage });
                });
        }
    </script>

    <script>
        // Load notifications function
        function loadNotifications() {
            fetch('{{ route("notifications.data") }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.success) {
                        // Store data globally for event details
                        window.currentNotificationData = data;

                        // Update counts - safely handle undefined counts
                        const eventsCountEl = document.getElementById('eventsCount');
                        const celebrationsCountEl = document.getElementById('celebrationsCount');
                        const servicesCountEl = document.getElementById('servicesCount');

                        const counts = data.counts || {};
                        if (eventsCountEl) eventsCountEl.textContent = counts.events || 0;
                        if (celebrationsCountEl) celebrationsCountEl.textContent = counts.celebrations || 0;
                        if (servicesCountEl) servicesCountEl.textContent = counts.services || 0;

                        // Update total notification count - safely handle undefined counts
                        const totalCount = (data.counts && data.counts.total) || 0;
                        const badge = document.getElementById('notificationBadge');
                        if (badge) {
                            badge.textContent = totalCount;
                            badge.style.display = totalCount > 0 ? 'inline' : 'none';
                        }

                        // Update lists
                        const eventsList = document.getElementById('eventsList');
                        if (eventsList && data.events) {
                            eventsList.innerHTML = generateEventList(data.events);
                        }

                        const celebrationsList = document.getElementById('celebrationsList');
                        if (celebrationsList && data.celebrations) {
                            celebrationsList.innerHTML = generateCelebrationList(data.celebrations);
                        }

                        const servicesList = document.getElementById('servicesList');
                        if (servicesList && data.services) {
                            servicesList.innerHTML = generateServiceList(data.services);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    const badge = document.getElementById('notificationBadge');
                    if (badge) {
                        badge.textContent = '0';
                        badge.style.display = 'none';
                    }
                });
        }

        // Generate HTML for events list
        function generateEventList(events) {
            if (!events || events.length === 0) {
                return '<div class="empty-notification-state"><i class="fas fa-calendar-times"></i><span>No upcoming events</span></div>';
            }
            return events.map((event, index) => {
                const eventDate = new Date(event.date).toLocaleDateString('en-US', {
                    weekday: 'short',
                    month: 'short',
                    day: 'numeric'
                });
                const timeText = event.hours_remaining !== null ?
                    `${event.hours_remaining}h left` :
                    `${event.days_remaining}d left`;
                const formatTime = (timeStr) => {
                    if (!timeStr || timeStr === 'TBD') return 'TBD';
                    try {
                        if (timeStr.includes('T')) {
                            const time = new Date(timeStr);
                            return time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        }
                        if (timeStr.includes(':')) {
                            const [hours, minutes] = timeStr.split(':');
                            const time = new Date();
                            time.setHours(parseInt(hours), parseInt(minutes), 0);
                            return time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        }
                        return timeStr;
                    } catch (e) {
                        return 'TBD';
                    }
                };
                return `
                                                            <div class="notification-item" style="animation-delay: ${index * 0.1}s;" onclick="showEventDetails(${event.id}, 'event')">
                                                                <div class="notification-item-content">
                                                                    <div class="notification-icon bg-primary"><i class="fas fa-calendar-alt"></i></div>
                                                                    <div class="notification-details">
                                                                        <div class="notification-title">${event.title}</div>
                                                                        <div class="notification-meta">
                                                                            <span class="meta-item"><i class="fas fa-calendar"></i>${eventDate}</span>
                                                                            <span class="meta-item"><i class="fas fa-clock"></i>${formatTime(event.time)}</span>
                                                                        </div>
                                                                        <div class="notification-info">
                                                                            <span class="info-item"><i class="fas fa-map-marker-alt"></i>${event.venue}</span>
                                                                            ${event.speaker ? `<span class="info-item"><i class="fas fa-user"></i>${event.speaker}</span>` : ''}
                                                                        </div>
                                                                        <div class="notification-badge"><span class="time-badge bg-primary">${timeText}</span></div>
                                                                    </div>
                                                                    <div class="notification-arrow"><i class="fas fa-chevron-right"></i></div>
                                                                </div>
                                                            </div>
                                                        `;
            }).join('');
        }

        // Generate HTML for celebrations list
        function generateCelebrationList(celebrations) {
            if (!celebrations || celebrations.length === 0) {
                return '<div class="empty-notification-state"><i class="fas fa-birthday-cake"></i><span>No upcoming celebrations</span></div>';
            }
            return celebrations.map((celebration, index) => {
                const celebrationDate = new Date(celebration.date).toLocaleDateString('en-US', {
                    weekday: 'short',
                    month: 'short',
                    day: 'numeric'
                });
                const timeText = celebration.hours_remaining !== null ?
                    `${celebration.hours_remaining}h left` :
                    `${celebration.days_remaining}d left`;
                const formatTime = (timeStr) => {
                    if (!timeStr || timeStr === 'TBD') return 'TBD';
                    try {
                        if (timeStr.includes('T')) {
                            const time = new Date(timeStr);
                            return time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        }
                        if (timeStr.includes(':')) {
                            const [hours, minutes] = timeStr.split(':');
                            const time = new Date();
                            time.setHours(parseInt(hours), parseInt(minutes), 0);
                            return time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        }
                        return timeStr;
                    } catch (e) {
                        return 'TBD';
                    }
                };
                return `
                                                            <div class="notification-item" style="animation-delay: ${index * 0.1}s;" onclick="showEventDetails(${celebration.id}, 'celebration')">
                                                                <div class="notification-item-content">
                                                                    <div class="notification-icon bg-warning"><i class="fas fa-birthday-cake"></i></div>
                                                                    <div class="notification-details">
                                                                        <div class="notification-title">${celebration.title}</div>
                                                                        <div class="notification-meta">
                                                                            <span class="meta-item"><i class="fas fa-user"></i>${celebration.celebrant}</span>
                                                                            <span class="meta-item"><i class="fas fa-calendar"></i>${celebrationDate}</span>
                                                                        </div>
                                                                        <div class="notification-info">
                                                                            <span class="info-item"><i class="fas fa-clock"></i>${formatTime(celebration.time)}</span>
                                                                            <span class="info-item"><i class="fas fa-map-marker-alt"></i>${celebration.venue}</span>
                                                                        </div>
                                                                        <div class="notification-badge"><span class="time-badge bg-warning">${timeText}</span></div>
                                                                    </div>
                                                                    <div class="notification-arrow"><i class="fas fa-chevron-right"></i></div>
                                                                </div>
                                                            </div>
                                                        `;
            }).join('');
        }

        // Generate HTML for services list
        function generateServiceList(services) {
            if (!services || services.length === 0) {
                return '<div class="empty-notification-state"><i class="fas fa-church"></i><span>No upcoming services</span></div>';
            }
            return services.map((service, index) => {
                const serviceDate = new Date(service.date).toLocaleDateString('en-US', {
                    weekday: 'short',
                    month: 'short',
                    day: 'numeric'
                });
                const timeText = service.hours_remaining !== null ?
                    `${service.hours_remaining}h left` :
                    `${service.days_remaining}d left`;
                const formatTime = (timeStr) => {
                    if (!timeStr || timeStr === 'TBD') return 'TBD';
                    try {
                        if (timeStr.includes('T')) {
                            const time = new Date(timeStr);
                            return time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        }
                        if (timeStr.includes(':')) {
                            const [hours, minutes] = timeStr.split(':');
                            const time = new Date();
                            time.setHours(parseInt(hours), parseInt(minutes), 0);
                            return time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        }
                        return timeStr;
                    } catch (e) {
                        return 'TBD';
                    }
                };
                return `
                                                            <div class="notification-item" style="animation-delay: ${index * 0.1}s;" onclick="showEventDetails(${service.id}, 'service')">
                                                                <div class="notification-item-content">
                                                                    <div class="notification-icon bg-success"><i class="fas fa-church"></i></div>
                                                                    <div class="notification-details">
                                                                        <div class="notification-title">${service.title}</div>
                                                                        <div class="notification-meta">
                                                                            <span class="meta-item"><i class="fas fa-calendar"></i>${serviceDate}</span>
                                                                            <span class="meta-item"><i class="fas fa-clock"></i>${formatTime(service.time)}</span>
                                                                        </div>
                                                                        <div class="notification-info">
                                                                            <span class="info-item"><i class="fas fa-map-marker-alt"></i>${service.venue}</span>
                                                                            ${service.speaker ? `<span class="info-item"><i class="fas fa-user"></i>${service.speaker}</span>` : ''}
                                                                        </div>
                                                                        ${service.theme ? `<div class="notification-theme"><i class="fas fa-quote-left"></i>${service.theme}</div>` : ''}
                                                                        <div class="notification-badge"><span class="time-badge bg-success">${timeText}</span></div>
                                                                    </div>
                                                                    <div class="notification-arrow"><i class="fas fa-chevron-right"></i></div>
                                                                </div>
                                                            </div>
                                                        `;
            }).join('');
        }

        // Function to show event details in a modal
        function showEventDetails(id, type) {
            let modal = document.getElementById('eventDetailsModal');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'eventDetailsModal';
                modal.className = 'modal fade';
                modal.innerHTML = `
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-light">
                                                                        <h5 class="modal-title" id="eventDetailsTitle">Event Details</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                    </div>
                                                                    <div class="modal-body p-4" id="eventDetailsBody">
                                                                        <div class="text-center">
                                                                            <div class="spinner-border" role="status">
                                                                                <span class="visually-hidden">Loading...</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer bg-light">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                            <i class="fas fa-times me-2"></i>Close
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        `;
                document.body.appendChild(modal);
            }
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
            loadEventDetails(id, type);
        }

        // Function to load event details
        function loadEventDetails(id, type) {
            const modalBody = document.getElementById('eventDetailsBody');
            const modalTitle = document.getElementById('eventDetailsTitle');
            const titles = {
                'event': 'Event Details',
                'celebration': 'Celebration Details',
                'service': 'Service Details'
            };
            modalTitle.textContent = titles[type] || 'Details';
            modalBody.innerHTML = `
                                                        <div class="text-center py-4">
                                                            <div class="spinner-border text-primary" role="status">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                            <p class="mt-2 text-muted">Loading details...</p>
                                                        </div>
                                                    `;
            setTimeout(() => {
                let eventData = null;
                if (window.currentNotificationData) {
                    if (type === 'event') {
                        eventData = window.currentNotificationData.events.find(e => e.id === id);
                    } else if (type === 'celebration') {
                        eventData = window.currentNotificationData.celebrations.find(c => c.id === id);
                    } else if (type === 'service') {
                        eventData = window.currentNotificationData.services.find(s => s.id === id);
                    }
                }
                const formatTime = (timeStr) => {
                    if (!timeStr || timeStr === 'TBD') return 'TBD';
                    try {
                        if (timeStr.includes('T') || /\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}/.test(timeStr)) {
                            const time = new Date(timeStr);
                            return time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        }
                        if (/^\d{2}:\d{2}/.test(timeStr)) {
                            const [hours, minutes] = timeStr.split(':');
                            const d = new Date();
                            d.setHours(parseInt(hours), parseInt(minutes), 0, 0);
                            return d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        }
                        return timeStr;
                    } catch (e) {
                        return 'TBD';
                    }
                };
                if (eventData) {
                    const eventDate = new Date(eventData.date).toLocaleDateString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    let timeDisplay = 'TBD';
                    if (type === 'service') {
                        const start = eventData.start_time || eventData.time;
                        const end = eventData.end_time;
                        if (start && end) {
                            timeDisplay = `${formatTime(start)} - ${formatTime(end)}`;
                        } else if (start) {
                            timeDisplay = formatTime(start);
                        } else if (eventData.time) {
                            timeDisplay = formatTime(eventData.time);
                        }
                    } else {
                        timeDisplay = eventData.time ? formatTime(eventData.time) : 'TBD';
                    }
                    modalBody.innerHTML = `
                                                                <div class="text-center mb-4">
                                                                    <div class="bg-${type === 'event' ? 'primary' : type === 'celebration' ? 'warning' : 'success'} text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px;">
                                                                        <i class="fas fa-${type === 'event' ? 'calendar-alt' : type === 'celebration' ? 'birthday-cake' : 'church'} fa-3x"></i>
                                                                    </div>
                                                                    <h3 class="text-dark mb-2">${eventData.title}</h3>
                                                                    <p class="text-muted">${type.charAt(0).toUpperCase() + type.slice(1)} Information</p>
                                                                </div>
                                                                <div class="row g-3">
                                                                    <div class="col-md-6">
                                                                        <div class="card h-100 border-0 shadow-sm">
                                                                            <div class="card-body text-center">
                                                                                <i class="fas fa-calendar text-primary fa-2x mb-3"></i>
                                                                                <h6 class="card-title">Date</h6>
                                                                                <p class="card-text text-muted">${eventDate}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="card h-100 border-0 shadow-sm">
                                                                            <div class="card-body text-center">
                                                                                <i class="fas fa-clock text-success fa-2x mb-3"></i>
                                                                                <h6 class="card-title">Time</h6>
                                                                                <p class="card-text text-muted">${timeDisplay}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="card h-100 border-0 shadow-sm">
                                                                            <div class="card-body text-center">
                                                                                <i class="fas fa-map-marker-alt text-danger fa-2x mb-3"></i>
                                                                                <h6 class="card-title">Venue</h6>
                                                                                <p class="card-text text-muted">${eventData.venue}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="card h-100 border-0 shadow-sm">
                                                                            <div class="card-body text-center">
                                                                                <i class="fas fa-user text-info fa-2x mb-3"></i>
                                                                                <h6 class="card-title">${type === 'celebration' ? 'Celebrant' : (type === 'service' ? 'Preacher' : 'Speaker')}</h6>
                                                                                <p class="card-text text-muted">${eventData.speaker || eventData.celebrant || 'TBD'}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    ${eventData.theme ? `
                                                                    <div class="col-12">
                                                                        <div class="card border-0 shadow-sm">
                                                                            <div class="card-body text-center">
                                                                                <i class="fas fa-quote-left text-warning fa-2x mb-3"></i>
                                                                                <h6 class="card-title">Theme</h6>
                                                                                <p class="card-text text-muted">${eventData.theme}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    ` : ''}
                                                                    <div class="col-12">
                                                                        <div class="alert alert-${type === 'event' ? 'primary' : type === 'celebration' ? 'warning' : 'success'} border-0">
                                                                            <div class="d-flex align-items-center">
                                                                                <i class="fas fa-info-circle fa-2x me-3"></i>
                                                                                <div>
                                                                                    <h6 class="mb-1">Time Remaining</h6>
                                                                                    <p class="mb-0">
                                                                                        ${eventData.hours_remaining !== null ?
                            `${eventData.hours_remaining} hours left` :
                            `${eventData.days_remaining} days left`}
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            `;
                } else {
                    modalBody.innerHTML = `
                                                                <div class="text-center py-4 text-muted">Details not found.</div>
                                                            `;
                }
            }, 50);
        }

        // Load notifications on page load
        document.addEventListener('DOMContentLoaded', function () {
            loadNotifications();
            // Refresh notifications every 5 minutes
            setInterval(loadNotifications, 300000);

            // Handle mobile dropdown positioning
            const notificationDropdown = document.getElementById('notificationDropdown');
            if (notificationDropdown) {
                const dropdownMenu = notificationDropdown.querySelector('.notification-dropdown');
                if (dropdownMenu) {
                    // Function to apply mobile positioning
                    function applyMobilePositioning() {
                        if (window.innerWidth <= 576) {
                            dropdownMenu.style.setProperty('position', 'fixed', 'important');
                            dropdownMenu.style.setProperty('top', '60px', 'important');
                            dropdownMenu.style.setProperty('left', '0.25rem', 'important');
                            dropdownMenu.style.setProperty('right', '0.25rem', 'important');
                            dropdownMenu.style.setProperty('width', 'calc(100vw - 0.5rem)', 'important');
                            dropdownMenu.style.setProperty('max-width', 'calc(100vw - 0.5rem)', 'important');
                            dropdownMenu.style.setProperty('margin', '0', 'important');
                            dropdownMenu.style.setProperty('transform', 'none', 'important');
                            dropdownMenu.style.setProperty('z-index', '1055', 'important');
                            dropdownMenu.style.setProperty('inset', '60px 0.25rem auto 0.25rem', 'important');
                        } else if (window.innerWidth <= 768) {
                            dropdownMenu.style.setProperty('position', 'fixed', 'important');
                            dropdownMenu.style.setProperty('top', '60px', 'important');
                            dropdownMenu.style.setProperty('left', '0.5rem', 'important');
                            dropdownMenu.style.setProperty('right', '0.5rem', 'important');
                            dropdownMenu.style.setProperty('width', 'calc(100vw - 1rem)', 'important');
                            dropdownMenu.style.setProperty('max-width', 'calc(100vw - 1rem)', 'important');
                            dropdownMenu.style.setProperty('margin', '0', 'important');
                            dropdownMenu.style.setProperty('transform', 'none', 'important');
                            dropdownMenu.style.setProperty('z-index', '1055', 'important');
                            dropdownMenu.style.setProperty('inset', '60px 0.5rem auto 0.5rem', 'important');
                        } else {
                            // Desktop - reset styles
                            dropdownMenu.style.removeProperty('position');
                            dropdownMenu.style.removeProperty('top');
                            dropdownMenu.style.removeProperty('left');
                            dropdownMenu.style.removeProperty('right');
                            dropdownMenu.style.removeProperty('width');
                            dropdownMenu.style.removeProperty('max-width');
                            dropdownMenu.style.removeProperty('margin');
                            dropdownMenu.style.removeProperty('transform');
                            dropdownMenu.style.removeProperty('z-index');
                            dropdownMenu.style.removeProperty('inset');
                        }
                    }

                    // Handle dropdown show event for mobile positioning
                    notificationDropdown.addEventListener('show.bs.dropdown', function () {
                        setTimeout(applyMobilePositioning, 10);
                    });

                    // Also handle after shown to ensure positioning
                    notificationDropdown.addEventListener('shown.bs.dropdown', function () {
                        applyMobilePositioning();
                        // Use MutationObserver to watch for Bootstrap's style changes
                        const observer = new MutationObserver(function (mutations) {
                            mutations.forEach(function (mutation) {
                                if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                                    applyMobilePositioning();
                                }
                            });
                        });
                        observer.observe(dropdownMenu, { attributes: true, attributeFilter: ['style'] });
                        // Disconnect observer after dropdown is hidden
                        notificationDropdown.addEventListener('hide.bs.dropdown', function () {
                            observer.disconnect();
                        }, { once: true });
                    });

                    // Handle window resize
                    window.addEventListener('resize', function () {
                        if (window.innerWidth > 768) {
                            // Reset to desktop styles
                            dropdownMenu.style.position = '';
                            dropdownMenu.style.top = '';
                            dropdownMenu.style.left = '';
                            dropdownMenu.style.right = '';
                            dropdownMenu.style.width = '';
                            dropdownMenu.style.maxWidth = '';
                            dropdownMenu.style.transform = '';
                            dropdownMenu.style.zIndex = '';
                        }
                    });

                    // Handle dropdown hide to reset styles
                    notificationDropdown.addEventListener('hide.bs.dropdown', function () {
                        if (window.innerWidth > 768) {
                            dropdownMenu.style.position = '';
                            dropdownMenu.style.top = '';
                            dropdownMenu.style.left = '';
                            dropdownMenu.style.right = '';
                            dropdownMenu.style.width = '';
                            dropdownMenu.style.maxWidth = '';
                            dropdownMenu.style.transform = '';
                            dropdownMenu.style.zIndex = '';
                        }
                    });
                }
            }

            // Ensure dropdowns close properly - close one when the other opens
            const profileDropdown = document.getElementById('navbarDropdown');
            const notificationDropdownEl = document.getElementById('notificationDropdown');

            if (notificationDropdownEl && profileDropdown) {
                // Close profile dropdown when notification opens
                notificationDropdownEl.addEventListener('show.bs.dropdown', function () {
                    // Use Bootstrap API to close profile dropdown
                    if (typeof bootstrap !== 'undefined') {
                        const profileDropdownInstance = bootstrap.Dropdown.getInstance(profileDropdown);
                        if (profileDropdownInstance) {
                            profileDropdownInstance.hide();
                        }
                    }
                });

                // Close notification dropdown when profile opens
                profileDropdown.addEventListener('show.bs.dropdown', function () {
                    // Use Bootstrap API to close notification dropdown
                    if (typeof bootstrap !== 'undefined') {
                        const notificationDropdownInstance = bootstrap.Dropdown.getInstance(notificationDropdownEl);
                        if (notificationDropdownInstance) {
                            notificationDropdownInstance.hide();
                        }
                    }
                });
            }
        });

        // Update date and time display
        function updateDateTime() {
            const now = new Date();
            const dateElement = document.getElementById('currentDate');
            const timeElement = document.getElementById('currentTime');

            if (dateElement) {
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                dateElement.textContent = now.toLocaleDateString('en-US', options);
            }

            if (timeElement) {
                const hours = now.getHours().toString().padStart(2, '0');
                const minutes = now.getMinutes().toString().padStart(2, '0');
                const seconds = now.getSeconds().toString().padStart(2, '0');
                timeElement.textContent = `${hours}:${minutes}:${seconds}`;
            }
        }

        // Update date and time immediately and then every second
        document.addEventListener('DOMContentLoaded', function () {
            updateDateTime();
            setInterval(updateDateTime, 1000);
        });

        // Toggle sidebar functionality
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const layoutSidenav = document.getElementById('layoutSidenav');

            if (sidebarToggle) {
                sidebarToggle.onclick = null;
                sidebarToggle.removeAttribute('onclick');

                if (!sidebarToggle.hasAttribute('data-layout-toggle-handler')) {
                    sidebarToggle.setAttribute('data-layout-toggle-handler', 'true');

                    sidebarToggle.addEventListener('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation();

                        if (layoutSidenav) {
                            layoutSidenav.classList.toggle('sb-sidenav-toggled');
                        }
                        document.body.classList.toggle('sb-sidenav-toggled');

                        const isToggled = layoutSidenav ? layoutSidenav.classList.contains('sb-sidenav-toggled') : document.body.classList.contains('sb-sidenav-toggled');
                        localStorage.setItem('sb-sidebar-toggle', isToggled ? 'true' : 'false');

                        return false;
                    }, true);

                    const savedState = localStorage.getItem('sb-sidebar-toggle');
                    if (savedState === 'true') {
                        if (layoutSidenav) {
                            layoutSidenav.classList.add('sb-sidenav-toggled');
                        }
                        document.body.classList.add('sb-sidenav-toggled');
                    }
                }
            }
        });

        // Make functions globally available
        window.showEventDetails = showEventDetails;
        window.loadNotifications = loadNotifications;
    </script>
@endsection