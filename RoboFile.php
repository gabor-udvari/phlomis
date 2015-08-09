<?php
/**
 * This is Phlomis project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{
    use MyScss;

    public function __construct () {
        // check the build folder
        if ( ! is_dir('dist') ) {
            mkdir('dist');
        }
        if ( ! is_dir('dist/styles') ) {
            mkdir('dist/styles');
        }
    }

    public function install () {
        // additional installation steps done after composer post-install
        $this->taskRsync()
            ->fromPath('vendor/roots/sage/')
            ->toPath('./')
            ->recursive()
            ->exclude('.gitignore') 
            ->exclude('README.md') 
            ->dryRun()
            ->verbose()
            ->stats()
            ->run();
    }

    // ### Styles
    // `gulp styles` - Compiles, combines, and optimizes Bower CSS and project CSS.
    // By default this task will only log a warning if a precompiler error is
    // raised. If the `--production` flag is set: this task will fail outright.
    public function styles () {
        // compile LESS to CSS
        // 'vendor/bower-asset/bootstrap-sass-official/assets/stylesheets/_bootstrap.scss' => 'compiled.css'
        $this->taskMyScss([
            'assets/styles/main.scss' => 'dist/styles/main.css'
        ])
        ->compiler('myscss')
        ->run();

        /*
        // concat compiled CSS with info CSS
        $this->taskConcat([
            '_src_/css/udionline-less/info.less',
            '_src_/css/compiled.css'
        ])
        ->to('style.css')
        ->run();

        // remove temporary CSS
        $this->taskFileSystemStack()
            ->remove('_src_/css/compiled.css')
            ->run();
         */
    }
}

trait MyScss
{
    function taskMyScss($input)
    {
        return new MyScssTask($input);
    }
}

class MyScssTask extends \Robo\Task\Assets\Less
{
    protected function myscss($file)
    {
        $scssCode = file_get_contents($file);
        /*
        $parser = new \Less_Parser();
        $directories = array(
            '/var/www/udionline/wp-content/themes/udionline/_src_/css/udionline-less'=>'../udionline-less',
            '/var/www/udionline/wp-content/themes/udionline/_src_/css/less'=>'./'
        );
        $options = array( 'import_callback'=>'import_callback_function' );
         */
        $scss = new \Leafo\ScssPhp\Compiler();
        $scss->setImportPaths([
            'vendor/bower-asset/bootstrap-sass-official/assets/stylesheets'
        ]);
        /*
        $scss->addImportPath(function($path) {
            $file = basename($path);
            $dir = 'vendor/bower-asset/bootstrap-sass-official/assets/stylesheets/bootstrap';
            if (!file_exists($dir . '/_'.$file.'.scss')) { 
                return null;
            }
            return $dir . '/_'.$file.'.scss';
        });
         */
        // $parser->SetImportDirs( $directories );
        //$parser->parse($lessCode);
        // $parser->parseFile($file);
        return $scss->compile($scssCode);
    }

    protected function lessCompilers()
    {
        return [
            'lessphp', //https://github.com/leafo/lessphp
            'less', // https://github.com/oyejorge/less.php,
            'myless', // https://github.com/oyejorge/less.php, but with advanced options
            'myscss' // https://github.com/leafo/scssphp
        ];
    }
}
