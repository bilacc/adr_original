<?php
// Simple scanner to find likely SQL string concatenations or unsafe patterns.
// Run from project root: php scripts/find_sql_concat.php

$root = __DIR__ . '/..';
$exts = ['php','inc','phtml'];
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
$patterns = [
    // Looks for occurrences of string concatenation inside queries: e.g. "SELECT ... " . $var
    'concat_sql' => '/(Db::|mysql_query|mysqli_query|PDO->query|exec\()\s*[\'"][^\'"]*\.(\\$|[^\)]*\\$)/i',
    // occurrences of variables inside SQL strings with quotes: "WHERE id = '.$id.'"
    'dot_var_in_sql' => '/(Db::|mysql_query|mysqli_query)\s*\(\s*[\'"][^\'"]*\\.\s*\\$[a-zA-Z_]+/i',
    // simple pattern for non-prepared calls that include ".$
    'embedded_var' => '/[\'"][^\'"]*\\.\s*\\$[a-zA-Z_]+\\.[^\'"]*[\'"]/i'
];

$report = [];

foreach ($files as $f) {
    if (!$f->isFile()) continue;
    $ext = pathinfo($f->getFilename(), PATHINFO_EXTENSION);
    if (!in_array($ext, $exts)) continue;
    $content = file_get_contents($f->getPathname());
    foreach ($patterns as $name => $re) {
        if (preg_match_all($re, $content, $m, PREG_OFFSET_CAPTURE)) {
            foreach ($m[0] as $match) {
                $pos = $match[1];
                $line = substr_count(substr($content, 0, $pos), "\n") + 1;
                $report[] = [
                    'file' => str_replace($root . DIRECTORY_SEPARATOR, '', $f->getPathname()),
                    'pattern' => $name,
                    'line' => $line,
                    'snippet' => trim($match[0])
                ];
            }
        }
    }
}

if (empty($report)) {
    echo "No likely SQL concatenations found by this quick scan.\n";
} else {
    echo "Potential unsafe SQL concatenations / interpolations:\n\n";
    foreach ($report as $r) {
        echo "{$r['file']}:{$r['line']} -> {$r['pattern']}\n  {$r['snippet']}\n\n";
    }
    echo "Review each occurrence and replace with prepared statements (Db::query with placeholders) where possible.\n";
}
?>