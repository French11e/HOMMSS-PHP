# Secure Database Backup and Restore

This document explains how to securely backup and restore your database using the command line.

## Security Features

The secure backup system includes the following security features:

1. **Encryption**: All database backups are encrypted using AES-256-CBC encryption
2. **Password Protection**: Backups require a password to decrypt and restore
3. **Integrity Verification**: Checksums are used to verify backup integrity
4. **Metadata**: Each backup includes metadata for verification and documentation

## Setup

1. The MySQL dump path is configured in `config/database.php`
2. The backup password is set in the `.env` file as `BACKUP_PASSWORD=your_password`
3. The backup scripts are configured to work on Windows and Linux systems

## Command Line Usage

### Secure Backup

#### Using Artisan Command

You can create a secure backup using the following Artisan command:

```bash
# Basic secure backup
php artisan app:secure-backup-database

# Custom filename
php artisan app:secure-backup-database --filename=my-secure-backup

# Custom password (otherwise uses BACKUP_PASSWORD from .env)
php artisan app:secure-backup-database --password=your_secure_password
```

#### Using the Backup Script

For convenience, a shell script is provided to make secure backups easier:

```bash
# Basic secure backup
./secure-backup-db.sh

# Custom filename
./secure-backup-db.sh --filename=my-secure-backup

# Custom password
./secure-backup-db.sh --password=your_secure_password
```

### Secure Restore

#### Using Artisan Command

You can restore a secure backup using the following Artisan command:

```bash
# Restore a backup (will prompt for password)
php artisan app:secure-restore-database path/to/backup.zip

# Restore with password
php artisan app:secure-restore-database path/to/backup.zip --password=your_password

# Force restore without confirmation
php artisan app:secure-restore-database path/to/backup.zip --force
```

#### Using the Restore Script

For convenience, a shell script is provided to make secure restores easier:

```bash
# Restore a backup (will prompt for password)
./secure-restore-db.sh path/to/backup.zip

# Restore with password
./secure-restore-db.sh path/to/backup.zip --password=your_password

# Force restore without confirmation
./secure-restore-db.sh path/to/backup.zip --force
```

## Backup Location

Secure backups are stored in the following location:

- Local: `storage/app/private/backups`

## Security Best Practices

1. **Store Passwords Securely**: Don't share the backup password or store it in insecure locations
2. **Regular Backups**: Schedule regular backups to prevent data loss
3. **Test Restores**: Periodically test the restore process to ensure backups are valid
4. **Offsite Storage**: Store backup copies in a separate secure location
5. **Rotation**: Implement a backup rotation strategy to maintain multiple backup versions

## Troubleshooting

If you encounter issues with secure backups or restores:

1. Make sure the MySQL dump path is correctly set in `config/database.php`
2. Verify that you're using the correct password for decryption
3. Check that the backup file is not corrupted
4. Ensure you have sufficient permissions to read/write the backup files
5. Check the Laravel log files in `storage/logs`

## Technical Details

### Encryption Method

The secure backup system uses AES-256-CBC encryption with the following process:

1. A random initialization vector (IV) is generated for each backup
2. The password is hashed using SHA-256 to create the encryption key
3. The database dump is encrypted using the key and IV
4. The IV is stored with the encrypted data to allow for decryption

### Backup Format

Each secure backup is a ZIP file containing:

1. An encrypted SQL dump file (*.enc)
2. A metadata JSON file with backup information
3. A README.txt file with basic instructions

### Restore Process

The restore process follows these steps:

1. Extract the backup ZIP file
2. Verify the metadata and backup integrity
3. Decrypt the SQL dump using the provided password
4. Restore the database using the MySQL client

## MySQL Path Configuration

If you're using XAMPP, the MySQL path is typically:
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
