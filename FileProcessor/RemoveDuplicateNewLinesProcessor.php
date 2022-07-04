<?php declare(strict_types=1);

namespace Yireo\Rector\FileProcessor;

use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\Differ\DefaultDiffer;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Reporting\FileDiff;

final class RemoveDuplicateNewLinesProcessor implements FileProcessorInterface
{
    public function supports(File $file, Configuration $configuration): bool
    {
        return true;
    }

    /**
     * @param File $file
     * @param Configuration $configuration
     * @return array
     */
    public function process(File $file, Configuration $configuration): array
    {
        $oldContent = $file->getFileContent();
        $newContent = $oldContent;
        $newContent = preg_replace("/(\r?\n){2,}/", "\n\n", $newContent);
        $file->changeFileContent($newContent);

        $defaultDiffer = new DefaultDiffer();
        $fileDiff = new FileDiff(
            $file->getRelativeFilePath(),
            $defaultDiffer->diff($oldContent, $newContent),
            $defaultDiffer->diff($oldContent, $newContent),
            $file->getRectorWithLineChanges());

        return [$fileDiff];
    }

    public function getSupportedFileExtensions(): array
    {
        return ['php'];
    }
}