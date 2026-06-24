$ProjectDir = "D:\ims"
$BackupDir  = "D:\DatabaseBackups\bkbkrghs"
$Container  = "ims_db"
$RetentionDays = 7

New-Item -ItemType Directory -Force -Path $BackupDir | Out-Null

$envFile = Join-Path $ProjectDir ".env"
$envVars = @{}
Get-Content $envFile | ForEach-Object {
    if ($_ -match '^\s*([^#=]+)\s*=\s*(.*)\s*$') {
        $envVars[$matches[1].Trim()] = $matches[2].Trim()
    }
}
$dbName = $envVars["DB_NAME"]
$rootPw = $envVars["DB_ROOT_PASSWORD"]

$stamp = Get-Date -Format "yyyy-MM-dd"
$outFile = Join-Path $BackupDir "$dbName`_$stamp.sql"

docker exec $Container sh -c "exec mysqldump -uroot -p'$rootPw' $dbName" > $outFile

if ((Get-Item $outFile).Length -eq 0) {
    Write-Error "Backup produced an empty file, removing it: $outFile"
    Remove-Item $outFile
    exit 1
}

Get-ChildItem -Path $BackupDir -Filter "*.sql" |
    Where-Object { $_.LastWriteTime -lt (Get-Date).AddDays(-$RetentionDays) } |
    Remove-Item -Force

Write-Output "Backup complete: $outFile"
