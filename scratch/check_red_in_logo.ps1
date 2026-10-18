Add-Type -AssemblyName System.Drawing
$crop = [System.Drawing.Bitmap]::FromFile('e:\welkar\scratch\logo_crop_debug.png')
for ($y = 0; $y -lt $crop.Height; $y++) {
    for ($x = 0; $x -lt $crop.Width; $x++) {
        $c = $crop.GetPixel($x, $y)
        # Look for red marking
        if ($c.R -gt 100 -and $c.G -lt 50 -and $c.B -lt 50) {
            Write-Output "RED: x=$x, y=$y (R=$($c.R), G=$($c.G), B=$($c.B))"
        }
    }
}
$crop.Dispose()
