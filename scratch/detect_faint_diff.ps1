Add-Type -AssemblyName System.Drawing
$crop = [System.Drawing.Bitmap]::FromFile('C:\Users\mahek\.gemini\antigravity-ide\brain\852e865c-b276-4661-a4be-cf838f8df8ae\scratch\crops_marked\03_craftsmanship_heading.png')
$bgR = $crop.GetPixel(10, 12).R
$bgG = $crop.GetPixel(10, 12).G
$bgB = $crop.GetPixel(10, 12).B
Write-Output ("Reference BG: R={0}, G={1}, B={2}" -f $bgR, $bgG, $bgB)
for ($y = 12; $y -le 22; $y++) {
    $line = ""
    for ($x = 10; $x -le 90; $x++) {
        $c = $crop.GetPixel($x, $y)
        $diff = [Math]::Abs($c.R - $bgR) + [Math]::Abs($c.G - $bgG) + [Math]::Abs($c.B - $bgB)
        if ($diff -gt 6) {
            $line += "#"
        } elseif ($diff -gt 2) {
            $line += "."
        } else {
            $line += " "
        }
    }
    Write-Output ("Y={0:D2}: {1}" -f $y, $line)
}
$crop.Dispose()
