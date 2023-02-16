$IP_RANGE = Read-Host "input the range of IP addresses to scan"

$ports_to_scan = Read-Host "input first port range to scan"

$second_port_range = Read-Host "input last port range to scan"

$integer = [int]$ports_to_scan
$integer2 =[int]$second_port_range
for($integer; $integer -le $integer2; $integer++){
    $testConnection = Test-NetConnection -port $integer -ComputerName $IP_RANGE
    $testConnection 
}

$testConnection | out-file -FilePath D:\scan.txt
