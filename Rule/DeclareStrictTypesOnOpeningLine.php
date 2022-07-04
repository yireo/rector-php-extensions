<?php declare(strict_types=1);

namespace Yireo\Rector\Rule;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\Expression;
use Rector\ChangesReporting\ValueObject\RectorWithLineChange;
use Rector\Core\Logging\CurrentRectorProvider;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class DeclareStrictTypesOnOpeningLine extends AbstractRector
{
    /**
     * @var CurrentRectorProvider
     */
    private $currentRectorProvider;

    public function __construct(
        CurrentRectorProvider $currentRectorProvider
    ) {
        $this->currentRectorProvider = $currentRectorProvider;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Declare_::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Move the declare(strict_types=1) to the first line', [
            new CodeSample(<<<'CODE_SAMPLE'
^&lt;?php
declare(strict_types=1);
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
^&lt;?php declare(strict_types=1);
CODE_SAMPLE
            )
        ]);
    }

    /**
     * @param Declare_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        if ($node->getLine() === 1) {
            return null;
        }

        $oldContent = $this->file->getFileContent();
        $newContent = $oldContent;
        $newContent = preg_replace('/declare(\s?)\((\s?)strict_types(\s?)=(\s?)1(\s?)\);(\s?)/m', '', $newContent);
        $newContent = preg_replace('/^<\?php/', '<?php declare(strict_types=1);', $newContent);
        $this->file->changeFileContent($newContent);
        $this->file->changeHasChanged(true);

        //$fileDiff = $this->fileDiffFactory->createFileDiff($file, $oldContent, $newContent);

        $currentRector = $this->currentRectorProvider->getCurrentRector();
        $rectorWithLineChange = new RectorWithLineChange(\get_class($currentRector), 1);
        $this->file->addRectorClassWithLine($rectorWithLineChange);

        //return new FileDiff($file->getRelativeFilePath(), $this->defaultDiffer->diff($oldContent, $newContent), $this->consoleDiffer->diff($oldContent, $newContent), $file->getRectorWithLineChanges());

        return $node;
    }

    private function shouldSkip(Declare_ $declare): bool
    {
        $declares = $declare->declares;
        foreach ($declares as $declare) {
            if ($this->isName($declare->key, 'strict_types')) {
                return false;
            }
        }
        return true;
    }
}