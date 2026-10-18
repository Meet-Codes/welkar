Add-Type -AssemblyName System.Drawing
$crop = [System.Drawing.Bitmap]::FromFile('C:\Users\mahek\.gemini\antigravity-ide\brain\852e865c-b276-4661-a4be-cf838f8df8ae\scratch\crops_marked\03_craftsmanship_heading.png')
for ($y = 11; $y -lt 24; $y++) {
    for ($x = 10; $x -lt 100; $x++) {
        $c = $crop.GetPixel($x, $y)
        # Check if NOT light background (R < 240) and NOT purple (R > 150 & B > 150 & G < 120)
        $isPurple = ($c.R -gt 140 -and $c.B -gt 140 -and $c.G -lt 120)
        if (($c.R -lt 240 -or $c.G -lt 240 -or $c.B -lt 240) -and -not $isPurple) {
            Write-Output ("x={0}, y={1}: R={2}, G={3}, B={4}" -f $x, $y, $c.R, $c.G, $c.B)
        }
    }
}
$crop.Dispose()
