PDF to image convertor
======================

Convert PDF to image and save to disk. Convert PDF to JPG, PNG or GIF in PHP.

Install
-------

By Composer:

```shell
$ composer require baraja-core/php-pdf-to-image
```

Usage
-----

```php
$pdfPath = __DIR__ . '/example.pdf';
$imagePath = __DIR__ . '/example.jpg';

// Render PDF to image and save to disk.
\Baraja\PdfToImage\Convertor::convert($pdfPath, $imagePath, 'jpg');
```
