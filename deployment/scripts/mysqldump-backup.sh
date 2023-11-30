#!/usr/bin/env bash

set -e

# Check if the environment variables "DATABASE_HOST" are set
if [ -z "$DATABASE_HOST" ]; then
    echo "The environment variable DATABASE_HOST is not set. Exiting..."
    exit 1
fi

# Check if the environment variables "DATABASE_PORT" are set
if [ -z "$DATABASE_PORT" ]; then
    echo "The environment variable DATABASE_PORT is not set. Exiting..."
    exit 1
fi

# Check if the environment variables "DATABASE_USER" are set
if [ -z "$DATABASE_USER" ]; then
    echo "The environment variable DATABASE_USER is not set. Exiting..."
    exit 1
fi

# Check if the environment variables "DATABASE_PASSWORD" are set
if [ -z "$DATABASE_PASSWORD" ]; then
    echo "The environment variable DATABASE_PASSWORD is not set. Exiting..."
    exit 1
fi

# Check if the environment variables "DATABASE_NAME" are set
if [ -z "$DATABASE_NAME" ]; then
    echo "The environment variable DATABASE_NAME is not set. Exiting..."
    exit 1
fi

# Check if the environment variables "BACKUP_FILE_NAME" are set
if [ -z "$BACKUP_FILE_NAME" ]; then
    echo "The environment variable BACKUP_FILE_NAME is not set. Exiting..."
    exit 1
fi

# Check if the environment variables "S3_BUCKET_NAME" are set
if [ -z "$S3_BUCKET_NAME" ]; then
    echo "The environment variable S3_BUCKET_NAME is not set. Exiting..."
    exit 1
fi

# Check if the "AWS_ACCESS_KEY_ID" environment variable is set
if [ -z "$AWS_ACCESS_KEY_ID" ]; then
    echo "The environment variable AWS_ACCESS_KEY_ID is not set. Exiting..."
    exit 1
fi

# Check if the "AWS_SECRET_ACCESS_KEY" environment variable is set
if [ -z "$AWS_SECRET_ACCESS_KEY" ]; then
    echo "The environment variable AWS_SECRET_ACCESS_KEY is not set. Exiting..."
    exit 1
fi

# Check if the "AWS_DEFAULT_REGION" environment variable is set
if [ -z "$AWS_DEFAULT_REGION" ]; then
    echo "The environment variable AWS_DEFAULT_REGION is not set. Exiting..."
    exit 1
fi

# Use mysqldump to backup the database
mysqldump -h $DATABASE_HOST -P $DATABASE_PORT -u $DATABASE_USER --password=$DATABASE_PASSWORD $DATABASE_NAME > $BACKUP_FILE_NAME

# Use AWS CLI to upload the backup to S3
aws s3 cp $BACKUP_FILE_NAME s3://$S3_BUCKET_NAME/$BACKUP_FILE_NAME

# Check if the backup was successful
if [ $? -eq 0 ]; then
    echo "The backup was successful."
    exit 0
else
    echo "The backup was not successful."
    exit 1
fi
