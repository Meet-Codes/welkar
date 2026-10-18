Add-Type -AssemblyName System.Drawing
$crop = [System.Drawing.Bitmap]::FromFile('C:\Users\mahek\.gemini\antigravity-ide\brain\852e865c-b276-4661-a4be-cf838f8df8ae\scratch\crops_marked\02_pillar_heading.png')
for ($y = 20; $y -lt 35; $y++) {
    for ($x = 15; $x -lt 55; $x++) {
        $c = $crop.GetPixel($x, $y)
        Write-Output ("x={0}, y={1} - R={2}, G={3}, B={4}" -f $x, $y, $c.R, $c.G, $c.B)
    }
}
$crop.Dispose()
