<?php

namespace ShineUnited\ComposerBuildPlugin\Minify\Task;

use ShineUnited\ComposerBuild\Task\Task;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Composer\IO\IOInterface;
use MatthiasMullie\Minify\Css as Minifier;


class MinifyCssTask extends Task {

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

		$this->addOption(
			'embed-gif',
			null,
			InputOption::VALUE_NONE,
			'Embed referenced GIF image files',
			null
		);

		$this->addOption(
			'embed-png',
			null,
			InputOption::VALUE_NONE,
			'Embed referenced PNG image files',
			null
		);

		$this->addOption(
			'embed-jpeg',
			null,
			InputOption::VALUE_NONE,
			'Embed referenced JPEG image files',
			null
		);

		$this->addOption(
			'embed-tiff',
			null,
			InputOption::VALUE_NONE,
			'Embed referenced TIFF image files',
			null
		);

		$this->addOption(
			'embed-svg',
			null,
			InputOption::VALUE_NONE,
			'Embed referenced SVG image files',
			null
		);

		$this->addOption(
			'embed-xbm',
			null,
			InputOption::VALUE_NONE,
			'Embed referenced XBM image files',
			null
		);

		$this->addOption(
			'embed-font',
			null,
			InputOption::VALUE_NONE,
			'Embed referenced WOFF font files',
			null
		);

		$this->addOption(
			'max-embed-size',
			null,
			InputOption::VALUE_REQUIRED,
			'Maximum filesize (in kB) to embed',
			10
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

		$maxImportSize = $input->getOption('max-embed-size');
		$importExtensions = array();

		if($input->getOption('embed-gif')) {
			$importExtensions['gif'] = 'data:image/gif';
		}

		if($input->getOption('embed-png')) {
			$importExtensions['png'] = 'data:image/png';
		}

		if($input->getOption('embed-jpeg')) {
			$importExtensions['jpe']  = 'data:image/jpeg';
			$importExtensions['jpg']  = 'data:image/jpeg';
			$importExtensions['jpeg'] = 'data:image/jpeg';
		}

		if($input->getOption('embed-svg')) {
			$importExtensions['svg'] = 'data:image/svg+xml';
		}

		if($input->getOption('embed-tiff')) {
			$importExtensions['tif']  = 'image/tiff';
			$importExtensions['tiff'] = 'image/tiff';
		}

		if($input->getOption('embed-xbm')) {
			$importExtensions['xbm'] = 'image/x-xbitmap';
		}

		if($input->getOption('embed-font')) {
			$importExtensions['woff'] = 'data:application/x-font-woff';
		}

		$srcPaths = $input->getOption('src');
		if(!is_array($srcPaths)) {
			$srcPaths = array($srcPaths);
		}

		$firstPath = array_shift($srcPaths);

		$io->write('Creating CSS minifier', true, IOInterface::NORMAL);
		$io->write('Adding <info>' . $firstPath . '</info>', true, IOInterface::NORMAL);
		$minifier = new Minifier($firstPath);

		$minifier->setImportExtensions($importExtensions);
		$minifier->setMaxImportSize($maxImportSize);

		foreach($srcPaths as $srcPath) {
			$io->write('Adding <info>' . $srcPath . '</info>', true, IOInterface::NORMAL);
			$minifier->add($srcPath);
		}

		$io->write('Minifying CSS...', false, IOInterface::NORMAL);
		$output = $minifier->minify();
		$io->write('Complete', true, IOInterface::NORMAL);

		$destDirPath = dirname($destPath);
		if(!is_dir($destDirPath)) {
			$io->write('Creating directory <info>' . $destDirPath . '</info>', true, IOInterface::VERBOSE);
			mkdir($destDirPath, 0777, true);
		}

		$io->write('Writing minified CSS to <info>' . $destPath . '</info>', true, IOInterface::NORMAL);
		file_put_contents($destPath, $output);
	}
}
