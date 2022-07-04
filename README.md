# Yireo Rector rules & file processors

## Current status
Rules:
- `\Yireo\Rector\Rule\AddEmptyLineAtEndOfFile`: Working
- `\Yireo\Rector\Rule\DeclareStrictTypesOnOpeningLine`: Not working
- `\Yireo\Rector\Rule\RemoveDuplicateNewLines`: Not working

File processors:
- `Yireo\Rector\FileProcessor\DeclareStrictTypesOnFirstLineProcessor`: Working, but needs refactoring into rule
- `Yireo\Rector\FileProcessor\RemoveDuplicateNewLinesProcessor`: Working, but needs refactoring into rule

## Usage
Register the right rules and/or file processors:

File `rector.php`:
```php
use Yireo\Rector\Rule\AddEmptyLineAtEndOfFile;
use Yireo\Rector\FileProcessor\DeclareStrictTypesOnFirstLineProcessor;
use Yireo\Rector\FileProcessor\RemoveDuplicateNewLinesProcessor;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {    
    $rectorConfig->rule(AddEmptyLineAtEndOfFile::class);

    //$services = $rectorConfig->services();
    //$services->set(DeclareStrictTypesOnFirstLineProcessor::class);
    //$services->set(RemoveDuplicateNewLinesProcessor::class);
};
```

Usage:
```bash
vendor/bin/rector process src --dry-run
vendor/bin/rector process src
```