<?php

declare(strict_types=1);

namespace Baraja\PdfToImage;


final class Configuration
{
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
	}


	public static function from(
		string $pdfPath,
		?string $savePath = null,
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
