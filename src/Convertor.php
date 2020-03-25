<?php

declare(strict_types=1);

namespace Baraja\PdfToImage;


final class Convertor
{
	public const FORMAT_JPG = 'jpg';

	public const FORMAT_PNG = 'png';

	public const FORMAT_GIF = 'gif';

	public const SUPPORTED_FORMATS = [self::FORMAT_JPG, self::FORMAT_PNG, self::FORMAT_GIF];


	/**
	 * @throws \Error
	 */
	public function __construct()
	{
		throw new \Error('Class ' . get_class($this) . ' is static and cannot be instantiated.');
	}


	/**
	 * Convert first page of PDF to image and save to disk.
	 *
	 * @param string $pdfPath
	 * @param string $savePath
	 * @param string $format
	 * @param bool $trim
	 * @throws ConvertorException
	 */
	public static function convert(string $pdfPath, string $savePath, string $format = 'jpg', bool $trim = false): void
	{
		if (\in_array($format = strtolower($format), self::SUPPORTED_FORMATS, true) === false) {
			ConvertorException::unsupportedFormat($format);
		}

		if (\is_file($pdfPath) === false) {
			ConvertorException::fileDoesNotExist($pdfPath);
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
	 * @param string $pdfPath
	 * @param string $savePath
	 * @return \Imagick
	 * @throws ConvertorException|\ImagickException
	 */
	private static function process(string $pdfPath, string $savePath): \Imagick
	{
		if (class_exists('\Imagick') === false) {
			ConvertorException::imagicKIsNotInstalled();
		}

		$im = new \Imagick($pdfPath);
		$im->setImageFormat('jpg');
		self::write($savePath, (string) $im);

		return $im;
	}


	/**
	 * Writes a string to a file.
	 * Moved from nette/utils
	 *
	 * @param string $file
	 * @param string $content
	 * @param int|null $mode
	 * @throws ConvertorException
	 */
	private static function write(string $file, string $content, ?int $mode = 0666): void
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
	 * Creates a directory.
	 * Moved from nette/utils
	 *
	 * @param string $dir
	 * @param int $mode
	 * @throws ConvertorException
	 */
	private static function createDir(string $dir, int $mode = 0777): void
	{
		if (!is_dir($dir) && !@mkdir($dir, $mode, true) && !is_dir($dir)) { // @ - dir may already exist
			throw new ConvertorException('Unable to create directory "' . $dir . '": ' . self::getLastError());
		}
	}


	/**
	 * Moved from nette/utils
	 *
	 * @return string
	 */
	private static function getLastError(): string
	{
		return (string) preg_replace('#^\w+\(.*?\): #', '', error_get_last()['message']);
	}
}