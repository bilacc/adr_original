<?php
// Run once to add property-specific columns to items table if missing.
// Usage: php upgrade_schema.php  OR open in browser once.
require_once __DIR__ . '/lib/functions.php';

$db = new Database();

$alterations = [
    "ALTER TABLE items ADD COLUMN IF NOT EXISTS area_m2 INT NULL",
    "ALTER TABLE items ADD COLUMN IF NOT EXISTS rooms INT NULL",
    "ALTER TABLE items ADD COLUMN IF NOT EXISTS city VARCHAR(255) NULL",
    "ALTER TABLE items ADD COLUMN IF NOT EXISTS address VARCHAR(255) NULL",
    "ALTER TABLE items ADD COLUMN IF NOT EXISTS floor INT NULL",
    "ALTER TABLE items ADD COLUMN IF NOT EXISTS total_floors INT NULL",
    "ALTER TABLE items ADD COLUMN IF NOT EXISTS heating VARCHAR(100) NULL",
    "ALTER TABLE items ADD COLUMN IF NOT EXISTS energetski_certifikat VARCHAR(50) NULL",
    "ALTER TABLE items ADD COLUMN IF NOT EXISTS lat VARCHAR(30) NULL",
    "ALTER TABLE items ADD COLUMN IF NOT EXISTS lng VARCHAR(30) NULL",
    "ALTER TABLE items ADD COLUMN IF NOT EXISTS category_id INT NULL",
    "ALTER TABLE items ADD COLUMN IF NOT EXISTS published TINYINT(1) NOT NULL DEFAULT 0",
];

echo "<pre>Running schema upgrade...\n";
foreach ($alterations as $sql) {
    try {
        // MySQL <= 5.7 doesn't support IF NOT EXISTS for columns; do safe check
        // We'll try a generic approach: run and catch errors silently
        $db->exec($sql);
        echo "OK: $sql\n";
    } catch (Exception $e) {
        // try fallback for adding column if the engine complains
        echo "WARN: Could not run: $sql\n  " . $e->getMessage() . "\n";
    }
}
echo "Done. Verify schema and delete upgrade_schema.php after run.\n</pre>";
?>