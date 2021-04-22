<?php

namespace ShineUnited\ComposerBuildPlugin\Minify\Task;

use ShineUnited\ComposerBuild\Task\Task;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Composer\IO\IOInterface;
use MatthiasMullie\Minify\JS as Minifier;


class MinifyJsTask extends Task {

	public function configure() {

		$this->addOption(
			'src', // name
			null,
			InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, // mode
			'Source file path(s)', // description
			null // default
		);

		$this->addOption(
			'dest', // name
			null,
			InputOption::VALUE_REQUIRED, // mode
			'Destination file path', // description
			null // default
		);
	}

	public function execute(InputInterface $input, OutputInterface $output) {
		$io = $this->getIO();

		$srcPaths = $input->getOption('src');
		$destPath = $input->getOption('dest');

		if(is_null($srcPaths) || empty($srcPaths)) {
			throw new \RuntimeException('One or more source paths must be defined');
		}

		if(is_null($destPath)) {
			throw new \RuntimeException('Destination path must be defined');
		}

		$srcPaths = $input->getOption('src');
		if(!is_array($srcPaths)) {
			$srcPaths = array($srcPaths);
		}

		$firstPath = array_shift($srcPaths);

		$io->write('Creating JS minifier', true, IOInterface::NORMAL);
		$io->write('Adding <info>' . $firstPath . '</info>', true, IOInterface::NORMAL);
		$minifier = new Minifier($firstPath);

		foreach($srcPaths as $srcPath) {
			$io->write('Adding <info>' . $srcPath . '</info>', true, IOInterface::NORMAL);
			$minifier->add($srcPath);
		}

		$io->write('Minifying JS...', false, IOInterface::NORMAL);
		$output = $minifier->minify();
		$io->write('Complete', true, IOInterface::NORMAL);

		$destDirPath = dirname($destPath);
		if(!is_dir($destDirPath)) {
			$io->write('Creating directory <info>' . $destDirPath . '</info>', true, IOInterface::VERBOSE);
			mkdir($destDirPath, 0777, true);
		}

		$io->write('Writing minified JS to <info>' . $destPath . '</info>', true, IOInterface::NORMAL);
		file_put_contents($destPath, $output);
	}
}
