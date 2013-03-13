#!/bin/bash

# this is for cleaning up all images files in webroot/uploads
rm *.epub
cd ../src
zip -0Xq STORYZER-intl.epub mimetype
zip -Xr9Dq STORYZER-intl.epub OEBPS
zip -Xr9Dq STORYZER-intl.epub META-INF
mv STORYZER-intl.epub ../build
cd ../build
