Add-Type -AssemblyName System.Drawing
$crop = [System.Drawing.Bitmap]::FromFile('e:\welkar\scratch\logo_crop_debug.png')
for ($y = 8; $y -le 11; $y++) {
    for ($x = 30; $x -lt 60; $x++) {
        $c = $crop.GetPixel($x, $y)
        Write-Output ("x={0}, y={1} - R={2}, G={3}, B={4}" -f $x, $y, $c.R, $c.G, $c.B)
    }
}
$crop.Dispose()
