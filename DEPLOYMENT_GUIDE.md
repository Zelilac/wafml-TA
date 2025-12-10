# Deployment Guide - WAF + evote-2 Integration

Complete guide for deploying the integrated system in development and production environments.

## 📋 Table of Contents

1. [Development Setup](#development-setup)
2. [Docker Deployment](#docker-deployment)
3. [Production Deployment](#production-deployment)
4. [Monitoring & Maintenance](#monitoring--maintenance)
5. [Troubleshooting](#troubleshooting)

---

## Development Setup

### Prerequisites

- **PHP**: 7.3+ (8.0+ recommended)
- **Python**: 3.8+
- **MySQL**: 5.7+ or MariaDB
- **Composer**: Latest
- **pip**: Python package manager
- **Node.js**: 12+ (for frontend builds, optional)

### Step 1: Initial Setup

```bash
# Navigate to project root
cd /path/to/ProyekTA

# Run automated setup
bash setup-complete.sh
```

### Step 2: Database Configuration

```bash
cd evote-2

# Create database
mysql -u root -p -e "CREATE DATABASE evote CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run migrations
php artisan migrate

# Seed initial data (optional)
php artisan db:seed
```

### Step 3: Start Services

```bash
# Terminal 1: WAF Service
cd new-WAF
source venv/bin/activate
python waf.py

# Terminal 2: Laravel Application
cd evote-2
php artisan serve

# Terminal 3: Run tests (optional)
bash test-integration.sh
```

### Access Points

- **evote-2**: http://localhost:8000
- **WAF API**: http://localhost:5000
- **WAF Dashboard**: http://localhost:5000/

---

## Docker Deployment

### Quick Start with Docker Compose

```bash
# Build and start all services
docker-compose up -d

# View logs
docker-compose logs -f

# Stop services
docker-compose down
```

### Services Included

| Service | Port | Image |
|---------|------|-------|
| evote-2 (Laravel) | 8000 | php:8.0-fpm |
| new-WAF (Flask) | 5000 | python:3.10-slim |
| MySQL | 3306 | mysql:8.0 |
| phpMyAdmin | 8080 | phpmyadmin:latest |

### Database Setup in Docker

```bash
# Access database
mysql -h 127.0.0.1 -u evote_user -p evote

# Or via phpMyAdmin
# http://localhost:8080
# User: evote_user
# Password: evote_password
```

### Rebuild Containers

```bash
# Force rebuild
docker-compose up -d --build

# Remove old images
docker image prune -a

# See container status
docker-compose ps
```

---

## Production Deployment

### Environment Configuration

#### 1. evote-2 (.env)

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...  # Generate with: php artisan key:generate

# Database (use managed service)
DB_CONNECTION=mysql
DB_HOST=your-mysql-host
DB_DATABASE=evote_prod
DB_USERNAME=evote_prod_user
DB_PASSWORD=STRONG_PASSWORD_HERE

# WAF Configuration
WAF_ENABLED=true
WAF_ENDPOINT=https://waf.yourdomain.com/predict

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=warning

# Cache (use Redis for performance)
CACHE_DRIVER=redis
REDIS_HOST=your-redis-host
REDIS_PASSWORD=STRONG_PASSWORD_HERE

# Session
SESSION_DRIVER=redis

# Queue (for async jobs)
QUEUE_CONNECTION=redis
```

#### 2. new-WAF (.env)

```env
FLASK_ENV=production
WAF_THRESHOLD=0.6
WAF_MODEL=random_forest.joblib
```

### 2. Server Setup (Ubuntu 20.04 LTS)

```bash
# Update system
sudo apt-get update && sudo apt-get upgrade -y

# Install PHP
sudo apt-get install -y php8.0-fpm php8.0-mysql php8.0-curl php8.0-xml

# Install Python
sudo apt-get install -y python3.10 python3-pip python3.10-venv

# Install MySQL
sudo apt-get install -y mysql-server

# Install Nginx
sudo apt-get install -y nginx

# Install Redis (optional, for caching)
sudo apt-get install -y redis-server

# Install Supervisor (for process management)
sudo apt-get install -y supervisor
```

### 3. Deploy evote-2 with Nginx + PHP-FPM

Create `/etc/nginx/sites-available/evote`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;

    # Redirect to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;

    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;

    root /var/www/evote-2/public;
    index index.php;

    # Gzip compression
    gzip on;
    gzip_types text/plain text/css application/json application/javascript;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.0-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

Enable site:

```bash
sudo ln -s /etc/nginx/sites-available/evote /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### 4. Deploy new-WAF with Gunicorn + Supervisor

Create `/etc/supervisor/conf.d/waf.conf`:

```ini
[program:waf]
command=/home/ubuntu/new-WAF/venv/bin/gunicorn --bind 127.0.0.1:5000 --workers 4 --timeout 30 waf:app
directory=/home/ubuntu/new-WAF
user=ubuntu
autostart=true
autorestart=true
stderr_logfile=/var/log/waf/error.log
stdout_logfile=/var/log/waf/access.log
environment=PYTHONUNBUFFERED=1

[group:waf]
programs=waf
```

Start supervisor:

```bash
sudo mkdir -p /var/log/waf
sudo chown ubuntu:ubuntu /var/log/waf

sudo systemctl restart supervisor
sudo supervisorctl status
```

### 5. Nginx as Reverse Proxy for WAF

Add to Nginx config:

```nginx
location /api/waf/ {
    proxy_pass http://127.0.0.1:5000/;
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    proxy_redirect off;
}
```

### 6. SSL Certificate (Let's Encrypt)

```bash
sudo apt-get install -y certbot python3-certbot-nginx
sudo certbot certonly --nginx -d yourdomain.com -d www.yourdomain.com
sudo systemctl restart nginx
```

### 7. Database Backups

```bash
# Create backup script /home/ubuntu/backup-db.sh
#!/bin/bash
BACKUP_DIR="/backups/mysql"
DB_NAME="evote_prod"
DB_USER="evote_prod_user"

mkdir -p $BACKUP_DIR
mysqldump -u $DB_USER -p $DB_NAME > $BACKUP_DIR/db_$(date +%Y%m%d_%H%M%S).sql

# Compress old backups
find $BACKUP_DIR -name "*.sql" -mtime +7 -exec gzip {} \;

# Add to crontab (runs daily at 2 AM)
crontab -e
# 0 2 * * * /home/ubuntu/backup-db.sh
```

---

## Monitoring & Maintenance

### Health Checks

```bash
# Check evote-2 status
curl -s http://localhost:8000/api/waf/status | jq

# Check WAF status
curl -s http://localhost:5000/ | head -20

# Check MySQL
mysqladmin -u root -p ping

# Check Redis (if used)
redis-cli ping
```

### Logs

```bash
# Laravel logs
tail -f /var/www/evote-2/storage/logs/laravel.log

# WAF logs
sudo journalctl -u supervisor -f
tail -f /var/log/waf/access.log

# Nginx logs
sudo tail -f /var/log/nginx/error.log
```

### Performance Monitoring

```bash
# Use New Relic, DataDog, or similar:
# - Application metrics
# - Error tracking
# - Performance profiling
```

### Model Updates

```bash
# Retrain models periodically
cd new-WAF
jupyter notebook test.ipynb

# Copy new models
cp artifacts/*.joblib models/

# Reload in production
curl -X POST http://your-waf-api/reload
```

---

## Troubleshooting

### "502 Bad Gateway" Error

```bash
# Check if PHP-FPM is running
sudo systemctl status php8.0-fpm

# Check if WAF is accessible
curl http://127.0.0.1:5000/

# Check Nginx error log
sudo tail -50 /var/log/nginx/error.log
```

### "Connection refused" to WAF

```bash
# Check if Gunicorn is running
sudo supervisorctl status waf

# Restart WAF
sudo supervisorctl restart waf

# Check firewall
sudo ufw status
sudo ufw allow 5000/tcp  # If needed
```

### Database Connection Issues

```bash
# Test MySQL connection
mysql -h localhost -u evote_prod_user -p evote_prod

# Check MySQL service
sudo systemctl status mysql

# Check MySQL logs
sudo tail -50 /var/log/mysql/error.log
```

### High Memory Usage

```bash
# Check PHP-FPM pool settings
sudo nano /etc/php/8.0/fpm/pool.d/www.conf

# Recommended for production:
# pm.max_children = 50
# pm.start_servers = 10
# pm.min_spare_servers = 5
# pm.max_spare_servers = 20

sudo systemctl reload php8.0-fpm
```

### SSL Certificate Renewal

```bash
# Renew certificate (auto-renewing should be enabled)
sudo certbot renew --dry-run

# Manual renewal
sudo certbot renew
```

---

## Deployment Checklist

- [ ] Database backed up
- [ ] `.env` file configured for production
- [ ] SSL certificates installed
- [ ] PHP-FPM pool settings optimized
- [ ] WAF models updated
- [ ] Monitoring configured
- [ ] Backup scheduled
- [ ] Logs rotated
- [ ] Firewall rules configured
- [ ] Application tested thoroughly
- [ ] Performance benchmarked

---

## Support & Documentation

- **Laravel Documentation**: https://laravel.com/docs
- **Flask Documentation**: https://flask.palletsprojects.com
- **Nginx Documentation**: https://nginx.org/en/docs/
- **Scikit-learn Documentation**: https://scikit-learn.org
- **Integration Guide**: See `INTEGRATION_GUIDE.md`

---

**Last Updated**: December 8, 2025
