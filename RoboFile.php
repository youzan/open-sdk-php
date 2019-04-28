<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{
    // define public methods as commands
    
    /**
	 * Creates release zip
	 *
	 * @param string $version Version to build.
	 */
	public function release( $version = 'dev-master' ) {
        $package = "youzanyun/open-sdk";
		list( $vendor, $name ) = explode( '/', $package );
		if ( empty( $vendor ) || empty( $name ) ) {
			return;
		}
		$this->_mkdir( 'release' );
		$this->taskExec( "composer create-project {$package} {$name} {$version}" )
		     ->dir( __DIR__ . '/release' )
		     ->arg( '--prefer-dist' )
		     ->arg( '--no-dev' )
		     ->run();
		$this->taskExec( 'composer remove composer/installers --update-no-dev' )
		     ->dir( __DIR__ . "/release/{$name}" )
		     ->run();
		$this->taskExec( 'composer dump-autoload --optimize' )
		     ->dir( __DIR__ . "/release/{$name}" )
		     ->run();
		$zipFile = "release/{$vendor}-{$name}.zip";
		$this->_remove( $zipFile );
		$this->taskPack( $zipFile )
		     ->addDir( $name, "release/{$name}" )
		     ->run();
		if ( ! empty( $name ) ) {
			$this->_deleteDir( "release/{$name}" );
		}
	}

}