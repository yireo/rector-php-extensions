<?php declare(strict_types=1);

namespace Yireo\Rector\Rule;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Nop;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\Application\File;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class AddEmptyLineAtEndOfFile extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add empty file at end of file', [
            new CodeSample(<<<'CODE_SAMPLE'
class Example
{
}
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
class Example
{
}

CODE_SAMPLE
            )
        ]);
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $nextNode = $node->getAttribute(AttributeKey::NEXT_NODE);
        if ($nextNode instanceof Node) {
            return null;
        }

        if ($this->hasDoubleLastLine($this->file)) {
            return null;
        }

        $this->nodesToAddCollector->addNodeAfterNode(new Nop(), $node);

        return $node;
    }

    /**
     * @param File $file
     * @return string
     */
    private function hasDoubleLastLine(File $file): bool
    {
        $content = $file->getFileContent();
        $lines = explode("\n", $content);
        $lastLine = end($lines);
        $secondLastLine = prev($lines);

        return (empty($lastLine) && empty($secondLastLine));
    }
}