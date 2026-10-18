Add-Type -AssemblyName System.Drawing
$crop = [System.Drawing.Bitmap]::FromFile('e:\welkar\scratch\logo_crop_debug.png')
for ($y = 0; $y -lt $crop.Height; $y++) {
    $line = ""
    for ($x = 0; $x -lt 40; $x++) {
        $c = $crop.GetPixel($x, $y)
        if ($c.G -gt 40 -and $c.G -gt $c.R + 15) {
            $line += "T" # Teal
        } elseif ($c.R -gt 90 -and $c.G -lt 50) {
            $line += "R" # Red
        } elseif ($c.R -gt 150 -and $c.G -gt 150) {
            $line += "W" # White / Text
        } else {
            $line += "." # Background
        }
    }
    Write-Output ("{0:D2}: {1}" -f $y, $line)
}
$crop.Dispose()
