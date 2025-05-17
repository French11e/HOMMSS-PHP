#!/bin/bash

# Secure backup script for database
# Usage: ./secure-backup-db.sh [--filename=custom-name] [--password=your-password]

FILENAME=""
PASSWORD=""

# Parse arguments
for arg in "$@"
do
    case $arg in
        --filename=*)
        FILENAME="--filename=${arg#*=}"
        shift
        ;;
        --password=*)
        PASSWORD="--password=${arg#*=}"
        shift
        ;;
        *)
        # Unknown option
        ;;
    esac
done

# Run the backup command
echo "Starting secure backup process..."
php artisan app:secure-backup-database $FILENAME $PASSWORD

# Check if backup was successful
if [ $? -eq 0 ]; then
    echo "Secure backup completed successfully!"
else
    echo "Secure backup failed!"
    exit 1
fi

exit 0
