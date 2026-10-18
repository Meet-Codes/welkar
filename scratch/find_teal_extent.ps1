Add-Type -AssemblyName System.Drawing
$crop = [System.Drawing.Bitmap]::FromFile('e:\welkar\scratch\logo_crop_debug.png')
for ($y = 0; $y -lt 15; $y++) {
    for ($x = 20; $x -lt 80; $x++) {
        $c = $crop.GetPixel($x, $y)
        # Look for teal
        if ($c.G -gt 40 -and $c.G -gt $c.R + 15) {
            Write-Output "x=$x, y=$y (R=$($c.R), G=$($c.G), B=$($c.B))"
        }
    }
}
$crop.Dispose()
