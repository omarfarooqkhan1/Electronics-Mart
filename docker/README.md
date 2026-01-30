# Docker Setup for NI Drip Central

This directory contains Docker configuration for the NI Drip Central e-commerce platform.

## Services

### MySQL Database
- **Image**: mysql:8.0
- **Container**: ni_drip_central_mysql
- **Port**: 3306
- **Database**: ni_drip_central
- **Username**: ni_drip_user
- **Password**: ni_drip_password

### Mailpit (Email Testing)
- **Image**: axllent/mailpit:latest
- **Container**: ni_drip_central_mailpit
- **SMTP Port**: 1025
- **Web UI Port**: 8025
- **Web UI URL**: http://localhost:8025

## Quick Start

1. **Start Services**:
   ```bash
   docker-compose up -d
   ```

2. **Update Laravel Configuration**:
   - Copy `.env.example` to `.env`
   - Uncomment MySQL configuration in `.env`
   - Comment out SQLite configuration

3. **Run Migrations**:
   ```bash
   php artisan migrate --seed
   ```

4. **Access Services**:
   - Application: http://localhost:8000
   - Mailpit Web UI: http://localhost:8025
   - MySQL: localhost:3306

## Commands

- **Start services**: `docker-compose up -d`
- **Stop services**: `docker-compose down`
- **View logs**: `docker-compose logs -f [service_name]`
- **Restart service**: `docker-compose restart [service_name]`

## Data Persistence

MySQL data is persisted in a Docker volume named `mysql_data`. To reset the database:

```bash
docker-compose down -v
docker-compose up -d
```

## Network

All services run on the `ni_drip_network` bridge network for internal communication.