<?php declare(strict_types=1);

namespace Yireo\Rector\Rule;

use PhpParser\Node;
use PhpParser\Node\Stmt\Nop;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RemoveDuplicateNewLines extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove duplicate new lines', [
            new CodeSample(<<<'CODE_SAMPLE'
class Example
{
    public function method1() {}
    
    
    public function method2() {}
}
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
class Example
{
    public function method1() {}
    
    public function method2() {}
}
CODE_SAMPLE
            )
        ]);
    }

    /**
     * @param Node $node
     */
    public function refactor(Node $node): ?Node
    {
        echo $node->getStartLine(). ' = '.get_class($node)."\n";

        // 37+38, 49, 53+54
        if (!$node instanceof Nop) {
            return null;
        }
        $line = $node->getLine();
        echo 'TEST:'.$line;
        return $node;

        $nextNode = $node->getAttribute(AttributeKey::NEXT_NODE);
        /*
        $nextNode = $node->getAttribute(AttributeKey::NEXT_NODE);
        if ($rawNode !== null || $nextNode !== null) {
            //return null;
        }*/

        //    private const NEW_LINE_REGEX = '#(\\r|\\n)#';


        //$this->removeNode($node);
        if (empty($node->stmts)) {
            return null;
        }

        $line = $node->getLine();

        $nextNode = $node->getAttribute(AttributeKey::NEXT_NODE);
        if (!$nextNode instanceof Node) {
            return null;
        }

        foreach ($node->stmts as $stmt) {
            echo $line.': '.get_class($stmt)."\n";
        }

        //$node->stmts[] = new Nop;

        return $node;
        $line = $node->getLine();
        $startLine = $node->getStartLine();
        $endLine = $node->getEndLine();

        return null;
    }
}