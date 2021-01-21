<?php

namespace ShineUnited\ComposerBuildPlugin\Minify;

use ShineUnited\ComposerBuildPlugin\Minify\Task\TaskFactory;

use ShineUnited\ComposerBuild\Capability\TaskFactory as TaskFactoryCapability;
use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Plugin\Capable;


class Plugin implements PluginInterface, Capable {

	public function activate(Composer $composer, IOInterface $io) {
		// do nothing
	}

	public function deactivate(Composer $composer, IOInterface $io) {
		// do nothing
	}

	public function uninstall(Composer $composer, IOInterface $io) {
		// do nothing
	}

	public function getCapabilities() {
		return array(
			TaskFactoryCapability::class => TaskFactory::class
		);
	}
}
