# Database Backup Documentation

This document explains how to backup your database using the Spatie Backup package without using the GUI.

## Prerequisites

- Ensure the Spatie Backup package is installed and configured
- Make sure your `.env` file has the `BACKUP_ARCHIVE_PASSWORD` variable set

## Command Line Backup

### Using Artisan Command

You can backup your database using the following Artisan command:

```bash
# Backup only the database
php artisan app:backup-database --only-db

# Full backup (database + files)
php artisan app:backup-database

# Custom filename
php artisan app:backup-database --only-db --filename=my-custom-backup
```

### Using the Backup Script

For convenience, a shell script is provided to make backups easier:

```bash
# Backup only the database (default)
./backup-db.sh

# Full backup (database + files)
./backup-db.sh --full

# Custom filename
./backup-db.sh --filename=my-custom-backup

# Full backup with custom filename
./backup-db.sh --full --filename=my-full-backup
```

## Scheduled Backups

To schedule automatic backups, edit your `app/Console/Kernel.php` file and add the following to the `schedule` method:

```php
// Daily database backup at 2:00 AM
$schedule->command('app:backup-database --only-db')->dailyAt('02:00');

// Weekly full backup on Sundays at 3:00 AM
$schedule->command('app:backup-database')->weeklyOn(0, '03:00');
```

## Backup Location

Backups are stored in the following locations:

- Local: `storage/app/private/backups`
- Cloud: If configured, backups are also stored in your cloud storage (S3, OCI, etc.)

## Security

- All backups are encrypted using the password specified in your `.env` file
- To extract a backup, you'll need the same password

## Restoring Backups

To restore a backup:

1. Extract the backup file using the password
2. If it's a database-only backup, restore the database using your database management tool
3. If it's a full backup, also restore the files to their appropriate locations

## Troubleshooting

If you encounter issues with backups:

1. Check the Laravel log files in `storage/logs`
2. Ensure your database credentials are correct
3. Verify that the backup directory is writable
4. Make sure you have enough disk space
