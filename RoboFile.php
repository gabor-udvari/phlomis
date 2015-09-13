<?php
/**
 * This is Phlomis project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */

class RoboFile extends \Robo\Tasks
{
  private $vendorDir;
  private $assetPackages;

  /**
   * Contruct for the class, checks and creates the dist folders
   */
  public function __construct() {
    // check the build folder
    $buildDirs = array(
      'dist/styles',
      'dist/scripts',
      'dist/fonts'
    );
    $this->createPaths($buildDirs);

    // get the vendorDir
    $io = new Composer\IO\NullIO();
    $factory = new Composer\Factory();
    $composer = $factory->createComposer($io);
    $this->vendorDir = rtrim($composer->getConfig()->get('vendor-dir'), '/');

    // go through installed packages (taken from Composer\Command\ShowCommand.php)
    $installedRepo = $composer->getRepositoryManager()->getLocalRepository();
    $this->assetPackages = [];
    foreach ($installedRepo->getPackages() as $package) {
      if ($package->getType() == 'bower-asset-library' ) {
        // store the extra information for assets
        $this->assetPackages[$package->getPrettyName()] = $package;
      }
    }
  }

  private function createPaths($paths){
    // iterate through paths array and create folder
    foreach ($paths as $path) {
      // use Symfony 2 mkdir for recursive directory creation
      if (! is_dir($path)) {
        $this->_mkdir($path);
      }
    }
  }

  private function getAssetPath($packageName) {
    foreach ($this->assetPackages as $k => $package) {
      if ( strpos($k, $packageName) !== FALSE) {
        return $this->vendorDir .'/'. $k . '/';
      }
    }
  }

  private function getAssetMain($packageName) {
    foreach ($this->assetPackages as $k => $package) {
      if ( strpos($k, $packageName) !== FALSE) {
        $extra = $package->getExtra();
        $main = '';
        if (isset($extra['bower-asset-main'])) $main = $extra['bower-asset-main'];
        // check if single value is given, then return a string instead of an array
        if (is_array($main) && count($main) == 1){
          $main = $this->vendorDir.'/bower-asset/'.$packageName.'/'.$main[0];
        }
        return $main;
      }
    }
  }

  /**
   * Search and replace path dependencies, simple replacement for wiredep
   */
  private function pathDependencies() {
    // replace bower_asset directories
    $this->taskReplaceInFile('assets/styles/main.scss')
      ->from('../../bower_components')
      ->to('')
      ->run();
  }

  /**
   * Installation steps done after composer post-install
   * Copying the Sage files with rsync
   */
  public function install() {
    $this->taskRsync()
      ->fromPath($this->getAssetPath('sage'))
      ->toPath('./')
      ->recursive()
      ->exclude('.gitignore')
      ->exclude('README.md')
      // ->dryRun()
      // ->verbose()
      // ->stats()
      ->run();
  }

  /**
   * Main build step, included to be compatible with Sage gulp
   */
  public function build() {
    $this->styles();
    $this->scripts();
  }

  /** 
   * `gulp styles` - Compiles, combines, and optimizes Bower CSS and project CSS
   * By default this task will only log a warning if a precompiler error is
   * raised. If the `--production` flag is set: this task will fail outright.
   */
  public function styles() {
    // fix path issues
    $this->pathDependencies();

    $this->taskScss(
      [
        'assets/styles/main.scss' => 'dist/styles/main.css'
      ]
    )
    ->addImportPath('assets/styles')
    ->addImportPath('vendor/bower-asset')
    ->setFormatter('Leafo\ScssPhp\Formatter\Compressed')
    ->run();
  }

  /**
   * `gulp scripts` - Runs JSHint then compiles, combines, and optimizes Bower JS and project JS.
   */
  public function scripts() {
    $this->taskMinify('assets/scripts/main.js')
      ->to('dist/scripts/main.js')
      ->run();
    $this->taskMinify($this->getAssetMain('modernizr'))
      ->to('dist/scripts/modernizr.js')
      ->run();
  }

  /**
   * `gulp fonts` - Grabs all the fonts and outputs them in a flattened directory structure
   * See: https://github.com/armed/gulp-flatten
   */
  public function fonts() {
    $fonts = $this->getAssetPath('bootstrap').'assets/fonts/bootstrap/*';
    $this->taskFlattenDir($fonts)
      ->to('dist/fonts')
      ->run();
  }

  /** 
   * `gulp watch` - Use BrowserSync to proxy your dev server and synchronize code changes across devices.
   * Specify the hostname of your dev server at `manifest.config.devUrl`.
   * When a modification is made to an asset, run the
   * build step for that asset and inject the changes into the page.
   * @link http://www.browsersync.io
   */
  public function watch() {
    $this->taskWatch()
      ->monitor('assets/scripts/main.js', function() {
        $this->scripts();
      })
      ->monitor('assets/styles/main.scss', function() {
        $this->styles();
      })->run();
  }
}
