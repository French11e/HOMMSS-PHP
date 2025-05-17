# Database Backup and Restore

This document explains how to backup and restore your database without using the GUI.

## Setup

1. The MySQL dump path is configured in `config/database.php`
2. The backup scripts are configured to work on Windows systems
3. The backup password is set in the `.env` file as `BACKUP_PASSWORD=your_password`

## Command Line Usage

### Standard Backup

#### Using Artisan Command

You can backup your database using the following Artisan command:

```bash
# Simple database backup
php artisan app:simple-backup-database

# Custom filename
php artisan app:simple-backup-database --filename=my-custom-backup
```

#### Using the Backup Script

For convenience, a shell script is provided to make backups easier:

```bash
# Database backup
./backup-db.sh

# Custom filename
./backup-db.sh --filename=my-custom-backup
```

### Secure Backup and Restore

For secure, encrypted backups and restores, use the following commands:

#### Secure Backup

```bash
# Secure database backup
php artisan app:secure-backup-database

# Custom filename
php artisan app:secure-backup-database --filename=my-secure-backup

# Custom password
php artisan app:secure-backup-database --password=your_secure_password
```

#### Secure Restore

```bash
# Restore a backup (will prompt for password)
php artisan app:secure-restore-database path/to/backup.zip

# Restore with password
php artisan app:secure-restore-database path/to/backup.zip --password=your_password

# Force restore without confirmation
php artisan app:secure-restore-database path/to/backup.zip --force
```

#### Using the Secure Scripts

```bash
# Secure backup
./secure-backup-db.sh

# Secure restore
./secure-restore-db.sh path/to/backup.zip
```

For more detailed information about secure backups, see `docs/secure-database-backup.md`.

## Scheduled Backups

Automatic backups are scheduled in the `app/Console/Kernel.php` file:

-   Daily secure database backup at 2:00 AM
-   Weekly standard database backup on Sundays at 3:00 AM (as a fallback)

To ensure scheduled backups run, make sure your cron job is set up:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

For Windows, you can use Task Scheduler to run the following command every minute:

```
php C:\path\to\your\project\artisan schedule:run
```

You can customize the backup schedule by editing the `app/Console/Kernel.php` file.

## Backup Location

Backups are stored in the following location:

-   Local: `storage/app/private/backups`

## Security

-   Standard backups are stored in a protected directory with .htaccess restrictions
-   Secure backups are encrypted using AES-256-CBC encryption with a password
-   Backup integrity is verified using checksums
-   Keep your backup password safe - you will need it to restore the backup
-   For additional security, consider moving backup files to a secure offsite location

## Troubleshooting

If you encounter issues with backups:

1. Make sure the MySQL dump path is correctly set in `config/database.php`
2. Check that the backup directory is writable
3. Verify that you have enough disk space
4. Check the Laravel log files in `storage/logs`

## MySQL Path Configuration

If you're using XAMPP, the MySQL dump path is typically:

```
C:\xampp\mysql\bin\
```

For other installations, locate your MySQL bin directory and update the path in `config/database.php`:

```php
'dump' => [
    'dump_binary_path' => 'C:\\path\\to\\mysql\\bin\\',
    'use_single_transaction' => true,
    'timeout' => 60 * 5, // 5 minutes
],
```
