<?php
$xmlFilePath = 'Lab1_Part1.xml';

if (!file_exists($xmlFilePath)) {
    $dom = new DOMDocument('1.0', 'UTF-8');
    $employees = $dom->createElement('employees');
    $dom->appendChild($employees);
    $dom->save($xmlFilePath);
} else {
    $dom = new DOMDocument();
    $dom->load($xmlFilePath);
}

function insertEmployee($name, $email, $phones, $address) {
    global $dom, $xmlFilePath;
    $employee = $dom->createElement('employee');
    $nameElement = $dom->createElement('name', $name);
    $emailElement = $dom->createElement('email', $email);

    $phonesElement = $dom->createElement('phones');
    foreach ($phones as $type => $phone) {
        $phoneElement = $dom->createElement('phone', $phone);
        $phoneElement->setAttribute('type', $type);
        $phonesElement->appendChild($phoneElement);
    }

    $addressElement = $dom->createElement('address');
    foreach ($address as $key => $value) {
        $addressElement->appendChild($dom->createElement($key, $value));
    }

    $employee->appendChild($nameElement);
    $employee->appendChild($emailElement);
    $employee->appendChild($phonesElement);
    $employee->appendChild($addressElement);

    $employees = $dom->getElementsByTagName('employees')->item(0);
    $employees->appendChild($employee);
    $dom->save($xmlFilePath);
}

function searchEmployees($searchValue, $searchField) {
    global $dom;
    $searchResults = [];
    $employees = $dom->getElementsByTagName('employee');
    foreach ($employees as $employee) {
        $currentFieldValue = $employee->getElementsByTagName($searchField)->item(0)->nodeValue;
        if (str_contains(strtolower($currentFieldValue), strtolower($searchValue))) {
            $searchResults[] = $employee;
        }
    }
    return $searchResults;
}

$searchResults = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phones = $_POST['phones'] ?? [];
    $address = $_POST['address'] ?? [];
    $searchValue = trim($_POST['searchValue'] ?? '');
    $searchField = $_POST['searchField'] ?? 'name';

    switch ($action) {
        case 'Insert':
            insertEmployee($name, $email, $phones, $address);
            break;
        case 'Search':
            $searchResults = searchEmployees($searchValue, $searchField);
            break;
    }
}

function displaySearchResults($searchResults) {
    if (!empty($searchResults)) {
        echo '<div class="search-results mt-4">';
        echo '<h2 class="mb-3">Search Results</h2>';
        foreach ($searchResults as $employee) {
            echo '<div class="card mb-3">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($employee->getElementsByTagName('name')->item(0)->nodeValue) . '</h5>';
            echo '<p class="card-text"><strong>Email:</strong> ' . htmlspecialchars($employee->getElementsByTagName('email')->item(0)->nodeValue) . '</p>';
            echo '<p class="card-text"><strong>Phones:</strong><br>';
            $phones = $employee->getElementsByTagName('phone');
            foreach ($phones as $phone) {
                echo htmlspecialchars($phone->getAttribute('type')) . ': ' . htmlspecialchars($phone->nodeValue) . '<br>';
            }
            echo '</p>';
            echo '<p class="card-text"><strong>Address:</strong><br>';
            $address = $employee->getElementsByTagName('address')->item(0);
            $addressElements = ['street', 'building_number', 'city', 'region', 'country'];
            foreach ($addressElements as $element) {
                echo ucfirst($element) . ': ' . htmlspecialchars($address->getElementsByTagName($element)->item(0)->nodeValue) . '<br>';
            }
            echo '</p>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }
}
?>