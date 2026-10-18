Add-Type -AssemblyName System.Drawing
$crop = [System.Drawing.Bitmap]::FromFile('C:\Users\mahek\.gemini\antigravity-ide\brain\852e865c-b276-4661-a4be-cf838f8df8ae\.user_uploaded\media_1792298344737.png')
for ($y = 0; $y -lt 30; $y++) {
    for ($x = 20; $x -lt 80; $x++) {
        $c = $crop.GetPixel($x, $y)
        # Look for teal (G high, B high, R low)
        if ($c.G -gt 60 -and $c.B -gt 60 -and $c.G -gt $c.R + 20) {
            Write-Output ("Teal at x={0}, y={1}: R={2}, G={3}, B={4}" -f $x, $y, $c.R, $c.G, $c.B)
        }
    }
}
$crop.Dispose()
