#!/bin/sh

# Configure mc alias for MinIO
mc alias set minio http://minio:9000 quynh quynh121201

# Create the bucket (replace 'mybucket' with your bucket name)
mc mb minio/shop

# Set the bucket policy if needed (e.g., public)
mc anonymous set public minio/shop

exit 0
