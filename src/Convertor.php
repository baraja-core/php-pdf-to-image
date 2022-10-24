<?php

declare(strict_types=1);

namespace Baraja\PdfToImage;


final class Convertor
{
	/** @throws \Error */
	public function __construct()
	{
		throw new \Error('Class ' . static::class . ' is static and cannot be instantiated.');
	}


	/**
	 * Convert first page of PDF to image and save to disk.
	 *
	 * @throws ConvertorException
	 */
	public static function convert(Configuration $configuration): void
	{
		try {
			self::process($configuration);
		} catch (\ImagickException $e) {
			throw new ConvertorException($e->getMessage(), $e->getCode(), $e);
		}
	}


	/**
	 * @throws \ImagickException
	 */
	private static function process(Configuration $configuration): void
	{
		if (class_exists('\Imagick') === false) {
			throw new \RuntimeException('Imagick is not installed.');
		}

		$im = new \Imagick($configuration->pdfPath);
		$im->setImageFormat($configuration->format);
		if ($configuration->cols !== null && $configuration->rows !== null) {
			$im->scaleImage($configuration->cols, $configuration->rows, $configuration->bestfit);
		}
		if ($configuration->trim) {
			$im->setImageBorderColor('rgb(255,255,255)');
			$im->trimImage(1);
		}
		self::write($configuration->savePath, (string) $im);
	}


	/**
	 * Writes a string to a file. Moved from nette/utils
	 *
	 * @throws ConvertorException
	 */
	private static function write(string $file, string $content, ?int $mode = 0_666): void
	{
		self::createDir(dirname($file));
		if (@file_put_contents($file, $content) === false) { // @ is escalated to exception
			throw new ConvertorException(sprintf('Unable to write file "%s": %s', $file, self::getLastError()));
		}
		if ($mode !== null && !@chmod($file, $mode)) { // @ is escalated to exception
			throw new ConvertorException(sprintf('Unable to chmod file "%s": %s', $file, self::getLastError()));
		}
	}


	/**
	 * Creates a directory. Moved from nette/utils
	 *
	 * @throws ConvertorException
	 */
	private static function createDir(string $dir, int $mode = 0_777): void
	{
		if (!is_dir($dir) && !@mkdir($dir, $mode, true) && !is_dir($dir)) { // @ - dir may already exist
			throw new ConvertorException(sprintf('Unable to create directory "%s": %s', $dir, self::getLastError()));
		}
	}


	private static function getLastError(): string
	{
		return (string) preg_replace('#^\w+\(.*?\): #', '', error_get_last()['message'] ?? '');
	}
}
