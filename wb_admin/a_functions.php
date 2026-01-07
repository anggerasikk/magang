<?php
function encode_id($id) {
    return rtrim(strtr(base64_encode($id . '|my_secret_salt'), '+/', '-_'), '=');
}

function decode_id($hash) {
    $decoded = base64_decode(strtr($hash, '-_', '+/'));
    $parts = explode('|', $decoded);
    return (isset($parts[0]) && $parts[1] === 'jdih2025_s4lt!') ? (int)$parts[0] : 0;
}
?>
