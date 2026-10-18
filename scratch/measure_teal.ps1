Add-Type -AssemblyName System.Drawing
$bmp = [System.Drawing.Bitmap]::FromFile('C:\Users\mahek\.gemini\antigravity-ide\brain\852e865c-b276-4661-a4be-cf838f8df8ae\scratch\crops_marked\01_top_navbar.png')
$minX = $bmp.Width
$maxX = 0
$minY = $bmp.Height
$maxY = 0
$count = 0
for ($x = 0; $x -lt $bmp.Width; $x++) {
    for ($y = 0; $y -lt $bmp.Height; $y++) {
        $c = $bmp.GetPixel($x, $y)
        if ($c.R -lt 70 -and $c.G -gt 70 -and $c.B -gt 60) {
            $count++
            if ($x -lt $minX) { $minX = $x }
            if ($x -gt $maxX) { $maxX = $x }
            if ($y -lt $minY) { $minY = $y }
            if ($y -gt $maxY) { $maxY = $y }
        }
    }
}
Write-Output "Image size: $($bmp.Width) x $($bmp.Height)"
Write-Output "Teal count: $count"
Write-Output "Bounds: ($minX, $minY) to ($maxX, $maxY)"
Write-Output "Width: $($maxX - $minX), Height: $($maxY - $minY)"
$bmp.Dispose()
