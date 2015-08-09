# Phlomis

Phlomis is a project for replacing the Node.js based build tools of [Sage](https://github.com/roots/sage) to a PHP based one with [Composer](https://getcomposer.org/) and [Robo](http://codegyre.github.io/Robo/).

The name comes from the genus of plants called [Phlomis](https://en.wikipedia.org/wiki/Phlomis). One of the members of the genus is Phlomis russeliana which has the common name Turkish sage. That and the "Ph" at the beginning makes it a perfect name for a PHP oriented fork of Sage.

## Requirements

| Prerequisite    | How to check | How to install
| --------------- | ------------ | ------------- |
| PHP >= 5.4.x    | `php -v`     | [php.net](http://php.net/manual/en/install.php) |

## Installation

__TODO__ a simple `composer install` should handle all this

- Clone the repository
- Download Sage (either master or one of the releases)
- Extract Sage files to the root directory of the repo, but _do not_ overwrite any of the already existing files such as:
  - .gitignore
  - README.md
- Install the required packages with Composer

```
git clone git@github.com:gabor-udvari/phlomis.git
wget https://github.com/roots/sage/archive/8.2.1.zip -O sage.zip
unzip -l sage.zip | sed -n 's/^[^a-z]*\(.*\/.*\)$/\1/p' | while read f; do unzip -qjn -d "$(echo $f | sed 's/^[^\/]*\(.*\)[\/].*$/phlomis\1/')" sage.zip "$f"; done
cd phlomis
composer.phar install
```

## Usage

__TODO__ only `styles` work currently

You can use all the original Gulp commands just like you used with Sage, except these are now Robo commands:

- `vendor/bin/robo build`
- `vendor/bin/robo watch`

## Keeping up to date with Sage

You can either download the latest zip of Sage and sync everything with rsync

```
wget https://github.com/roots/sage/archive/master.zip -O sage.zip
unzip -q sage.zip
rsync -av --exclude '.git*' --exclude 'README.md' sage-master/ phlomis/
```

Or you can add roots/sage as an upstream remote with git and merge

```
cd phlomis
git remote add upstream https://github.com/roots/sage.git
git fetch upstream master
git merge master upstream/master
```
