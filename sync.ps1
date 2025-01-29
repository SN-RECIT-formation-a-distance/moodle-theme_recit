$from = "moodle-theme_recit/src/*"
$to = "shared/recitfad/theme/recit2/"
$source = "./src";

try {
    . ("..\sync\watcher.ps1")
}
catch {
    Write-Host "Error while loading sync.ps1 script." 
}