<?php

declare(strict_types=1);

namespace Baraja\PdfToImage;


final class Configuration
{
	public const
		FormatJpg = 'jpg',
		FormatPng = 'png',
		FormatGif = 'gif';

	public const SupportedFormats = [self::FormatJpg, self::FormatPng, self::FormatGif];


	public function __construct(
		public string $pdfPath,
		public string $savePath,
		public string $format = 'jpg',
		public bool $trim = false,
		public ?int $cols = null,
		public ?int $rows = null,
		public bool $bestfit = false
	) {
		$this->format = strtolower($format);
		if (in_array($this->format, self::SupportedFormats, true) === false) {
			throw new \InvalidArgumentException(
				sprintf(
					'Format "%s" is not supported. Did you mean "%s"?',
					$this->format,
					implode('", "', self::SupportedFormats),
				)
			);
		}
		if (is_file($pdfPath) === false) {
			throw new ConvertorException(sprintf('File "%s" does not exist.', $pdfPath));
		}
	}


	public static function from(
		string $pdfPath,
		string $savePath,
		string $format = 'jpg',
		bool $trim = false,
	): self {
		return new self(
			pdfPath: $pdfPath,
			savePath: $savePath,
			format: $format,
			trim: $trim,
		);
	}
}
