param(
    [string] $Root = (Resolve-Path "$PSScriptRoot\..").Path
)

$ErrorActionPreference = 'Stop'

$php = Get-Command php -ErrorAction SilentlyContinue

if (-not $php) {
    Write-Error 'PHP is not available in PATH. Install PHP or run this command from an environment where php is available.'
}

$files = Get-ChildItem -Path $Root -Recurse -Filter '*.php' -File |
    Where-Object {
        $_.FullName -notmatch '\\vendor\\' -and
        $_.FullName -notmatch '\\node_modules\\' -and
        $_.FullName -notmatch '\\.git\\'
    }

if (-not $files) {
    Write-Host 'No PHP files found.'
    exit 0
}

$failed = @()

foreach ($file in $files) {
    $result = & $php.Source -l $file.FullName 2>&1

    if ($LASTEXITCODE -ne 0) {
        $failed += [PSCustomObject]@{
            File = $file.FullName
            Output = ($result | Out-String).Trim()
        }
    }
}

if ($failed.Count -gt 0) {
    Write-Host 'PHP syntax check failed:' -ForegroundColor Red

    foreach ($item in $failed) {
        Write-Host $item.File -ForegroundColor Red
        Write-Host $item.Output
    }

    exit 1
}

Write-Host "PHP syntax check passed for $($files.Count) files." -ForegroundColor Green
