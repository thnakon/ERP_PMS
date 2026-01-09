<?php

return [
    // Page titles
    'title' => 'Controlled Drugs',
    'page_subtitle' => 'Pharmacy',

    // Buttons & Actions
    'add_new' => 'Log Dispensing',
    'pending_approvals' => 'Pending Approvals',
    'fda_report' => 'FDA Report',
    'generate_report' => 'Generate Report',
    'print_report' => 'Print Report',
    'view_details' => 'View Details',
    'approve' => 'Approve',
    'reject' => 'Reject',
    'confirm_reject' => 'Confirm Rejection',

    // Stats
    'stat_total' => 'Total Records',
    'stat_pending' => 'Pending Approval',
    'stat_approved_today' => 'Approved Today',
    'stat_dangerous' => 'Dangerous Drugs',
    'stat_specially_controlled' => 'Specially Controlled',

    // Table headers
    'log_number' => 'Log Number',
    'drug' => 'Drug/Medicine',
    'drug_type' => 'Drug Type',
    'recipient' => 'Recipient',
    'id_card' => 'ID Card',
    'quantity' => 'Quantity',
    'transaction_type' => 'Transaction Type',
    'date' => 'Date',
    'status' => 'Status',
    'approved_by' => 'Approved By',
    'created_by' => 'Created By',

    // Status
    'status_pending' => 'Pending',
    'status_approved' => 'Approved',
    'status_rejected' => 'Rejected',
    'status_cancelled' => 'Cancelled',

    // Drug schedules
    'schedule_normal' => 'Regular Drug',
    'schedule_dangerous' => 'Dangerous Drug',
    'schedule_specially_controlled' => 'Specially Controlled',
    'schedule_narcotic' => 'Narcotic',
    'schedule_psychotropic' => 'Psychotropic',

    // Transaction types
    'trans_sale' => 'Sale',
    'trans_dispense' => 'Prescription Dispense',
    'trans_receive' => 'Receive',
    'trans_return' => 'Return',
    'trans_dispose' => 'Dispose',
    'trans_transfer' => 'Transfer',

    // Form labels - Drug info
    'drug_info' => 'Drug Information',
    'select_drug' => 'Select Drug/Medicine',
    'drug_warning_dangerous' => '⚠️ Dangerous Drug - Must be dispensed by pharmacist',
    'drug_warning_specially' => '⚠️ Specially Controlled - Prescription required',
    'drug_warning_narcotic' => '⚠️ Narcotic - Must follow legal requirements',
    'drug_warning_psychotropic' => '⚠️ Psychotropic - Pharmacist approval required',

    // Form labels - Recipient info  
    'recipient_info' => 'Recipient Information',
    'recipient_legal_note' => '(Required by law)',
    'select_from_customers' => 'Select from Customers',
    'or_enter_new' => '-- Or enter new info --',
    'full_name' => 'Full Name',
    'id_card_number' => 'ID Card Number',
    'phone' => 'Phone',
    'age' => 'Age',
    'address' => 'Address',

    // Form labels - Prescription info
    'prescription_info' => 'Prescription Information',
    'if_applicable' => '(If applicable)',
    'prescription_number' => 'Prescription Number',
    'doctor_name' => 'Doctor Name',
    'license_number' => 'License Number',
    'hospital_clinic' => 'Hospital/Clinic',

    // Form labels - Purpose
    'purpose_section' => 'Purpose',
    'purpose' => 'Purpose of Use',
    'indication' => 'Indication',
    'notes' => 'Notes',

    // Legal confirmation
    'legal_confirm' => 'I confirm that the above information is accurate and understand that dispensing controlled drugs must comply with all applicable laws.',
    'submit_log' => 'Submit Controlled Drug Log',

    // Details page
    'drug_details' => 'Drug Details',
    'recipient_details' => 'Recipient Details',
    'prescription_details' => 'Prescription Details',
    'rejection_reason' => 'Rejection Reason',
    'rejection_reason_placeholder' => 'Please provide reason for rejection (minimum 10 characters)',

    // FDA Report
    'fda_report_title' => 'FDA Controlled Drug Report',
    'report_period' => 'Report Period',
    'start_date' => 'Start Date',
    'end_date' => 'End Date',
    'total_transactions' => 'Total Transactions',
    'movement_summary' => 'Drug Movement Summary',
    'detailed_log' => 'Detailed Transaction Log',
    'dispensed_out' => 'Dispensed Out',
    'received_in' => 'Received In',
    'disposed' => 'Disposed',
    'transaction_count' => 'Transactions',
    'legal_certification' => 'Legal Certification',
    'certification_text' => 'I hereby certify that this report is accurate and in compliance with the Drug Act B.E. 2510 and all related regulations.',
    'pharmacist_signature' => 'Pharmacist in Charge Signature',
    'authorized_signature' => 'Authorized Person Signature',
    'signature_date' => 'Date: ___/___/______',

    // Pending page
    'pending_title' => 'Pending Pharmacist Approval',
    'pending_info' => 'These are controlled drug dispensing records that require pharmacist approval before processing.',
    'no_pending' => 'No Pending Items',
    'no_pending_desc' => 'There are no controlled drug records pending approval.',
    'back_to_main' => 'Back to Main Page',

    // Filters
    'search_placeholder' => 'Search log number, name, ID card...',
    'filter_all' => 'All',
    'filter_by_status' => 'Filter by Status',
    'filter_by_drug_type' => 'Filter by Drug Type',

    // Messages
    'logged_and_approved' => 'Controlled drug dispensing logged and approved successfully',
    'logged_pending_approval' => 'Controlled drug dispensing logged, pending pharmacist approval',
    'approved_successfully' => 'Dispensing approved successfully',
    'rejected_successfully' => 'Dispensing rejected successfully',
    'already_processed' => 'This record has already been processed',
    'not_authorized' => 'You are not authorized. Only pharmacists and administrators can approve.',

    // Empty states
    'no_records' => 'No controlled drug records found',
    'no_data_in_period' => 'No data in the selected period',

    // Sort options
    'sort_newest' => 'Newest First',
    'sort_oldest' => 'Oldest First',
];
