# PHP PDF to Image

A lightweight PHP library for converting PDF documents to image files. Supports JPG, PNG, and GIF output formats with optional image scaling and whitespace trimming.

## Key Principles

- **Simple static API** - Single method call to convert PDF to image
- **Multiple formats** - Supports JPG, PNG, and GIF output formats
- **Image manipulation** - Optional scaling and whitespace trimming built-in
- **Zero dependencies** - Only requires PHP 8.0+ and the Imagick extension
- **Auto-directory creation** - Automatically creates output directories if they don't exist
- **Strict typing** - Fully typed with PHP 8 strict mode enabled

## Architecture

The library follows a clean, minimal architecture with three main components:

```
┌─────────────────────────────────────────────────────────────┐
│                      User Application                       │
└─────────────────────────────┬───────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                     Configuration                            │
│  ┌─────────────────────────────────────────────────────┐    │
│  │ • pdfPath      - Source PDF file path               │    │
│  │ • savePath     - Output image file path             │    │
│  │ • format       - Output format (jpg/png/gif)        │    │
│  │ • trim         - Remove whitespace borders          │    │
│  │ • cols/rows    - Scale dimensions                   │    │
│  │ • bestfit      - Maintain aspect ratio on scale     │    │
│  └─────────────────────────────────────────────────────┘    │
└─────────────────────────────┬───────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                       Convertor                              │
│  ┌─────────────────────────────────────────────────────┐    │
│  │ convert(Configuration): void                        │    │
│  │   ├── Validates Imagick availability                │    │
│  │   ├── Loads PDF via Imagick                         │    │
│  │   ├── Applies format conversion                     │    │
│  │   ├── Applies optional scaling                      │    │
│  │   ├── Applies optional trimming                     │    │
│  │   └── Writes output file to disk                    │    │
│  └─────────────────────────────────────────────────────┘    │
└─────────────────────────────┬───────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                   ConvertorException                         │
│  Custom exception for all conversion-related errors          │
└─────────────────────────────────────────────────────────────┘
```

### Components

| Component | Description |
|-----------|-------------|
| `Configuration` | Immutable value object that holds all conversion parameters. Validates input on construction and provides format constants. |
| `Convertor` | Static utility class that performs the actual PDF-to-image conversion using Imagick. Cannot be instantiated. |
| `ConvertorException` | Custom exception thrown when conversion fails (file not found, write errors, Imagick errors). |

## 📦 Installation

It's best to use [Composer](https://getcomposer.org) for installation, and you can also find the package on
[Packagist](https://packagist.org/packages/baraja-core/php-pdf-to-image) and
[GitHub](https://github.com/baraja-core/php-pdf-to-image).

To install, simply use the command:

```shell
$ composer require baraja-core/php-pdf-to-image
```

### Requirements

- **PHP 8.0** or higher
- **Imagick extension** (`ext-imagick`)

#### Installing Imagick

On Debian/Ubuntu:

```shell
$ sudo apt-get install php-imagick
```

On macOS with Homebrew:

```shell
$ brew install imagemagick
$ pecl install imagick
```

Make sure Ghostscript is also installed for PDF processing:

```shell
# Debian/Ubuntu
$ sudo apt-get install ghostscript

# macOS
$ brew install ghostscript
```

## Basic Usage

### Simple Conversion

The most straightforward way to convert a PDF to an image:

```php
use Baraja\PdfToImage\Configuration;
use Baraja\PdfToImage\Convertor;

$configuration = new Configuration(
    pdfPath: '/path/to/document.pdf',
    savePath: '/path/to/output.jpg',
    format: 'jpg'
);

Convertor::convert($configuration);
```

### Using the Factory Method

You can also use the static factory method for configuration:

```php
use Baraja\PdfToImage\Configuration;
use Baraja\PdfToImage\Convertor;

$configuration = Configuration::from(
    pdfPath: '/path/to/document.pdf',
    savePath: '/path/to/output.png',
    format: 'png',
    trim: true
);

Convertor::convert($configuration);
```

### Converting with Image Scaling

To resize the output image during conversion:

```php
use Baraja\PdfToImage\Configuration;
use Baraja\PdfToImage\Convertor;

$configuration = new Configuration(
    pdfPath: '/path/to/document.pdf',
    savePath: '/path/to/thumbnail.jpg',
    format: 'jpg',
    cols: 800,    // Width in pixels
    rows: 600,    // Height in pixels
    bestfit: true // Maintain aspect ratio
);

Convertor::convert($configuration);
```

### Trimming Whitespace

To automatically remove white borders from the resulting image:

```php
use Baraja\PdfToImage\Configuration;
use Baraja\PdfToImage\Convertor;

$configuration = new Configuration(
    pdfPath: '/path/to/document.pdf',
    savePath: '/path/to/trimmed.png',
    format: 'png',
    trim: true
);

Convertor::convert($configuration);
```

### Full Example with Error Handling

```php
use Baraja\PdfToImage\Configuration;
use Baraja\PdfToImage\Convertor;
use Baraja\PdfToImage\ConvertorException;

try {
    $configuration = new Configuration(
        pdfPath: __DIR__ . '/documents/report.pdf',
        savePath: __DIR__ . '/images/report-preview.jpg',
        format: 'jpg',
        trim: true,
        cols: 1200,
        rows: 900,
        bestfit: true
    );

    Convertor::convert($configuration);

    echo 'PDF converted successfully!';
} catch (ConvertorException $e) {
    echo 'Conversion failed: ' . $e->getMessage();
}
```

## Configuration Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `pdfPath` | `string` | *required* | Absolute or relative path to the source PDF file. The file must exist. |
| `savePath` | `string` | *required* | Path where the output image will be saved. Parent directories are created automatically. |
| `format` | `string` | `'jpg'` | Output image format. Must be one of: `jpg`, `png`, `gif`. |
| `trim` | `bool` | `false` | When `true`, removes white borders from the resulting image. |
| `cols` | `int\|null` | `null` | Target width in pixels for scaling. Must be set together with `rows`. |
| `rows` | `int\|null` | `null` | Target height in pixels for scaling. Must be set together with `cols`. |
| `bestfit` | `bool` | `false` | When `true` and scaling is enabled, maintains aspect ratio (image may be smaller than specified dimensions). |

### Supported Output Formats

The library provides constants for supported formats:

```php
use Baraja\PdfToImage\Configuration;

Configuration::FormatJpg;  // 'jpg'
Configuration::FormatPng;  // 'png'
Configuration::FormatGif;  // 'gif'

Configuration::SupportedFormats; // ['jpg', 'png', 'gif']
```

## How It Works

1. **Configuration Validation** - When you create a `Configuration` object, it validates:
   - The PDF file exists at the specified path
   - The output format is one of the supported formats (jpg, png, gif)
   - Format strings are normalized to lowercase

2. **Conversion Process** - When `Convertor::convert()` is called:
   - Verifies the Imagick extension is available
   - Loads the first page of the PDF using Imagick
   - Sets the output image format
   - Applies scaling if `cols` and `rows` are specified
   - Applies trimming if enabled (removes white borders with 1px tolerance)
   - Creates the output directory if it doesn't exist
   - Writes the image to disk with `0666` permissions

3. **Error Handling** - All errors are wrapped in `ConvertorException`:
   - File not found errors
   - Imagick processing errors
   - File write permission errors
   - Directory creation errors

## Important Notes

- **First Page Only** - The converter processes only the first page of multi-page PDF documents
- **Memory Usage** - Large PDF files or high-resolution outputs may require significant memory. Adjust PHP's `memory_limit` if needed
- **Ghostscript** - Imagick requires Ghostscript to be installed for PDF processing
- **Static Class** - The `Convertor` class is static and cannot be instantiated

## Author

**Jan Barášek** - [https://baraja.cz](https://baraja.cz)

## 📄 License

`baraja-core/php-pdf-to-image` is licensed under the MIT license. See the [LICENSE](https://github.com/baraja-core/php-pdf-to-image/blob/master/LICENSE) file for more details.
