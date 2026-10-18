Add-Type -AssemblyName System.Drawing
$crop = [System.Drawing.Bitmap]::FromFile('e:\welkar\scratch\logo_crop_debug.png')
for ($y = 0; $y -lt $crop.Height; $y++) {
    for ($x = 0; $x -lt $crop.Width; $x++) {
        $c = $crop.GetPixel($x, $y)
        # Look for teal marking
        if ($c.G -gt 40 -and $c.G -gt $c.R + 20) {
            Write-Output "x=$x, y=$y (R=$($c.R), G=$($c.G), B=$($c.B))"
        }
    }
}
$crop.Dispose()
