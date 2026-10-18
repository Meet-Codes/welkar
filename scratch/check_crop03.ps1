Add-Type -AssemblyName System.Drawing
$crop = [System.Drawing.Bitmap]::FromFile('C:\Users\mahek\.gemini\antigravity-ide\brain\852e865c-b276-4661-a4be-cf838f8df8ae\scratch\crops_marked\03_craftsmanship_heading.png')
Write-Output "Size: $($crop.Width) x $($crop.Height)"
for ($y = 0; $y -lt 30; $y++) {
    for ($x = 0; $x -lt 60; $x++) {
        $c = $crop.GetPixel($x, $y)
        if ($c.R -lt 240 -or $c.G -lt 240 -or $c.B -lt 240) {
            Write-Output ("x={0}, y={1}: R={2}, G={3}, B={4}" -f $x, $y, $c.R, $c.G, $c.B)
        }
    }
}
$crop.Dispose()
