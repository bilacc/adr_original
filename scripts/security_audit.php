<?php
// Simple static scan for common malicious patterns.
// Run from CLI: php scripts/security_audit.php
$root = __DIR__ . '/..';
$patterns = [
    'base64_decode' => '/base64_decode\s*\(/i',
    'eval' => '/\beval\s*\(/i',
    'gzinflate' => '/gzinflate\s*\(/i',
    'preg_replace_e' => '/preg_replace\s*\(.*?,.*?,.*?,\s*[\'"]e[\'"]\s*\)/i',
    'assert' => '/\bassert\s*\(/i',
    'system_exec' => '/\b(system|exec|shell_exec|passthru)\s*\(/i',
    'file_get_contents_remote' => '/file_get_contents\s*\(\s*["\']https?:\/\//i',
    'curl_exec' => '/curl_exec\s*\(/i'
];

$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
$results = [];
foreach ($files as $f) {
    if (!$f->isFile()) continue;
    $ext = pathinfo($f->getFilename(), PATHINFO_EXTENSION);
    if (!in_array($ext, ['php','inc','phtml'])) continue;
    $content = file_get_contents($f->getPathname());
    foreach ($patterns as $name => $re) {
        if (preg_match_all($re, $content, $m)) {
            $results[] = [
                'file' => str_replace($root . DIRECTORY_SEPARATOR, '', $f->getPathname()),
                'pattern' => $name,
                'count' => count($m[0])
            ];
        }
    }
}

echo "Security scan results:\n\n";
if (empty($results)) {
    echo "No suspicious patterns found by this quick scan.\n";
} else {
    foreach ($results as $r) {
        echo "{$r['file']} -> {$r['pattern']} ({$r['count']})\n";
    }
    echo "\nPlease inspect the listed files manually. This tool finds patterns but cannot confirm intent.\n";
}
?>