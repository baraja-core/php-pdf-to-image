PDF to image convertor
======================

Convert PDF to image and save to disk. Convert PDF to JPG, PNG or GIF in PHP.

📦 Installation
---------------

It's best to use [Composer](https://getcomposer.org) for installation, and you can also find the package on
[Packagist](https://packagist.org/packages/baraja-core/php-pdf-to-image) and
[GitHub](https://github.com/baraja-core/php-pdf-to-image).

To install, simply use the command:

```shell
$ composer require baraja-core/php-pdf-to-image
```

You can use the package manually by creating an instance of the internal classes, or register a DIC extension to link the services directly to the Nette Framework.

How to use
----------

```php
$pdfPath = __DIR__ . '/example.pdf';
$imagePath = __DIR__ . '/example.jpg';

// Render PDF to image and save to disk.
\Baraja\PdfToImage\Convertor::convert($pdfPath, $imagePath, 'jpg');
```

📄 License
-----------

`baraja-core/php-pdf-to-image` is licensed under the MIT license. See the [LICENSE](https://github.com/baraja-core/template/blob/master/LICENSE) file for more details.
