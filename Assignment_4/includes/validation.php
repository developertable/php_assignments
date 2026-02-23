<?php
/**
 * Validation Helper Functions
 * Centralized input validation
 */

// Validate email
function validate_email($email) {
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if ($email === false) {
        return ['valid' => false, 'error' => 'Please enter a valid email address.'];
    }
    return ['valid' => true, 'value' => $email];
}

// Validate required field
function validate_required($value, $field_name) {
    if (empty(trim($value))) {
        return ['valid' => false, 'error' => ucfirst($field_name) . ' is required.'];
    }
    return ['valid' => true, 'value' => trim($value)];
}

// Validate integer
function validate_integer($value, $field_name) {
    $int_value = filter_var($value, FILTER_VALIDATE_INT);
    if ($int_value === false) {
        return ['valid' => false, 'error' => ucfirst($field_name) . ' must be a valid number.'];
    }
    return ['valid' => true, 'value' => $int_value];
}

// Validate string length
function validate_length($value, $field_name, $min = 1, $max = 255) {
    $length = strlen($value);
    if ($length < $min || $length > $max) {
        return ['valid' => false, 'error' => ucfirst($field_name) . " must be between $min and $max characters."];
    }
    return ['valid' => true, 'value' => $value];
}

// Collect all validation errors
function collect_errors($validations) {
    $errors = [];
    foreach ($validations as $validation) {
        if (!$validation['valid']) {
            $errors[] = $validation['error'];
        }
    }
    return $errors;
}
?>