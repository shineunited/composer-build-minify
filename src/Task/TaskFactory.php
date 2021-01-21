<?php

namespace ShineUnited\ComposerBuildPlugin\Minify\Task;

use ShineUnited\ComposerBuild\Capability\TaskFactory as TaskFactoryCapability;


class TaskFactory implements TaskFactoryCapability {

	public function handlesType($type) {
		$type = preg_replace('/[-_\s]+/', '', $type);

		$types = array(
			'minifyjs',
			'jsminify',
			'minifycss',
			'cssminify'
		);

		return in_array($type, $types);
	}

	public function createTask($type, $name, array $config = array()) {
		$type = preg_replace('/[-_\s]+/', '', $type);

		switch($type) {
			case 'minifyjs':
			case 'jsminify':
				return new MinifyJsTask($name, $config);
			case 'minifycss':
			case 'cssminify':
				return new MinifyCssTask($name, $config);
			default:
				return false;
		}
	}
}
