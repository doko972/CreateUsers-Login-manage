<?php

function generateToken() {
    if (!isset($_SESSION['token']) || !isset($_SESSION['tokenExpire']) || $_SESSION['tokenExpire'] < time()) {
        $_SESSION['token'] = md5(uniqid(mt_rand(), true));
        $_SESSION['tokenExpire'] = time() + 60 * 15;
    }
}

function redirectTo(string $url): void {
    // Assurez-vous qu'il n'y a pas de sortie avant d'envoyer les en-têtes
    if (headers_sent()) {
        echo "<script>location.href='$url';</script>";
    } else {
        header('Location: ' . $url);
    }
    exit;
}

function getArrayAsHTMLList(array $array, string $ulClass = '', string $liClass = ''): string {
    $ulClass = $ulClass ? ' class="' . $ulClass . '"' : '';
    $liClass = $liClass ? ' class="' . $liClass . '"' : '';
    return '<ul' . $ulClass . '>'
        . implode(array_map(fn ($v) => '<li' . $liClass . '>' . $v . '</li>', $array))
        . '</ul>';
}

function getHtmlErrors(array $errorsList): string {
    if (!empty($_SESSION['errorsList'])) {
        $errors = $_SESSION['errorsList'];
        unset($_SESSION['errorsList']);
        return getArrayAsHTMLList(array_map(fn ($e) => $errorsList[$e] ?? 'Erreur inconnue', $errors), 'notif-error');
    }
    return '';
}

function getHtmlMessages(array $messagesList): string {
    if (isset($_SESSION['msg'])) {
        $m = $_SESSION['msg'];
        unset($_SESSION['msg']);
        return isset($messagesList[$m]) ? '<p class="notif-success">' . $messagesList[$m] . '</p>' : '';
    }
    return '';
}

function isRefererOk(): bool {
    global $globalUrl;
    return isset($_SERVER['HTTP_REFERER']) && str_contains($_SERVER['HTTP_REFERER'], $globalUrl);
}

function isTokenOk(?array $data = null): bool {
    if (!is_array($data)) $data = $_REQUEST;
    return isset($_SESSION['token']) && isset($data['token']) && $_SESSION['token'] === $data['token'];
}

function preventCSRF(string $redirectUrl = 'index.php'): void {
    if (!isRefererOk()) {
        addError('referer');
        redirectTo($redirectUrl);
    }
    if (!isTokenOk()) {
        addError('csrf');
        redirectTo($redirectUrl);
    }
}

function preventCSRFAPI(array $inputData): void {
    if (!isRefererOk()) triggerError('referer');
    if (!isTokenOk($inputData)) triggerError('csrf');
}

function triggerError(string $error): void {
    global $errors;
    echo json_encode([
        'isOk' => false,
        'errorMessage' => $errors[$error] ?? 'Erreur inconnue'
    ]);
    exit;
}

function addError(string $errorMsg): void {
    if (!isset($_SESSION['errorsList'])) {
        $_SESSION['errorsList'] = [];
    }
    $_SESSION['errorsList'][] = $errorMsg;
}

function addMessage(string $message): void {
    $_SESSION['msg'] = $message;
}

function eraseFormData(): void {
    unset($_SESSION['formData']);
}

function stripTagsArray(array &$data): void {
    $data = array_map('strip_tags', $data);
}

function checkProductInfo(array $productData): bool {
    if (!isset($productData['nameProduct']) || strlen($productData['nameProduct']) === 0) {
        addError('product_name');
    }
    if (strlen($productData['nameProduct']) > 50) {
        addError('product_name_size');
    }
    if (!isset($productData['price']) || !is_numeric($productData['price'])) {
        addError('product_price');
    }
    return empty($_SESSION['errorsList']);
}
?>