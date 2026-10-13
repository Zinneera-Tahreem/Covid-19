<?php
/**
 * STQA Automated Test Execution Engine API
 * Executes all predefined test cases against PHP's PCRE preg_match engine
 * Returns JSON metrics for automated evaluation and grading.
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../db.php';

$testCases = [
    // Full Name Tests
    [
        'id' => 'TC_NAME_01',
        'field' => 'Full Name',
        'technique' => 'ECP (Valid)',
        'regex_constant' => 'REGEX_FULL_NAME',
        'regex_str' => '/^[a-zA-Z\s]{3,50}$/',
        'input' => 'Dr Anthony Fauci',
        'expected' => true,
        'description' => 'Standard alphabetic name with title and space'
    ],
    [
        'id' => 'TC_NAME_02',
        'field' => 'Full Name',
        'technique' => 'BVA (Min Limit 3)',
        'regex_constant' => 'REGEX_FULL_NAME',
        'regex_str' => '/^[a-zA-Z\s]{3,50}$/',
        'input' => 'Amy',
        'expected' => true,
        'description' => 'Exact minimum boundary length of 3 characters'
    ],
    [
        'id' => 'TC_NAME_03',
        'field' => 'Full Name',
        'technique' => 'BVA (Below Min 2)',
        'regex_constant' => 'REGEX_FULL_NAME',
        'regex_str' => '/^[a-zA-Z\s]{3,50}$/',
        'input' => 'Al',
        'expected' => false,
        'description' => 'Boundary violation: 2 characters (less than minimum 3)'
    ],
    [
        'id' => 'TC_NAME_04',
        'field' => 'Full Name',
        'technique' => 'ECP (Invalid - XSS)',
        'regex_constant' => 'REGEX_FULL_NAME',
        'regex_str' => '/^[a-zA-Z\s]{3,50}$/',
        'input' => '<script>alert(1)</script>',
        'expected' => false,
        'description' => 'Security test: HTML tags and special characters rejected'
    ],
    [
        'id' => 'TC_NAME_05',
        'field' => 'Full Name',
        'technique' => 'ECP (Invalid - Numeric)',
        'regex_constant' => 'REGEX_FULL_NAME',
        'regex_str' => '/^[a-zA-Z\s]{3,50}$/',
        'input' => 'John Doe 99',
        'expected' => false,
        'description' => 'Illegal numeric characters included in legal name'
    ],

    // Email Address Tests
    [
        'id' => 'TC_EMAIL_01',
        'field' => 'Email Address',
        'technique' => 'ECP (Valid)',
        'regex_constant' => 'REGEX_EMAIL',
        'regex_str' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
        'input' => 'citizen.2024@health.gov.in',
        'expected' => true,
        'description' => 'Standard compliant email with subdomains'
    ],
    [
        'id' => 'TC_EMAIL_02',
        'field' => 'Email Address',
        'technique' => 'ECP (Invalid - Missing @)',
        'regex_constant' => 'REGEX_EMAIL',
        'regex_str' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
        'input' => 'citizenhealth.org',
        'expected' => false,
        'description' => 'Malformed email missing the mandatory @ symbol'
    ],
    [
        'id' => 'TC_EMAIL_03',
        'field' => 'Email Address',
        'technique' => 'ECP (Invalid - Missing TLD)',
        'regex_constant' => 'REGEX_EMAIL',
        'regex_str' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
        'input' => 'patient@hospital',
        'expected' => false,
        'description' => 'Missing Top-Level Domain extension (.com, .org, etc.)'
    ],
    [
        'id' => 'TC_EMAIL_04',
        'field' => 'Email Address',
        'technique' => 'ECP (Invalid - Double @)',
        'regex_constant' => 'REGEX_EMAIL',
        'regex_str' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
        'input' => 'user@@health.com',
        'expected' => false,
        'description' => 'Erroneous duplicate @ separator'
    ],
    [
        'id' => 'TC_EMAIL_05',
        'field' => 'Email Address',
        'technique' => 'ECP (Invalid - Whitespace)',
        'regex_constant' => 'REGEX_EMAIL',
        'regex_str' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
        'input' => 'user name@health.org',
        'expected' => false,
        'description' => 'Illegal embedded whitespace inside username'
    ],

    // Mobile Number Tests
    [
        'id' => 'TC_PHONE_01',
        'field' => 'Mobile Phone',
        'technique' => 'ECP (Valid 10 Digits)',
        'regex_constant' => 'REGEX_PHONE',
        'regex_str' => '/^(\+91[\-\s]?)?[6-9]\d{9}$/',
        'input' => '9876543210',
        'expected' => true,
        'description' => 'Standard 10-digit mobile number starting with 9'
    ],
    [
        'id' => 'TC_PHONE_02',
        'field' => 'Mobile Phone',
        'technique' => 'ECP (Valid +91 Prefix)',
        'regex_constant' => 'REGEX_PHONE',
        'regex_str' => '/^(\+91[\-\s]?)?[6-9]\d{9}$/',
        'input' => '+91 9823456781',
        'expected' => true,
        'description' => 'Valid mobile with international +91 country prefix'
    ],
    [
        'id' => 'TC_PHONE_03',
        'field' => 'Mobile Phone',
        'technique' => 'BVA (9 Digits - Too Short)',
        'regex_constant' => 'REGEX_PHONE',
        'regex_str' => '/^(\+91[\-\s]?)?[6-9]\d{9}$/',
        'input' => '987654321',
        'expected' => false,
        'description' => 'Boundary error: 9 digits instead of required 10'
    ],
    [
        'id' => 'TC_PHONE_04',
        'field' => 'Mobile Phone',
        'technique' => 'BVA (11 Digits - Too Long)',
        'regex_constant' => 'REGEX_PHONE',
        'regex_str' => '/^(\+91[\-\s]?)?[6-9]\d{9}$/',
        'input' => '98765432109',
        'expected' => false,
        'description' => 'Boundary error: 11 digits exceeds maximum length'
    ],
    [
        'id' => 'TC_PHONE_05',
        'field' => 'Mobile Phone',
        'technique' => 'ECP (Invalid Starting Digit)',
        'regex_constant' => 'REGEX_PHONE',
        'regex_str' => '/^(\+91[\-\s]?)?[6-9]\d{9}$/',
        'input' => '4876543210',
        'expected' => false,
        'description' => 'Invalid starting digit 4 (must begin with 6, 7, 8, or 9)'
    ],

    // Password Security Tests
    [
        'id' => 'TC_PASS_01',
        'field' => 'Password Security',
        'technique' => 'ECP (Valid Strong)',
        'regex_constant' => 'REGEX_PASSWORD',
        'regex_str' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/',
        'input' => 'Covid@2024',
        'expected' => true,
        'description' => 'Meets all criteria: Upper, lower, digit, symbol, length 10'
    ],
    [
        'id' => 'TC_PASS_02',
        'field' => 'Password Security',
        'technique' => 'BVA (7 Chars - Too Short)',
        'regex_constant' => 'REGEX_PASSWORD',
        'regex_str' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/',
        'input' => 'Cov@123',
        'expected' => false,
        'description' => 'Boundary violation: 7 characters (min required 8)'
    ],
    [
        'id' => 'TC_PASS_03',
        'field' => 'Password Security',
        'technique' => 'ECP (Missing Special Char)',
        'regex_constant' => 'REGEX_PASSWORD',
        'regex_str' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/',
        'input' => 'Covid2024',
        'expected' => false,
        'description' => 'Missing mandatory special character (@$!%*?&)'
    ],
    [
        'id' => 'TC_PASS_04',
        'field' => 'Password Security',
        'technique' => 'ECP (Missing Uppercase)',
        'regex_constant' => 'REGEX_PASSWORD',
        'regex_str' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/',
        'input' => 'covid@2024',
        'expected' => false,
        'description' => 'Missing mandatory uppercase letter'
    ],
    [
        'id' => 'TC_PASS_05',
        'field' => 'Password Security',
        'technique' => 'ECP (Missing Digit)',
        'regex_constant' => 'REGEX_PASSWORD',
        'regex_str' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/',
        'input' => 'Covid@Care',
        'expected' => false,
        'description' => 'Missing mandatory numeric digit'
    ],

    // Government Aadhaar / National ID Tests
    [
        'id' => 'TC_ID_01',
        'field' => 'Aadhaar / National ID',
        'technique' => 'ECP (Valid 12 Digits)',
        'regex_constant' => 'REGEX_GOV_ID',
        'regex_str' => '/^[2-9]{1}[0-9]{3}\s?[0-9]{4}\s?[0-9]{4}$/',
        'input' => '458912345678',
        'expected' => true,
        'description' => 'Valid 12 continuous digits starting with 4'
    ],
    [
        'id' => 'TC_ID_02',
        'field' => 'Aadhaar / National ID',
        'technique' => 'ECP (Valid with Spaces)',
        'regex_constant' => 'REGEX_GOV_ID',
        'regex_str' => '/^[2-9]{1}[0-9]{3}\s?[0-9]{4}\s?[0-9]{4}$/',
        'input' => '4589 1234 5678',
        'expected' => true,
        'description' => 'Standard 4-4-4 formatted government identity string'
    ],
    [
        'id' => 'TC_ID_03',
        'field' => 'Aadhaar / National ID',
        'technique' => 'ECP (Invalid Starting Zero)',
        'regex_constant' => 'REGEX_GOV_ID',
        'regex_str' => '/^[2-9]{1}[0-9]{3}\s?[0-9]{4}\s?[0-9]{4}$/',
        'input' => '012345678901',
        'expected' => false,
        'description' => 'Aadhaar rule: First digit cannot be 0 or 1'
    ],
    [
        'id' => 'TC_ID_04',
        'field' => 'Aadhaar / National ID',
        'technique' => 'BVA (11 Digits - Too Short)',
        'regex_constant' => 'REGEX_GOV_ID',
        'regex_str' => '/^[2-9]{1}[0-9]{3}\s?[0-9]{4}\s?[0-9]{4}$/',
        'input' => '45891234567',
        'expected' => false,
        'description' => 'Boundary failure: Only 11 digits provided'
    ],
    [
        'id' => 'TC_ID_05',
        'field' => 'Aadhaar / National ID',
        'technique' => 'ECP (Alphabets in ID)',
        'regex_constant' => 'REGEX_GOV_ID',
        'regex_str' => '/^[2-9]{1}[0-9]{3}\s?[0-9]{4}\s?[0-9]{4}$/',
        'input' => '4589ABCD5678',
        'expected' => false,
        'description' => 'Letters rejected in purely numeric Aadhaar ID'
    ],

    // Postal Pincode Tests
    [
        'id' => 'TC_PIN_01',
        'field' => 'Postal PIN Code',
        'technique' => 'ECP (Valid 6 Digits)',
        'regex_constant' => 'REGEX_PINCODE',
        'regex_str' => '/^[1-9][0-9]{5}$/',
        'input' => '400001',
        'expected' => true,
        'description' => 'Standard valid 6-digit Indian PIN code'
    ],
    [
        'id' => 'TC_PIN_02',
        'field' => 'Postal PIN Code',
        'technique' => 'ECP (Leading Zero Error)',
        'regex_constant' => 'REGEX_PINCODE',
        'regex_str' => '/^[1-9][0-9]{5}$/',
        'input' => '012345',
        'expected' => false,
        'description' => 'PIN code cannot start with digit 0'
    ],
    [
        'id' => 'TC_PIN_03',
        'field' => 'Postal PIN Code',
        'technique' => 'BVA (5 Digits - Too Short)',
        'regex_constant' => 'REGEX_PINCODE',
        'regex_str' => '/^[1-9][0-9]{5}$/',
        'input' => '40000',
        'expected' => false,
        'description' => 'Boundary failure: 5 digits instead of 6'
    ],
    [
        'id' => 'TC_PIN_04',
        'field' => 'Postal PIN Code',
        'technique' => 'ECP (Non-numeric characters)',
        'regex_constant' => 'REGEX_PINCODE',
        'regex_str' => '/^[1-9][0-9]{5}$/',
        'input' => '40000A',
        'expected' => false,
        'description' => 'Alpha characters in postal numeric pin'
    ],

    // Date of Birth Tests
    [
        'id' => 'TC_DOB_01',
        'field' => 'Date of Birth',
        'technique' => 'ECP (Valid ISO Date)',
        'regex_constant' => 'REGEX_DOB',
        'regex_str' => '/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/',
        'input' => '1998-11-23',
        'expected' => true,
        'description' => 'Valid ISO 8601 date YYYY-MM-DD'
    ],
    [
        'id' => 'TC_DOB_02',
        'field' => 'Date of Birth',
        'technique' => 'ECP (Invalid Month 13)',
        'regex_constant' => 'REGEX_DOB',
        'regex_str' => '/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/',
        'input' => '1998-13-23',
        'expected' => false,
        'description' => 'Month 13 does not exist (boundary check 01-12)'
    ],
    [
        'id' => 'TC_DOB_03',
        'field' => 'Date of Birth',
        'technique' => 'ECP (Slash Separator)',
        'regex_constant' => 'REGEX_DOB',
        'regex_str' => '/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/',
        'input' => '23/11/1998',
        'expected' => false,
        'description' => 'DD/MM/YYYY rejected; requires ISO standard'
    ],

    // Comment Text Sanitization Tests
    [
        'id' => 'TC_CMT_01',
        'field' => 'Comment Message',
        'technique' => 'ECP (Valid Message)',
        'regex_constant' => 'REGEX_COMMENT',
        'regex_str' => '/^[a-zA-Z0-9\s.,!?\'"()\-]{5,500}$/u',
        'input' => 'Vaccination was smooth and well organized at the clinic.',
        'expected' => true,
        'description' => 'Normal human readable feedback statement'
    ],
    [
        'id' => 'TC_CMT_02',
        'field' => 'Comment Message',
        'technique' => 'ECP (XSS Injection)',
        'regex_constant' => 'REGEX_COMMENT',
        'regex_str' => '/^[a-zA-Z0-9\s.,!?\'"()\-]{5,500}$/u',
        'input' => '<script>window.location="hack.com"</script>',
        'expected' => false,
        'description' => 'XSS payload containing angle brackets rejected'
    ],
    [
        'id' => 'TC_CMT_03',
        'field' => 'Comment Message',
        'technique' => 'BVA (4 Chars - Too Short)',
        'regex_constant' => 'REGEX_COMMENT',
        'regex_str' => '/^[a-zA-Z0-9\s.,!?\'"()\-]{5,500}$/u',
        'input' => 'Good',
        'expected' => false,
        'description' => 'Boundary violation: 4 characters (min required 5)'
    ]
];

$results = [];
$totalPassed = 0;
$totalFailed = 0;

$startTime = microtime(true);

foreach ($testCases as $tc) {
    $pattern = constant($tc['regex_constant']);
    $actualMatches = (bool) preg_match($pattern, $tc['input']);
    
    // Test passes if actual match equals expected boolean
    $isTestPass = ($actualMatches === $tc['expected']);
    if ($isTestPass) {
        $totalPassed++;
    } else {
        $totalFailed++;
    }

    $results[] = [
        'id' => $tc['id'],
        'field' => $tc['field'],
        'technique' => $tc['technique'],
        'input' => $tc['input'],
        'regex_pattern' => $tc['regex_str'],
        'expected' => $tc['expected'] ? 'Valid' : 'Invalid',
        'actual' => $actualMatches ? 'Valid' : 'Invalid',
        'status' => $isTestPass ? 'PASS' : 'FAIL',
        'description' => $tc['description']
    ];
}

$executionTimeMs = round((microtime(true) - $startTime) * 1000, 2);
$totalTests = count($testCases);
$passRate = round(($totalPassed / $totalTests) * 100, 1);

echo json_encode([
    'summary' => [
        'total_tests' => $totalTests,
        'passed' => $totalPassed,
        'failed' => $totalFailed,
        'pass_rate' => $passRate . '%',
        'execution_time_ms' => $executionTimeMs,
        'engine' => 'PHP ' . PHP_VERSION . ' PCRE Regex Engine'
    ],
    'results' => $results
], JSON_PRETTY_PRINT);
