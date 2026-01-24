# PowerShell script to test SSH connection to your server
# Run this script to verify SSH access before setting up GitHub Actions

param(
    [Parameter(Mandatory=$true)]
    [string]$ServerHost,
    
    [Parameter(Mandatory=$true)]
    [string]$ServerUser,
    
    [Parameter(Mandatory=$false)]
    [int]$ServerPort = 22
)

Write-Host "🔍 Testing SSH connection..." -ForegroundColor Cyan
Write-Host "Server: $ServerUser@$ServerHost:$ServerPort" -ForegroundColor Yellow
Write-Host ""

# Test SSH connection
try {
    $sshKeyPath = "$env:USERPROFILE\.ssh\github_actions_wauminilink"
    
    if (-not (Test-Path $sshKeyPath)) {
        Write-Host "❌ SSH key not found at: $sshKeyPath" -ForegroundColor Red
        exit 1
    }
    
    Write-Host "📝 Using SSH key: $sshKeyPath" -ForegroundColor Green
    
    # Test connection
    $testCommand = "ssh -i `"$sshKeyPath`" -p $ServerPort -o StrictHostKeyChecking=no -o ConnectTimeout=10 $ServerUser@$ServerHost 'echo Connection successful && pwd && whoami'"
    
    Write-Host "🔄 Attempting connection..." -ForegroundColor Yellow
    $result = Invoke-Expression $testCommand 2>&1
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host ""
        Write-Host "✅ SSH connection successful!" -ForegroundColor Green
        Write-Host ""
        Write-Host "Server Response:" -ForegroundColor Cyan
        Write-Host $result
        Write-Host ""
        Write-Host "✅ Your SSH setup is correct. You can proceed with GitHub Actions setup!" -ForegroundColor Green
    } else {
        Write-Host ""
        Write-Host "❌ SSH connection failed!" -ForegroundColor Red
        Write-Host ""
        Write-Host "Error details:" -ForegroundColor Yellow
        Write-Host $result
        Write-Host ""
        Write-Host "Troubleshooting:" -ForegroundColor Yellow
        Write-Host "1. Verify SERVER_HOST, SERVER_USER, and SERVER_PORT are correct"
        Write-Host "2. Ensure the public key is added to ~/.ssh/authorized_keys on the server"
        Write-Host "3. Check if SSH service is running on the server"
        Write-Host "4. Verify firewall allows SSH connections on port $ServerPort"
    }
} catch {
    Write-Host ""
    Write-Host "❌ Error: $_" -ForegroundColor Red
    Write-Host ""
    Write-Host "Make sure:" -ForegroundColor Yellow
    Write-Host "- OpenSSH client is installed (usually comes with Windows 10/11)"
    Write-Host "- You have internet connection"
    Write-Host "- Server details are correct"
}









