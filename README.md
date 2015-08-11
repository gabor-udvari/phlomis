# Phlomis

Phlomis is a project for replacing the Node.js based build tools of [Sage](https://github.com/roots/sage) to a PHP based one with [Composer](https://getcomposer.org/) and [Robo](http://codegyre.github.io/Robo/).

The name comes from the genus of plants called [Phlomis](https://en.wikipedia.org/wiki/Phlomis). One of the members of the genus is Phlomis russeliana which has the common name Turkish sage. That and the "Ph" at the beginning makes it a perfect name for a PHP oriented fork of Sage.

## Table of contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Keeping up to date with Sage](#keeping-up-to-date-with-sage)
- [Comparison with Sage](#comparison-with-sage)

## Requirements

| Prerequisite    | How to check | How to install
| --------------- | ------------ | ------------- |
| PHP >= 5.4.x    | `php -v`     | [php.net](http://php.net/manual/en/install.php) |
| Composer >= 1.0-dev  | `composer.phar --version` | [getcomposer.org](https://getcomposer.org/download/) |

## Installation

- Clone the repository
- Step inside the local folder
- Install the dependencies with Composer

```
git clone git@github.com:gabor-udvari/phlomis.git
cd phlomis
composer.phar install
```

## Usage

__TODO__ only `styles` works currently

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

## Comparison with Sage

### Build process comparison

<table>
<tr>
	<th>Sage
	<th>Phlomis
<tr>
	<td>
		<strong>bower</strong>: download packages natively:
		<ul>
			<li>modernizr: 2.8.2
			<li>bootstrap-sass-official: 3.3.5
		</ul>
		And put them into bower_components
	<td>
	  <strong>composer</strong>: download packages with composer-asset-plugin:
		<ul>
			<li>modernizr: 2.8.2
			<li>bootstrap-sass-official: 3.3.5
		</ul>
		And put them into vendor/bower-assets
<tr>
<td>
<strong>gulp</strong>: download modules natively
<td>
<strong>Robo</strong>: modules are part of Phlomis (until they are merged with upstream)
<tr>
<td>
<strong>gulp</strong>: insert dependency paths with wiredep
<tr>
<td>
<strong>Robo</strong>: no depency path insertion at this time
</table>
