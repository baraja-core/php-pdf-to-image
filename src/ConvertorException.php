<?php

declare(strict_types=1);

namespace Baraja\PdfToImage;


final class ConvertorException extends \Exception
{

	/**
	 * @param string $path
	 * @throws ConvertorException
	 */
	public static function fileDoesNotExist(string $path): void
	{
		throw new self('File "' . $path . '" does not exist.');
	}


	/**
	 * @param string $format
	 * @throws ConvertorException
	 */
	public static function unsupportedFormat(string $format): void
	{
		throw new self(
			'Format "' . $format . '" is not supported. '
			. 'Did you mean "' . implode('", "', Convertor::SUPPORTED_FORMATS) . '"?'
		);
	}


	/**
	 * @throws ConvertorException
	 */
	public static function imagicKIsNotInstalled(): void
	{
		throw new self('Imagick is not installed.');
	}
}