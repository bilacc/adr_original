# Run from repository root in PowerShell.
# Creates backups and removes injected 'ndsw' obfuscated blocks from .js and .php files.
$repoRoot = Get-Location
$backupDir = Join-Path $repoRoot "tools\backup_js"
New-Item -ItemType Directory -Path $backupDir -Force | Out-Null

# Pattern: match blocks starting with "if(ndsw===undefined)" up to a following closing "});" or "}}());" or "})();" conservatively.
$pattern = '(?ms)if\s*\(\s*ndsw\s*===\s*undefined\s*\)\s*\{.*?\}\s*(?:\)\s*\(\s*\)\s*;|;\s*$)'

Get-ChildItem -Path $repoRoot -Recurse -Include *.js,*.php -File |
  ForEach-Object {
    $path = $_.FullName
    try {
      $content = Get-Content -Raw -LiteralPath $path -ErrorAction Stop
    } catch {
      return
    }

    if ($content -match 'if\s*\(\s*ndsw\s*===') {
      # backup (preserve relative structure)
      $rel = $path.Substring($repoRoot.Path.Length).TrimStart('\')
      $dest = Join-Path $backupDir ($rel -replace '[\\/:]','__')
      New-Item -ItemType Directory -Path (Split-Path $dest) -Force | Out-Null
      Copy-Item -LiteralPath $path -Destination $dest -Force

      # clean
      $new = [regex]::Replace($content, $pattern, '', [System.Text.RegularExpressions.RegexOptions]::Singleline)
      if ($new -ne $content) {
        Set-Content -LiteralPath $path -Value $new -Encoding UTF8
        Write-Host "Cleaned: $path"
      }
    }
  }

Write-Host "Done. Backups in $backupDir"