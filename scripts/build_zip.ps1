$zipPath = "e:/welkar/welkar_production_website.zip"
if (Test-Path $zipPath) { Remove-Item -Force $zipPath }

Add-Type -AssemblyName System.IO.Compression
Add-Type -AssemblyName System.IO.Compression.FileSystem

$baseDir = "e:/welkar"

$zipStream = [System.IO.File]::Create($zipPath)
$archive = New-Object System.IO.Compression.ZipArchive($zipStream, [System.IO.Compression.ZipArchiveMode]::Create)

function Add-ZipEntry($filePath, $entryName) {
    $entryName = $entryName.Replace('\', '/')
    $compression = [System.IO.Compression.CompressionLevel]::Optimal
    [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile($archive, $filePath, $entryName, $compression) | Out-Null
}

function Add-DirectoryToZip($sourceDir, $prefix) {
    Get-ChildItem -Path $sourceDir -Recurse -Force | ForEach-Object {
        if (-not $_.PSIsContainer) {
            $rel = $_.FullName.Substring($sourceDir.Length).TrimStart('\', '/')
            $entry = if ($prefix) { "$prefix/$rel" } else { $rel }
            Add-ZipEntry $_.FullName $entry
        }
    }
}

# 1. Core directories
Add-DirectoryToZip "$baseDir/app" "app"
Add-DirectoryToZip "$baseDir/config" "config"
Add-DirectoryToZip "$baseDir/database/migrations" "database/migrations"
Add-DirectoryToZip "$baseDir/database/seeders" "database/seeders"
Add-ZipEntry "$baseDir/database/welkar_production_backup.sql" "database/welkar_production_backup.sql"
Add-DirectoryToZip "$baseDir/public" "public"
Add-DirectoryToZip "$baseDir/resources" "resources"
Add-DirectoryToZip "$baseDir/docs" "docs"
Add-ZipEntry "$baseDir/scripts/backup.php" "scripts/backup.php"
Add-ZipEntry "$baseDir/scripts/restore.php" "scripts/restore.php"
Add-ZipEntry "$baseDir/scripts/generate_sitemap.php" "scripts/generate_sitemap.php"
Add-ZipEntry "$baseDir/scripts/seed_accurate_product_faqs.php" "scripts/seed_accurate_product_faqs.php"
Add-ZipEntry "$baseDir/tests/run_tests.php" "tests/run_tests.php"

# Storage .gitkeep files
$storageDirs = @(
    "storage/backups",
    "storage/imports",
    "storage/invoices",
    "storage/logs",
    "storage/private",
    "storage/sessions",
    "storage/uploads",
    "storage/uploads/products"
)
foreach ($sd in $storageDirs) {
    $fullSd = "$baseDir/$sd"
    if (-not (Test-Path $fullSd)) { New-Item -ItemType Directory -Path $fullSd -Force | Out-Null }
    $gitkeep = "$fullSd/.gitkeep"
    if (-not (Test-Path $gitkeep)) { [System.IO.File]::WriteAllText($gitkeep, "") }
    Add-ZipEntry $gitkeep "$sd/.gitkeep"
}

# Root files
Add-ZipEntry "$baseDir/.env.example" ".env.example"
Add-ZipEntry "$baseDir/.gitignore" ".gitignore"
Add-ZipEntry "$baseDir/.htaccess" ".htaccess"
Add-ZipEntry "$baseDir/DEPLOYMENT_INSTRUCTIONS.md" "DEPLOYMENT_INSTRUCTIONS.md"
Add-ZipEntry "$baseDir/README.md" "README.md"

$archive.Dispose()
$zipStream.Dispose()

Get-Item $zipPath | Select-Object Name, @{N='SizeMB';E={[math]::Round($_.Length/1MB, 2)}}, LastWriteTime
