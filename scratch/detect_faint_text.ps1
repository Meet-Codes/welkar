Add-Type -AssemblyName System.Drawing
$crop = [System.Drawing.Bitmap]::FromFile('C:\Users\mahek\.gemini\antigravity-ide\brain\852e865c-b276-4661-a4be-cf838f8df8ae\scratch\crops_marked\03_craftsmanship_heading.png')
for ($y = 12; $y -le 22; $y++) {
    $line = ""
    for ($x = 10; $x -le 90; $x++) {
        $c = $crop.GetPixel($x, $y)
        # Background is white/light ~255. Text might be very faint white-on-light! (e.g. 240-252 vs 255)
        # Let's print difference from 255
        $diff = 255 - [Math]::Min($c.R, [Math]::Min($c.G, $c.B))
        if ($diff -gt 15) {
            $line += "#"
        } elseif ($diff -gt 4) {
            $line += ":"
        } elseif ($diff -gt 1) {
            $line += "."
        } else {
            $line += " "
        }
    }
    Write-Output ("Y={0:D2}: {1}" -f $y, $line)
}
$crop.Dispose()
