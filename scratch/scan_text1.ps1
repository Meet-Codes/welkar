Add-Type -AssemblyName System.Drawing
$crop = [System.Drawing.Bitmap]::FromFile('C:\Users\mahek\.gemini\antigravity-ide\brain\852e865c-b276-4661-a4be-cf838f8df8ae\scratch\crops_marked\02_pillar_heading.png')
for ($y = 10; $y -lt 34; $y++) {
    for ($x = 15; $x -lt 65; $x++) {
        $c = $crop.GetPixel($x, $y)
        # Check if NOT light gray background (background is ~248, 250, 252)
        if ($c.R -lt 240 -or $c.G -lt 240 -or $c.B -lt 240) {
            Write-Output ("Text found at x={0}, y={1} - R={2}, G={3}, B={4}" -f $x, $y, $c.R, $c.G, $c.B)
        }
    }
}
$crop.Dispose()
