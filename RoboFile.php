<?php
/**
 * This is Phlomis project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */

class RoboFile extends \Robo\Tasks
{
  // TODO: once the Scss task pull-request is accepted this can be removed
  use MyScss;

  /**
   * Contruct for the class, checks and creates the dist folders
   */
  public function __construct() {
    // check the build folder
    if (! is_dir('dist')) {
      mkdir('dist');
    }
    if (! is_dir('dist/styles')) {
      mkdir('dist/styles');
    }
    if (! is_dir('dist/scripts')) {
      mkdir('dist/scripts');
    }
  }

  /**
   * Installation steps done after composer post-install
   * Copying the Sage files with rsync
   */
  public function install() {
    $this->taskRsync()
      ->fromPath('vendor/bower-asset/sage/')
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

    // TODO: once the Scss task pull-request is accepted this can be added
    // compile Scss to CSS
    // 'vendor/bower-asset/bootstrap-sass-official/assets/stylesheets/_bootstrap.scss' => 'compiled.css'
    // /*
    $this->taskMyScss(
      [
        'assets/styles/main.scss' => 'dist/styles/main.css'
      ]
    )
    ->compiler('myscss')
    ->run();
    // */

    // TODO: once the Scss task pull-request is accepted this can be added
    /*
    $this->taskScss(
      [
        'assets/styles/main.scss' => 'dist/styles/main.css'
      ]
    )
    ->addImportPath('assets/styles')
    ->addImportPath('vendor/bower-asset')
    ->setFormatter('Leafo\ScssPhp\Formatter\Compressed')
    ->run();
     */
  }

  /**
   * `gulp scripts` - Runs JSHint then compiles, combines, and optimizes Bower JS
   * and project JS.
   */
  public function scripts() {
    $this->taskMinify('assets/scripts/main.js')
      ->to('dist/scripts/main.js')
      ->run();
    $this->taskMinify('vendor/bower-asset/modernizr/modernizr.js')
      ->to('dist/scripts/modernizr.js')
      ->run();
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
}

trait MyScss
{
  function taskMyScss($input) {
    return new MyScssTask($input);
  }
}

class MyScssTask extends \Robo\Task\Assets\Less
{
  protected function myscss($file) {
    $scssCode = file_get_contents($file);
    $scss = new \Leafo\ScssPhp\Compiler();
    $scss->setImportPaths(
      [
        'assets/styles',
        'vendor/bower-asset'
      ]
    );
    return $scss->compile($scssCode);
  }

  protected function lessCompilers() {
    return [
      'lessphp', //https://github.com/leafo/lessphp
      'less', // https://github.com/oyejorge/less.php,
      'myless', // https://github.com/oyejorge/less.php, but with advanced options
      'myscss' // https://github.com/leafo/scssphp
    ];
  }
}
