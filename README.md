# Mixnode WARC Reader for PHP
This library allows developers to read Web ARChive (WARC) files in PHP.

## Installation Guide

We recommend [Composer](http://getcomposer.org) for installing this package:

```bash
curl -sS https://getcomposer.org/installer | php
```

Once done, run the Composer command to install Mixnode WARC Reader for PHP:

```bash
php composer.phar require mixnode/mixnode-warcreader-php
```

After installing, you need to require Composer's autoloader in your code:

```php
require 'vendor/autoload.php';
```

You can then later update Mixnode WARC Reader using composer:

 ```bash
composer.phar update
 ```

## A Simple Example

```php
<?php
require 'vendor/autoload.php';

// Initialize a WarcReader object 
// The WarcReader constructure accepts paths to both raw WARC files and GZipped WARC files
$warc_reader = new Mixnode\WarcReader("test.warc.gz");

// Using nextRecord, iterate through the WARC file and output each record.
while(($record = $warc_reader->nextRecord()) != FALSE){
	// A WARC record is broken into two parts: header and content.
	// header contains metadata about content, while content is the actual resource captured.
	print_r($record['header']);
	print_r($record['content']);
	echo "------------------------------------\n";
}
```
