# Phlomis

Phlomis is a project for replacing the Node.js based build tools of [Sage](https://github.com/roots/sage) to a PHP based one with [Composer](https://getcomposer.org/), [Composer Asset Plugin](https://github.com/fxpio/composer-asset-plugin) and [Robo](http://codegyre.github.io/Robo/).

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
| PHP >= 5.6.x    | `php -v`     | [php.net](http://php.net/manual/en/install.php) |
| Composer >= 1.0 | `composer.phar --version` | [getcomposer.org](https://getcomposer.org/download/) |
| rsync >= 3.1.0	| `rsync --version` | [rsync.samba.org](https://rsync.samba.org/) |

## Installation

- Clone the repository
- Step inside the local folder
- Install the dependencies with Composer, then run update right after it

**Note**: the update after the first installation is required because [fxp/composer-asset-plugin](https://github.com/fxpio/composer-asset-plugin) is required for installing the actual Bower packages. The first installation will install fxp/composer-asset-plugin, then the second time it can install all the Bower packages.

```
git clone git@github.com:gabor-udvari/phlomis.git
cd phlomis
composer.phar install
composer.phar update
```

## Usage

You can use all the original Gulp commands just like you used with Sage, except these are now Robo commands:

- `vendor/bin/robo build`
- `vendor/bin/robo watch`

## Keeping up to date with Sage

Just edit your `composer.json`, increase the version number for Sage or put dev-master there and update with Composer:

```
sed -i '/bower-asset\/sage/ s/8\.4\.2/dev-master/' composer.json
composer.phar update
```

There are no issues with 8.2, 8.3, 8.4 releases, but 8.5 still refers to Bootstrap 4 beta. To resolve this, you can force the stable Bootstrap 4 version with fxp/composer-asset-plugin like this:
```
"config": {
	"fxp-asset": {
		"resolutions": {
			"bower-asset/bootstrap": "^4.0.0"
		}
	}
}
```

## Comparison with Sage

### Build process comparison

<table>
<tr>
	<th width="50%">Sage
	<th width="50%">Phlomis
<tr>
	<td>
		<strong>bower</strong>: download packages natively:
		<ul>
			<li>modernizr
			<li>bootstrap-sass-official
		</ul>
		And put them into bower_components
	<td>
	  <strong>composer</strong>: download packages with <a href="https://github.com/fxpio/composer-asset-plugin">composer-&#8203;asset-plugin</a>:
		<ul>
			<li>modernizr
			<li>bootstrap-sass-official
		</ul>
		And put them into vendor/bower-assets
<tr>
<td>
<strong>gulp</strong>: download modules natively
<td>
<strong>Robo</strong>: modules are either built-in to Robo or shipped with Phlomis
<tr>
<td>
<strong>gulp</strong>: insert dependency paths with wiredep
<td>
<strong>Robo</strong>: no task for depency insertion at this time
<tr>
<td>
<strong>gulp</strong>: copy fonts with gulp-flatten
<td>
<strong>Robo</strong>: FlattenDir task is built-in
<tr>
<td>
<strong>gulp</strong>: compile Scss with gulp-sass
<td>
<strong>Robo</strong>: compile Scss with leafo/scssphp
<tr>
<td>
<strong>gulp</strong>: minifying JS with UglifyJS
<td>
<strong>Robo</strong>: minifying JS with JSQueeze
<tr>
<td>
<strong>gulp</strong>: minifying images with gulp-imagemin
<td>
<strong>Robo</strong>: ImageMinify task is built in
<tr>
<td>
<strong>gulp</strong>: watching files is built-in
<td>
<strong>Robo</strong>: watching files is built-in
<tr>
<td>
<strong>gulp</strong>: updating browsers with Browserify
<td>
<strong>Robo</strong>: updating browsers not implemented yet, possible candidate: <a href="https://github.com/TomBZombie/NoF5">TomBZombie/NoF5</a>
</table>

## Tracking work required in upstream projects

- Scss task in Robo: [done](https://github.com/Codegyre/Robo/pull/200)
- Overriding main files in composer-asset-plugin: [done](https://github.com/francoispluchino/composer-asset-plugin/pull/143)
- FlattenDir task in Robo: [done](https://github.com/Codegyre/Robo/pull/215)
- ImageMinify task in Robo: [done](https://github.com/Codegyre/Robo/pull/228)
