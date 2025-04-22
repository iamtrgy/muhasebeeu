#!/bin/bash

# Set the directory to search in
SEARCH_DIR="/Users/turgayyilmaz/Desktop/muhasebeeu/resources/views"

# Find all blade files containing "py-8 mt-6" and replace with "py-8"
echo "Searching for files with 'py-8 mt-6' pattern..."
grep -l "py-8 mt-6" $(find $SEARCH_DIR -name "*.blade.php") | while read file; do
    echo "Updating file: $file"
    sed -i '' 's/py-8 mt-6/py-8/g' "$file"
done

echo "All updates completed!"
