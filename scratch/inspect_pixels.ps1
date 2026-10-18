Add-Type -AssemblyName System.Drawing
$bmp = [System.Drawing.Bitmap]::FromFile('C:\Users\mahek\.gemini\antigravity-ide\brain\852e865c-b276-4661-a4be-cf838f8df8ae\scratch\crops_marked\01_top_navbar.png')
for ($y = 0; $y -lt 30; $y++) {
    for ($x = 0; $x -lt 60; $x++) {
        $c = $bmp.GetPixel($x, $y)
        # Look for greenish/teal tint
        if ($c.G -gt 40 -and $c.G -gt $c.R + 20) {
            Write-Output "x=$x, y=$y, R=$($c.R), G=$($c.G), B=$($c.B)"
        }
    }
}
$bmp.Dispose()
