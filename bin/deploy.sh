#!/bin/bash
# Unpack secrets; -C ensures they unpack *in* the .travis directory
tar xvf .travis/secrets.tar -C .travis

# Setup SSH agent:
eval "$(ssh-agent -s)" #start the ssh agent
chmod 600 .travis/build-key.pem
ssh-add .travis/build-key.pem

# Setup git defaults:
git config --global user.email "github@mail.robert-kummer.de"
git config --global user.name "rokde"

# Add SSH-based remote to GitHub repo:
git remote add deploy git@github.com:donepm/cli-client.git
git fetch deploy

# Get box and build PHAR
curl -LSs https://box-project.github.io/box2/installer.php | php
php box.phar build -vv
# Without the following step, we cannot checkout the gh-pages branch due to
# file conflicts:
mv dpm.phar dpm.phar.tmp

# Checkout gh-pages and add PHAR file and version:
git checkout -b gh-pages deploy/gh-pages
mv dpm.phar.tmp dpm.phar
sha1sum dpm.phar > dpm.phar.version
git add dpm.phar dpm.phar.version

# Commit and push:
git commit -m 'Rebuilt phar'
git push deploy gh-pages:gh-pages
