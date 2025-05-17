#!/bin/bash

# Secure restore script for database
# Usage: ./secure-restore-db.sh backup_file.zip [--password=your-password] [--force]

if [ -z "$1" ]; then
    echo "Error: Backup file path is required"
    echo "Usage: ./secure-restore-db.sh backup_file.zip [--password=your-password] [--force]"
    exit 1
fi

BACKUP_FILE=$1
shift

PASSWORD=""
FORCE=""

# Parse arguments
for arg in "$@"
do
    case $arg in
        --password=*)
        PASSWORD="--password=${arg#*=}"
        shift
        ;;
        --force)
        FORCE="--force"
        shift
        ;;
        *)
        # Unknown option
        ;;
    esac
done

# Run the restore command
echo "Starting secure restore process..."
php artisan app:secure-restore-database "$BACKUP_FILE" $PASSWORD $FORCE

# Check if restore was successful
if [ $? -eq 0 ]; then
    echo "Secure restore completed successfully!"
else
    echo "Secure restore failed!"
    exit 1
fi

exit 0
