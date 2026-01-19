<?php
function get_locale() {
    if (isset($_GET['lang']) && in_array($_GET['lang'], ['es', 'en'])) {
        $_SESSION['lang'] = $_GET['lang'];
    }
    
    if (!isset($_SESSION['lang'])) {
        $_SESSION['lang'] = 'es';
    }
    
    return $_SESSION['lang'];
}

function load_translations($lang) {
    $path = __DIR__ . '/../locales/' . $lang . '.php';
    if (file_exists($path)) {
        return include $path;
    }
    return [];
}

$current_lang = get_locale();
$translations = load_translations($current_lang);

function __($key) {
    global $translations;
    return $translations[$key] ?? $key;
}
