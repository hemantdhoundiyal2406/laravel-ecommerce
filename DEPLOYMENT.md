# Deploy UrbanCart on Render

This project is prepared for Render using Docker, a Render Blueprint, and a managed PostgreSQL database.

## 1. Generate Production App Key

Run locally and keep the output for Render:

```bash
php artisan key:generate --show
```

The value looks like `base64:...`. Paste it into Render as `APP_KEY`.

## 2. Push Code to GitHub/GitLab/Bitbucket

Render needs a Git-backed repository.

```bash
git init
git add .
git commit -m "Prepare Laravel app for Render deployment"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO.git
git push -u origin main
```

## 3. Create Render Blueprint

Open:

```text
https://dashboard.render.com/blueprint/new
```

Select the pushed repository. Render will read `render.yaml` and create:

- Docker web service: `urbancart`
- PostgreSQL database: `urbancart-db`

## 4. Fill Render Environment Variables

Render will ask for these because they are marked as secrets or account-specific values:

- `APP_KEY`: output from `php artisan key:generate --show`
- `APP_URL`: your Render URL, for example `https://urbancart.onrender.com`
- `MAIL_FROM_ADDRESS`: your store email address
- `SEED_ADMIN_EMAIL`: your admin login email
- `SEED_ADMIN_PASSWORD`: a strong admin password

By default, `MAIL_MAILER=log` so the site can go live before SMTP is configured. Configure SMTP later if you want real emails.

## 5. Deploy

Click **Apply** in Render. The container startup script will:

- Link storage
- Run database migrations
- Seed demo products/admin only when the database is empty
- Cache Laravel config, routes, and views
- Serve the app on Render's assigned port

## Notes

- Uploaded images use Render container storage unless you configure S3-compatible storage. For a real production store, use S3/R2/Spaces and set `FILESYSTEM_DISK=s3`.
- Online payment is still a placeholder. COD works.
- After deployment, change `APP_URL` to your custom domain if you add one.
