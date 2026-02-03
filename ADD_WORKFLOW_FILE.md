# Adding GitHub Actions Workflow File

The workflow file (`.github/workflows/deploy.yml`) needs to be added manually because your Personal Access Token needs the `workflow` scope.

## Option 1: Update Your Token (Recommended)

1. Go to: **https://github.com/settings/tokens**
2. Find your token or create a new one
3. When creating/editing, make sure to check the **`workflow`** scope
4. Update your token in your local Git config

Then you can push the workflow file normally.

## Option 2: Add Workflow File via GitHub Web Interface

1. Go to your repository: **https://github.com/oftenfredict-source/wauminilink_aict**

2. Click **"Add file"** → **"Create new file"**

3. In the filename box, type: `.github/workflows/deploy.yml`

4. Copy and paste the content from the file below:

---

## Workflow File Content

```yaml
name: Deploy to Production

on:
  push:
    branches:
      - main

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
      - name: Checkout code
        uses: actions/checkout@v4
      
      - name: Deploy to server via SSH
        uses: appleboy/ssh-action@v1.0.3
        with:
          host: ${{ secrets.SERVER_HOST }}
          username: ${{ secrets.SERVER_USER }}
          key: ${{ secrets.SERVER_SSH_KEY }}
          port: ${{ secrets.SERVER_PORT }}
          script: |
            cd ${{ secrets.SERVER_PATH }}
            git pull origin main
            composer install --no-dev --optimize-autoloader
            php artisan migrate --force
            php artisan config:cache
            php artisan route:cache
            php artisan view:cache
            php artisan optimize
            chmod -R 755 storage bootstrap/cache
            echo "Deployment completed successfully!"
```

5. Click **"Commit new file"**

---

## After Adding the Workflow

Once the workflow file is added, you can:

1. Set up your GitHub secrets (see `QUICK_DEPLOYMENT_CHECKLIST.md`)
2. Test the deployment by pushing a small change
3. Check the Actions tab to see if it runs successfully

---

**Note**: The workflow file is also available locally at `.github/workflows/deploy.yml` if you want to reference it.










