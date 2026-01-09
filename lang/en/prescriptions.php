<?php

return [
    // Page titles
    'title' => 'Prescriptions',
    'page_subtitle' => 'Pharmacy',
    'create_title' => 'Create New Prescription',
    'edit_title' => 'Edit Prescription',
    'view_title' => 'Prescription Details',
    'refill_reminders_title' => 'Refill Reminders',

    // Stats
    'total' => 'Total',
    'pending' => 'Pending',
    'dispensed' => 'Dispensed',
    'dispensed_today' => 'Dispensed Today',
    'needs_refill' => 'Needs Refill',
    'overdue' => 'Overdue',
    'due_soon' => 'Due Soon',

    // Status
    'status' => 'Status',
    'status_pending' => 'Pending',
    'status_dispensed' => 'Dispensed',
    'status_partially_dispensed' => 'Partial',
    'status_cancelled' => 'Cancelled',
    'status_expired' => 'Expired',

    // Buttons & Actions
    'add_new' => 'New Prescription',
    'dispense' => 'Dispense',
    'process_refill' => 'Process Refill',
    'save_prescription' => 'Save Prescription',
    'update_prescription' => 'Update Prescription',
    'add_medication' => 'Add Medication',
    'add_first_medication' => 'Add First Medication',
    'refill_reminders' => 'Refill Reminders',
    'call_customer' => 'Call Customer',
    'check_interactions' => 'Check Interactions',
    'remove' => 'Remove',

    // Search & Filters
    'search_placeholder' => 'Search RX number, doctor, customer...',
    'filter_all' => 'All',

    // Form Labels
    'rx_number' => 'RX Number',
    'customer_info' => 'Customer Information',
    'doctor_info' => 'Prescribing Doctor',
    'prescription_details' => 'Prescription Details',
    'details' => 'Details',
    'medications' => 'Medications',
    'items' => 'Items',
    'medicine' => 'Medicine',
    'unit' => 'Unit',
    'instructions' => 'Instructions',
    'notes' => 'Notes',

    // Customer
    'customer' => 'Customer',
    'select_customer' => '-- Select Customer --',
    'allergies' => 'Drug Allergies',
    'no_allergy_info' => 'No allergy information',
    'view_customer_history' => 'View Customer History',

    // Doctor
    'doctor' => 'Doctor',
    'doctor_name' => 'Doctor Name',
    'doctor_license' => 'License Number',
    'hospital_clinic' => 'Hospital/Clinic',
    'doctor_phone' => 'Doctor Phone',

    // Prescription Details
    'date' => 'Date',
    'prescription_date' => 'Prescription Date',
    'expiry_date' => 'Prescription Expiry',
    'expiry_date_hint' => 'Usually valid for 3-6 months',
    'valid_until' => 'Valid Until',
    'days_until_expiry' => ':days days until expiry',
    'expires_today' => 'Expires today',
    'expired_days_ago' => 'Expired :days days ago',
    'diagnosis' => 'Diagnosis',
    'pharmacist' => 'Dispensing Pharmacist',
    'created_by' => 'Created By',
    'dispensed_by' => 'Dispensed By',
    'dispensed_at' => 'Dispensed At',

    // Refill
    'refill' => 'Refill',
    'refill_allowed' => 'Refills Allowed',
    'refill_count' => 'Refill Count',
    'refill_status' => 'Refill Status',
    'next_refill' => 'Next Refill',
    'refill_interval' => 'Refill Interval (days)',
    'due_date' => 'Due Date',

    // Medications
    'no_medications_yet' => 'No medications added yet',
    'total_items' => 'Total Items',
    'estimated_total' => 'Estimated Total',
    'search_medication' => 'Search medication...',
    'quantity' => 'Quantity',
    'dosage' => 'Dosage',
    'frequency' => 'Frequency',
    'duration' => 'Duration',
    'route' => 'Route',

    // Dosage options
    'dosage_half' => 'Half tablet',
    'dosage_one' => '1 tablet',
    'dosage_two' => '2 tablets',
    'dosage_as_needed' => 'As needed',

    // Frequency options
    'frequency_once' => 'Once daily',
    'frequency_twice' => 'Twice daily',
    'frequency_three' => 'Three times daily',
    'frequency_four' => 'Four times daily',
    'frequency_before_bed' => 'Before bedtime',
    'frequency_every_hours' => 'Every :hours hours',
    'frequency_as_needed' => 'As needed',

    // Route options
    'route_oral' => 'Oral',
    'route_topical' => 'Topical',
    'route_injection' => 'Injection',
    'route_inhale' => 'Inhalation',
    'route_drops' => 'Drops',
    'route_suppository' => 'Suppository',

    // Drug Interactions
    'drug_interactions' => 'Drug Interactions',
    'no_interactions' => 'No drug interactions detected',
    'interaction_warning' => 'Drug Interaction Warning',
    'interaction_severity_high' => 'High Severity',
    'interaction_severity_moderate' => 'Moderate Severity',
    'interaction_severity_low' => 'Low Severity',

    // Customer History
    'customer_history' => 'Customer Prescription History',
    'previous_prescriptions' => 'Previous Prescriptions',
    'no_previous_prescriptions' => 'No previous prescription history',

    // Reminders
    'reminder_info' => 'These prescriptions are due or overdue for refill. Contact the customer to remind them.',
    'no_reminders' => 'No Refill Reminders',
    'no_reminders_desc' => 'There are no prescriptions pending refill reminders at this time.',

    // Status Messages
    'no_prescriptions' => 'No prescriptions found',
    'confirm_delete' => 'Are you sure you want to delete this prescription?',

    // Success Messages
    'created_successfully' => 'Prescription created successfully',
    'updated_successfully' => 'Prescription updated successfully',
    'deleted_successfully' => 'Prescription deleted successfully',
    'dispensed_successfully' => 'Prescription dispensed successfully',
    'refill_processed' => 'Refill :count of :total processed successfully',
    'drug_interactions_found' => '⚠️ Found :count drug interaction(s)',

    // Error Messages
    'create_error' => 'Error creating prescription',
    'update_error' => 'Error updating prescription',
    'dispense_error' => 'Error dispensing prescription',
    'refill_error' => 'Error processing refill',
    'cannot_edit_dispensed' => 'Cannot edit dispensed prescription',
    'cannot_delete_dispensed' => 'Cannot delete dispensed prescription',
    'cannot_dispense' => 'Cannot dispense in this status',
    'cannot_refill' => 'Cannot process refill',
    'prescription_expired' => 'Prescription has expired',

    // Validation
    'customer_required' => 'Please select a customer',
    'doctor_required' => 'Doctor name is required',
    'medications_required' => 'At least one medication is required',
];
