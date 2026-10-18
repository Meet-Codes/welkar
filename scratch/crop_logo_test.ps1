Add-Type -AssemblyName System.Drawing
$orig = [System.Drawing.Bitmap]::FromFile('C:\Users\mahek\.gemini\antigravity-ide\brain\852e865c-b276-4661-a4be-cf838f8df8ae\.user_uploaded\media_1792298344737.png')
$crop = New-Object System.Drawing.Bitmap 80, 40
$g = [System.Drawing.Graphics]::FromImage($crop)
$g.DrawImage($orig, [System.Drawing.Rectangle]::new(0, 0, 80, 40), [System.Drawing.Rectangle]::new(0, 0, 80, 40), [System.Drawing.GraphicsUnit]::Pixel)
$crop.Save('e:\welkar\scratch\logo_crop_debug.png')
$crop.Dispose()
$g.Dispose()
$orig.Dispose()
Write-Output "Done cropping logo area."
