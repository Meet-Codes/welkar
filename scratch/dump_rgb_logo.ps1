Add-Type -AssemblyName System.Drawing
$crop = [System.Drawing.Bitmap]::FromFile('e:\welkar\scratch\logo_crop_debug.png')
for ($y = 0; $y -lt 20; $y++) {
    $line = ""
    for ($x = 0; $x -lt 40; $x++) {
        $c = $crop.GetPixel($x, $y)
        $line += "$($c.R),$($c.G),$($c.B) "
    }
    Write-Output "Y=$y : $line"
}
$crop.Dispose()
