<?php
global $where_col, $orderby, $in_started, $count;
$where_col = false;
$orderby = false;
$in_started = false;
$count = false;
function incrementN($lexer, $count)
{
    $i = 0;
    for (; $i < $count; $i++) {
        incState($lexer);
    }
}
function incState($lexer)
{
    $lexer->current_state++;
    if ($lexer->current_state === sizeof($lexer->mandatory_states)) {
        $lexer->mandatory = false;
    }
}
function handleselect($lexer, $val)
{
    if ($lexer->mandatory) {
        if (strcasecmp($val, $lexer->mandatory_states[$lexer->current_state]) === 0) {
            incState($lexer);
            return QL_Parser::SELECT;
        }
    }
}
function handlecolumn_list($lexer, $val)
{
    global $count;
    if ($lexer->mandatory) {
        if (!(strcasecmp($val, $lexer->mandatory_states[2]) === 0)) {
            if (strcmp($val, "*") === 0) {
                if (!$count) {
                    incrementN($lexer, 1);
                }
                return QL_Parser::ASTERISK;
            } else if ((strcmp($val, "(") === 0)) {
                return QL_Parser::PARENOPEN;
            } else if (strcmp($val, ")") === 0) {
                return QL_Parser::PARENCLOSE;
            } else if ((strcasecmp($val, "count") === 0)) {
                $count = true;
                return QL_Parser::COUNT;
            } else if (strcmp($val, ",") === 0) {
                return QL_Parser::COMMA;
            } else {
                return QL_Parser::COLUMNNAME;
            }
        } else {
            incrementN($lexer, 2);
            return QL_Parser::FRM;
        }
    }
}
function handlefrom($lexer, $val)
{
    if ((strcasecmp($val, $lexer->mandatory_states[$lexer->current_state]) === 0)) {
        incState($lexer);
        return QL_Parser::FRM;
    }
}
function handletable($lexer, $val)
{
    if ($lexer->mandatory) {
        $lexer->current_state = 0;
        $lexer->mandatory = false;
        if (!(strcasecmp($val, $lexer->optional_states[$lexer->current_state]) === 0)) {
            return QL_Parser::TABLENAME;
        }
    }
}
function handlewhere($lexer, $val)
{
    global $where_col, $in_started;
    $val = trim($val);
    if ((strcmp($val, "=") === 0)) {
        return QL_Parser::EQ;
    } else if ((strcasecmp($val, $lexer->optional_states[$lexer->current_state]) === 0)) {
        return QL_Parser::WHERE;
    } else if ((strcmp($val, "<") === 0)) {
        return QL_Parser::LT;
    } else if ((strcmp($val, "<=") === 0)) {
        return QL_Parser::LTE;
    } else if ((strcmp($val, ">=") === 0)) {
        return QL_Parser::GTE;
    } else if ((strcmp($val, "!=") === 0)) {
        return QL_Parser::NE;
    } else if ((strcmp($val, ">") === 0)) {
        return QL_Parser::GT;
    } else if ((strcmp($val, "(") === 0)) {
        return QL_Parser::PARENOPEN;
    } else if ((strcmp($val, ")") === 0)) {
        if ($in_started) {
            $in_started = false;
            $where_col = false;
        }
        return QL_Parser::PARENCLOSE;
    } else if ((strcasecmp($val, "and") === 0)) {
        return QL_Parser::LOGICAL_AND;
    } else if ((strcasecmp($val, "or") === 0)) {
        return QL_Parser::LOGICAL_OR;
    } else if (!$where_col) {
        $where_col = true;
        return QL_Parser::COLUMNNAME;
    } else if ((strcasecmp($val, "in") === 0)) {
        $in_started = true;
        return QL_Parser::IN;
    } else if (strcmp($val, ",") === 0) {
        return QL_Parser::COMMA;
    } else if (strcasecmp($val, "like") === 0) {
        return QL_Parser::LIKE;
    } else if ($where_col) {
        if (!$in_started) {
            $where_col = false;
        }
        return QL_Parser::VALUE;
    }
}
function handleorderby($lexer, $val)
{
    global $orderby;
    if (!$orderby) {
        $orderby = true;
        return QL_Parser::ORDERBY;
    }
    if (strcmp($val, ",") === 0) {
        return QL_Parser::COMMA;
    } else if (strcasecmp($val, "asc") === 0) {
        return QL_Parser::ASC;
    } else if (strcasecmp($val, "desc") === 0) {
        return QL_Parser::DESC;
    } else {
        return QL_Parser::COLUMNNAME;
    }
}
function handlelimit($lexer, $val)
{
    if ((strcasecmp($val, "limit") === 0)) {
        return QL_Parser::LIMIT;
    } else if ((strcmp($val, "(") === 0)) {
        return QL_Parser::PARENOPEN;
    } else if ((strcmp($val, ")") === 0)) {
        return QL_Parser::PARENCLOSE;
    } else if (strcmp($val, ",") === 0) {
        return QL_Parser::COMMA;
    } else {
        return QL_Parser::VALUE;
    }
}
function handleend($lexer, $val)
{
    return QL_Parser::SEMICOLON;
}
class QL_Lexer
{
    private $index;
    public $token;
    public $value;
    public $linenum;
    public $state = 1;
    private $data;
    public $mandatory_states = array('select', 'column_list', 'from', 'table');
    public $optional_states = array('where', 'orderby', 'limit');
    public $mandatory;
    public $current_state;
    function __construct($data)
    {
        $this->index = 0;
        $this->data = $data;
        $this->linenum = 1;
        $this->mandatory = true;
        $this->current_state = 0;
    }
    function __toString()
    {
        return $this->token . "";
    }

    private $_yy_state = 1;
    private $_yy_stack = array();

    function yylex()
    {
        return $this->{'yylex' . $this->_yy_state}();
    }

    function yypushstate($state)
    {
        array_push($this->_yy_stack, $this->_yy_state);
        $this->_yy_state = $state;
    }

    function yypopstate()
    {
        $this->_yy_state = array_pop($this->_yy_stack);
    }

    function yybegin($state)
    {
        $this->_yy_state = $state;
    }



    function yylex1()
    {
        $tokenMap = array(
            1 => 2,
            4 => 0,
        );
        if ($this->index >= strlen($this->data)) {
            return false; // end of input
        }
        $yy_global_pattern = "/^((\\w+|'(?:[^']|'')+'|\\(|\\)|(\\+|-)?\\d+|,|\\*|(?!<|>)=|<(?!=)|>(?!=)|<=|>=|!=|;))|^([ \t\r\n]+)/";

        do {
            if (preg_match($yy_global_pattern, substr($this->data, $this->index), $yymatches)) {
                $yysubmatches = $yymatches;
                $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                if (!count($yymatches)) {
                    throw new Exception('Error: lexing failed because a rule matched' .
                        'an empty string.  Input "' . substr(
                            $this->data,
                            $this->index,
                            5
                        ) . '... state INITR');
                }
                next($yymatches); // skip global match
                $this->token = key($yymatches); // token number
                if ($tokenMap[$this->token]) {
                    // extract sub-patterns for passing to lex function
                    $yysubmatches = array_slice(
                        $yysubmatches,
                        $this->token + 1,
                        $tokenMap[$this->token]
                    );
                } else {
                    $yysubmatches = array();
                }
                $this->value = current($yymatches); // token value
                $r = $this->{'yy_r1_' . $this->token}($yysubmatches);
                if ($r === null) {
                    $this->index += strlen($this->value);
                    $this->linenum += substr_count("\n", $this->value);
                    // accept this token
                    return true;
                } elseif ($r === true) {
                    // we have changed state
                    // process this token in the new state
                    return $this->yylex();
                } elseif ($r === false) {
                    $this->index += strlen($this->value);
                    $this->linenum += substr_count("\n", $this->value);
                    if ($this->index >= strlen($this->data)) {
                        return false; // end of input
                    }
                    // skip this token
                    continue;
                } else {
                    $yy_yymore_patterns = array(
                        1 => "^([ \t\r\n]+)",
                        4 => "",
                    );

                    // yymore is needed
                    do {
                        if (!strlen($yy_yymore_patterns[$this->token])) {
                            throw new Exception('cannot do yymore for the last token');
                        }
                        if (preg_match(
                            $yy_yymore_patterns[$this->token],
                            substr($this->data, $this->index),
                            $yymatches
                        )) {
                            $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                            next($yymatches); // skip global match
                            $this->token = key($yymatches); // token number
                            $this->value = current($yymatches); // token value
                            $this->linenum = substr_count("\n", $this->value);
                        }
                    } while ($this->{'yy_r1_' . $this->token}() !== null);
                    // accept
                    $this->index += strlen($this->value);
                    $this->linenum += substr_count("\n", $this->value);
                    return true;
                }
            } else {
                throw new Exception('Unexpected input at line' . $this->linenum .
                    ': ' . $this->data[$this->index]);
            }
            break;
        } while (true);
    } // end function


    const INITR = 1;
    function yy_r1_1($yy_subpatterns)
    {

        global $orderby;
        //echo "<br> ql state: ",$this->current_state," ",$this->value,"<br>";
        if ($this->mandatory) {
            //echo "<br> ql state: ",$this->current_state," ",$this->value,"<br>";
            $handler = 'handle' . $this->mandatory_states[$this->current_state];
            $this->token = $handler($this, $this->value);
        } else {
            $str = $this->value;
            if (strcmp($this->value, ";") === 0) {
                $this->token = handleend($this, $this->value);
                return;
            }
            if (strcasecmp($this->value, "order") === 0) {
                $orderby = true;
                return false;
            } else if (strcasecmp($this->value, "by") === 0 && $orderby === true) {
                $orderby = false;
                $this->current_state = 1;
            }
            $index = array_search(strtolower($str), $this->optional_states, true);
            if ($index !== false) {
                $this->current_state = $index;
            }
            $handler = 'handle' . $this->optional_states[$this->current_state];
            $this->token = $handler($this, $this->value);
        } //$this->yypushstate($this->value);
    }
    function yy_r1_4($yy_subpatterns)
    {

        return false;
    }
}
