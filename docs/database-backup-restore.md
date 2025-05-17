# Secure Database Backup and Restore

This document explains how to securely backup and restore your database.

## Security Features

The secure backup system includes the following security features:

1. **Encryption**: All database backups are encrypted using AES-256-CBC encryption
2. **Password Protection**: Backups require a password to decrypt and restore
3. **Integrity Verification**: SHA-256 hashing is used to verify backup integrity
4. **Metadata**: Each backup includes metadata for verification and documentation

## Backup Your Database

You can backup your database using the following command:

```bash
php artisan app:backup-database
```

The backups will be stored in `storage/app/Laravel/` with filenames like `hommss-db-backup2025-05-17-20-31-40.zip`.

### Custom Filename

You can specify a custom filename for your backup:

```bash
php artisan app:backup-database --filename=my-custom-backup
```

### Secure Password Handling

For security reasons, it's recommended to let the system prompt you for a password:

```bash
php artisan app:backup-database
```

When you run this command, you'll be securely prompted to enter a password. The password won't be visible as you type, and it won't be stored in your command history.

If you leave the password blank when prompted, the system will use the password defined in your `.env` file as `BACKUP_PASSWORD`.

> **Security Note**: Avoid using the `--password` option directly in the command line, as this will store your password in your command history.

### Scheduled Backups

The system is configured to automatically create a backup daily at 2:00 AM Manila time (Asia/Manila timezone, UTC+8). These backups will be stored in the same location.

#### How Scheduled Backups Handle Passwords

For scheduled backups, the system automatically uses the password defined in your `.env` file as `BACKUP_PASSWORD`. This allows backups to run automatically without requiring user input.

The output of scheduled backups is logged to `storage/logs/backup.log`, and email notifications for both successful and failed backups are sent to the admin email address (hommss666@gmail.com) specified in your `.env` file as `ADMIN_EMAIL`.

#### Setting Up the Scheduler

To ensure scheduled backups run, make sure your cron job is set up:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

For Windows, you can use Task Scheduler to run the following command every minute:

```
php C:\path\to\your\project\artisan schedule:run
```

### Retention Policy

The retention policy (in `config/backup.php`) is set to:

-   Keep all backups for 7 days
-   Keep daily backups for 16 days
-   Keep weekly backups for 8 weeks
-   Keep monthly backups for 4 months
-   Keep yearly backups for 2 years

This means that older backups will be automatically cleaned up according to this policy.

## Restore Your Database

### Basic Restoration

The easiest way to restore your database is to use the provided batch file:

1. Double-click on `restore-database.bat`
2. Select the backup you want to restore from the list
3. Enter the password used to encrypt the backup
4. Confirm that you want to proceed with the restoration
5. Wait for the process to complete

### Advanced Restoration

You can also restore a specific backup file using the command:

```bash
php artisan app:restore-database --backup=hommss-db-backup2025-05-17-20-48-27.zip
```

Replace the filename with the actual backup file you want to restore. You will be prompted for the password.

### Secure Password Entry

When restoring a backup, you'll be securely prompted to enter the password:

```bash
php artisan app:restore-database --backup=hommss-db-backup2025-05-17-20-48-27.zip
```

The password prompt is secure - your password won't be visible as you type, and it won't be stored in your command history.

> **Security Note**: Avoid using the `--password` option directly in the command line, as this will store your password in your command history.

### Force Restoration

If you want to skip the confirmation prompt, you can use the `--force` option:

```bash
php artisan app:restore-database --backup=hommss-db-backup2025-05-17-20-48-27.zip --force
```

You'll still be prompted for the password securely.

## Backup File Structure

Each backup is a ZIP file containing:

1. An encrypted SQL dump of your database (\*.enc)
2. A metadata JSON file with backup information, including the hash for integrity verification
3. A README.txt file with basic instructions

The SQL dump is encrypted using AES-256-CBC encryption with a password. The hash is generated using SHA-256 to ensure the integrity of the backup.

## Email Notifications

The backup and restore system sends email notifications to keep you informed about the status of your operations:

### Backup Notifications

1. **Scheduled Backups**:

    - Success notifications: Sent when a scheduled backup completes successfully
    - Failure notifications: Sent when a scheduled backup fails

2. **Manual Backups**:
    - Success notifications: Sent when a manual backup completes successfully
    - Failure notifications: Sent when a manual backup fails

### Restore Notifications

1. **All Restore Operations**:
    - Success notifications: Sent when a database restore completes successfully
    - Failure notifications: Sent when a database restore fails

All notifications are sent to the admin email address (hommss666@gmail.com) specified in your `.env` file as `ADMIN_EMAIL`. The timestamps in all email notifications use the Manila timezone (Asia/Manila, UTC+8).

## Password Security

The backup system is designed with security in mind:

1. **Secure Password Entry**: Passwords are entered using a secure prompt that doesn't display what you type
2. **No Command History**: When prompted for a password, it's not stored in your command history
3. **Environment Variables**: Default passwords can be stored in your `.env` file, which should have restricted access
4. **Strong Encryption**: Backups use AES-256-CBC encryption with salting, a strong industry-standard algorithm
5. **Integrity Verification**: SHA-256 hashing ensures your backup hasn't been tampered with
6. **Password Verification**: Passwords are verified using a secure hash before attempting decryption
7. **Salted Passwords**: Each backup uses a unique salt to enhance password security
8. **Scheduler Security**: Scheduled backups use the environment password without exposing it in logs or process lists
9. **No Password Display**: Passwords are never displayed in command output or logs

### Password Best Practices

1. **Use Strong Passwords**: Include a mix of uppercase, lowercase, numbers, and special characters
2. **Change Passwords Regularly**: Update your backup password periodically
3. **Different Passwords**: Use a different password for your backups than for other systems
4. **Secure Storage**: If you need to store your password, use a password manager
5. **Limited Access**: Restrict access to your backup files and password information

### Securing Your .env File

Since your backup password is stored in the `.env` file, it's crucial to secure this file:

1. **File Permissions**: Set restrictive permissions on your `.env` file (e.g., `chmod 600 .env` on Linux/Unix)
2. **Server Configuration**: Ensure your web server is configured to deny access to `.env` files
3. **Version Control**: Never commit your `.env` file to version control
4. **Backup Securely**: When backing up your `.env` file, ensure it's stored securely
5. **Limited Access**: Only system administrators should have access to the `.env` file

## Troubleshooting

If you encounter issues with backups or restores:

1. Make sure the MySQL dump path is correctly set in `config/database.php`
2. Check that the backup directory is writable
3. Verify that you have sufficient permissions to read/write the backup files
4. Check the Laravel log files in `storage/logs`
5. If you're having password issues, make sure you're using the correct password for the specific backup

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
