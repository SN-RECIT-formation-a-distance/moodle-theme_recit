$from = "moodle-theme_recit/src/*"
$to = "shared/recitfad2/theme/recit2/"

try {
    . ("..\sync\watcher.ps1")
}
catch {
    Write-Host "Error while loading sync.ps1 script." 
}