#!/bin/bash

# Simple script to backup the database
# Usage: ./backup-db.sh [--filename=custom-name]

FILENAME=""

# Parse arguments
for arg in "$@"
do
    case $arg in
        --filename=*)
        FILENAME="--filename=${arg#*=}"
        shift
        ;;
        *)
        # Unknown option
        ;;
    esac
done

# Run the backup command
echo "Starting backup process..."
php artisan app:simple-backup-database $FILENAME

# Check if backup was successful
if [ $? -eq 0 ]; then
    echo "Backup completed successfully!"
else
    echo "Backup failed!"
    exit 1
fi

exit 0
