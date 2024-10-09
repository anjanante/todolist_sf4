<?php

namespace App\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class SubtimeFunction extends FunctionNode{
    protected $dateExpression = null;
    protected $intervalExpression = null;

    /**
     * Create format IDENTIFIER(dateExpression, intervalExpression)
     *
     * @param Parser $parser
     * @return void
     */
    public function parse(Parser $parser){
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->dateExpression = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_COMMA);

        $this->intervalExpression = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return 'SUBTIME('
        .$this->dateExpression->dispatch($sqlWalker)
        .', '
        .$this->intervalExpression->dispatch($sqlWalker)
        .')';
    }
}