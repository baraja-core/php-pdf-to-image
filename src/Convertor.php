<?php

declare(strict_types=1);

namespace Baraja\PdfToImage;


final class Convertor
{
	public const FORMAT_JPG = 'jpg';

	public const FORMAT_PNG = 'png';

	public const FORMAT_GIF = 'gif';

	public const SUPPORTED_FORMATS = [self::FORMAT_JPG, self::FORMAT_PNG, self::FORMAT_GIF];


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
	public static function convert(string $pdfPath, string $savePath, string $format = 'jpg', bool $trim = false): void
	{
		if (\in_array($format = strtolower($format), self::SUPPORTED_FORMATS, true) === false) {
			throw new \InvalidArgumentException(
				'Format "' . $format . '" is not supported. '
				. 'Did you mean "' . implode('", "', self::SUPPORTED_FORMATS) . '"?',
			);
		}
		if (\is_file($pdfPath) === false) {
			throw new ConvertorException('File "' . $pdfPath . '" does not exist.');
		}
		try {
			$im = self::process($pdfPath, $savePath);
			if ($trim === true) {
				$im->setImageBorderColor('rgb(255,255,255)');
				$im->trimImage(1);
				self::write($savePath, (string) $im);
			}
		} catch (\ImagickException $e) {
			throw new ConvertorException($e->getMessage(), $e->getCode(), $e);
		}
	}


	/**
	 * @throws \ImagickException
	 */
	private static function process(string $pdfPath, string $savePath): \Imagick
	{
		if (class_exists('\Imagick') === false) {
			throw new \RuntimeException('Imagick is not installed.');
		}

		$im = new \Imagick($pdfPath);
		$im->setImageFormat('jpg');
		self::write($savePath, (string) $im);

		return $im;
	}


	/**
	 * Writes a string to a file. Moved from nette/utils
	 *
	 * @throws ConvertorException
	 */
	private static function write(string $file, string $content, ?int $mode = 0_666): void
	{
		static::createDir(dirname($file));
		if (@file_put_contents($file, $content) === false) { // @ is escalated to exception
			throw new ConvertorException('Unable to write file "' . $file . '": ' . self::getLastError());
		}
		if ($mode !== null && !@chmod($file, $mode)) { // @ is escalated to exception
			throw new ConvertorException('Unable to chmod file "' . $file . '": ' . self::getLastError());
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
			throw new ConvertorException('Unable to create directory "' . $dir . '": ' . self::getLastError());
		}
	}


	private static function getLastError(): string
	{
		return (string) preg_replace('#^\w+\(.*?\): #', '', error_get_last()['message'] ?? '');
	}
}
