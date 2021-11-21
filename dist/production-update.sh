#!/bin/bash
# Author: Andreas Geyer
# Version: 0.1.1
# Description:
# easy bash script to uplood
# new production bundle on qbitone server

# Detailed Description:
# Uses composer archive to create a production bundle.
# Uses unzip/zip to prevent update error in composer 'update-checker' package.
# Uses rsync to update a .zip and .json file wich is required
# for properly update in WP projects

__VERSION__="0.1.1"
__FILENAME__="production-update.sh"

user=p562944
server=qbitone.de
path="dist/"
file="${path}quaintenance"


if [[ $0 == "$path$__FILENAME__" ]]
then
    composer archive --format=zip --dir=. --file="$file"
    unzip "${file}.zip" -d "$file"
    rm -v "${file}.zip"
    (
        cd "$path" || { echo "Failure - Wrong Directory Or Does Not Exist"; exit 1; }
        zip -r "quaintenance.zip" "quaintenance"
    )
    rm -r "$file"
    rsync -avhczP --stats "${file}.zip" "${file}.json" $user@$server:/html/wordpress/
    rm "${file}.zip"
else
    echo "-------------------"
    echo "Run script from the WP root dir"
    echo ">>> $path$__FILENAME__"
    echo "b/c we need to run composer from here."
    echo "-------------------"
    exit 1
fi
