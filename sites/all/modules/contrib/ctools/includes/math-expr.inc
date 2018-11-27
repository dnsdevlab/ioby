<?php


class ctools_math_expr {
  var $suppress_errors = FALSE;
  var $last_error = NULL;
  var $errors = array();

  var $v = array('e' => 2.71, 'pi' => 3.14); // variables (and constants)
  var $f = array(); // user-defined functions
  var $vb = array('e', 'pi'); // constants
  var $fsimple = array( // built-in functions
    'sin', 'sinh', 'asin', 'asinh',
    'cos', 'cosh', 'acos', 'acosh',
    'tan', 'tanh', 'atan', 'atanh',
    'exp',
    'sqrt', 'abs', 'log',
    'time', 'ceil', 'floor', 'round'
  );
  var $fb = array(
    'ln' => array(
      'function' => 'log',
      'arguments' => 1,
    ),
    'arcsin' => array(
      'function' => 'asin',
      'arguments' => 1,
    ),
    'arcsinh' => array(
      'function' => 'asinh',
      'arguments' => 1,
    ),
    'arccos' => array(
      'function' => 'acos',
      'arguments' => 1,
    ),
    'arccosh' => array(
      'function' => 'acosh',
      'arguments' => 1,
    ),
    'arctan' => array(
      'function' => 'atan',
      'arguments' => 1,
    ),
    'arctanh' => array(
      'function' => 'atanh',
      'arguments' => 1,
    ),
    'min' => array(
      'function' => 'min',
      'arguments' => 2,
      'max arguments' => 99,
    ),
    'max' => array(
      'function' => 'max',
      'arguments' => 2,
      'max arguments' => 99,
    ),
    'pow' => array(
      'function' => 'pow',
      'arguments' => 2,
    ),
    'if' => array(
      'function' => 'ctools_math_expr_if',
      'arguments' => 2,
      'max arguments' => 3,
    ),
    'number' => array(
      'function' => 'ctools_math_expr_number',
      'arguments' => 1,
    ),
  );

  /**
   * Public constructor.
   */
  public function __construct() {
    // Make the variables a little more accurate.
    $this->v['pi'] = pi();
    $this->v['e'] = exp(1);

    // Allow modules to add custom functions.
    // @todo -- properly document this.
    $context = array('final' => &$this->fb);
    drupal_alter('ctools_math_expression_functions', $this->fsimple, $context);

    // Translate the older, simpler style into the newer, richer style.
    foreach ($this->fsimple as $function) {
      $this->fb[$function] = array(
        'function' => $function,
        'arguments' => 1,
      );
    }
  }

  /**
   * Evaluate the expression.
   *
   * @param string $expr The expression to evaluate.
   * @return string
   */
  function evaluate($expr) {
    $this->last_error = NULL;
    $expr = trim($expr);
    if (substr($expr, -1, 1) == ';') {
      $expr = substr($expr, 0, strlen($expr) - 1);
    } // strip semicolons at the end

    // is it a variable assignment?
    if (preg_match('/^\s*([a-z]\w*)\s*=\s*(.+)$/', $expr, $matches)) {
      if (in_array($matches[1], $this->vb)) { // make sure we're not assigning to a constant
        return $this->trigger("cannot assign to constant '$matches[1]'");
      }
      if (($tmp = $this->pfx($this->nfx($matches[2]))) === FALSE) {
        return FALSE;
      } // get the result and make sure it's good
      $this->v[$matches[1]] = $tmp; // if so, stick it in the variable array
      return $this->v[$matches[1]]; // and return the resulting value

      // is it a function assignment?
    }
    elseif (preg_match('/^\s*([a-z]\w*)\s*\(\s*([a-z]\w*(?:\s*,\s*[a-z]\w*)*)\s*\)\s*=\s*(.+)$/', $expr, $matches)) {
      $fnn = $matches[1]; // get the function name
      if (isset($this->fb[$matches[1]])) { // make sure it isn't built in
        return $this->trigger("cannot redefine built-in function '$matches[1]()'");
      }
      $args = explode(",", preg_replace("/\s+/", "", $matches[2])); // get the arguments
      if (($stack = $this->nfx($matches[3])) === FALSE) {
        return FALSE;
      } // see if it can be converted to postfix
      for ($i = 0; $i < count($stack); $i++) { // freeze the state of the non-argument variables
        $token = $stack[$i];
        if (preg_match('/^[a-z]\w*$/', $token) and !in_array($token, $args)) {
          if (array_key_exists($token, $this->v)) {
            $stack[$i] = $this->v[$token];
          }
          else {
            return $this->trigger("undefined variable '$token' in function definition");
          }
        }
      }
      $this->f[$fnn] = array('args' => $args, 'func' => $stack);

      return TRUE;

    }
    else {
      return trim($this->pfx($this->nfx($expr)), '"'); // straight up evaluation, woo
    }
  }

  /**
   * Fetch an array of variables used in the expression.
   *
   * @return array
   */
  public function vars() {
    $output = $this->v;
    unset($output['pi']);
    unset($output['e']);

    return $output;
  }

  /**
   * Fetch all user defined functions in the expression.
   *
   * @return array
   */
  public function funcs() {
    $output = array();
    foreach ($this->f as $fnn => $dat) {
      $output[] = $fnn . '(' . implode(',', $dat['args']) . ')';
    }

    return $output;
  }

  // Convert infix to postfix notation
  private function nfx($expr) {

    $index = 0;
    $stack = new ctools_math_expr_stack;
    $output = array(); // postfix form of expression, to be passed to pfx()
    $expr = trim(strtolower($expr));

    $ops = array(
      '+' => array(
        'precedence' => 0,
      ),
      '-' => array(
        'precedence' => 0,
      ),
      '*'=> array(
        'precedence' => 1,
      ),
      '/'=> array(
        'precedence' => 1,
      ),
      '^' => array(
        'right' => TRUE,
        'precedence' => 2,
      ),
      '_' => array(
        'precedence' => 1,
      ),
      '==' => array(
        'precedence' => -1,
      ),
      '!=' => array(
        'precedence' => -1,
      ),
      '>=' => array(
        'precedence' => -1,
      ),
      '<=' => array(
        'precedence' => -1,
      ),
      '>' => array(
        'precedence' => -1,
      ),
      '<' => array(
        'precedence' => -1,
      )
    );
    $expecting_op = FALSE; // we use this in syntax-checking the expression
    // And determining when a - is a negation

    while (1) { // 1 Infinite Loop ;)
      $op = substr($expr, $index, 1); // get the first character at the current index
      // if the second character is an =, add it to our op.
      if (substr($expr, $index + 1, 1) == '=') {
        $op = substr($expr, $index, 2);
        $index++;
      }
      // find out if we're currently at the beginning of a number/variable/function/parenthesis/operand
      $ex = preg_match('/^([a-z]\w*\(?|\d+(?:\.\d*)?|\.\d+|\()/', substr($expr, $index), $match);

      if ($op == '-' and !$expecting_op) { // is it a negation instead of a minus?
        $stack->push('_'); // put a negation on the stack
        $index++;
      }
      elseif ($op == '_') { // we have to explicitly deny this, because it's legal on the stack
        return $this->trigger("illegal character '_'"); // but not in the input expression

      }
      elseif ((isset($ops[$op]) || $ex) && $expecting_op) { // are we putting an operator on the stack?
        if ($ex) { // are we expecting an operator but have a number/variable/function/opening parenthesis?
          $op = '*';
          $index--; // it's an implicit multiplication
        }
        // heart of the algorithm:
        while ($stack->count > 0 && ($o2 = $stack->last()) && isset($ops[$o2]) && (!empty($ops[$op]['right']) ? $ops[$op]['precedence'] < $ops[$o2]['precedence'] : $ops[$op]['precedence'] <= $ops[$o2]['precedence'])) {
          $output[] = $stack->pop(); // pop stuff off the stack into the output
        }
        // many thanks: http://en.wikipedia.org/wiki/Reverse_Polish_notation#The_algorithm_in_detail
        $stack->push($op); // finally put OUR operator onto the stack
        $index++;
        $expecting_op = FALSE;

      }
      elseif ($op == ')' and $expecting_op) { // ready to close a parenthesis?
        while (($o2 = $stack->pop()) != '(') { // pop off the stack back to the last (
          if (is_null($o2)) {
            return $this->trigger("unexpected ')'");
          }
          else {
            $output[] = $o2;
          }
        }
        if (preg_match("/^([a-z]\w*)\($/", $stack->last(2), $matches)) { // did we just close a function?
          $fnn = $matches[1]; // get the function name
          $arg_count = $stack->pop(); // see how many arguments there were (cleverly stored on the stack, thank you)
          $output[] = $stack->pop(); // pop the function and push onto the output
          if (isset($this->fb[$fnn])) { // check the argument count
            $max_arguments = isset($this->fb[$fnn]['max arguments']) ? $this->fb[$fnn]['max arguments'] : $this->fb[$fnn]['arguments'];
            if ($arg_count > $max_arguments) {
              return $this->trigger("too many arguments ($arg_count given, $max_arguments expected)");
            }
          }
          elseif (array_key_exists($fnn, $this->f)) {
            if ($arg_count != count($this->f[$fnn]['args'])) {
              return $this->trigger("wrong number of arguments ($arg_count given, " . count($this->f[$fnn]['args']) . " expected)");
            }
          }
          else { // did we somehow push a non-function on the stack? this should never happen
            return $this->trigger("internal error");
          }
        }
        $index++;

      }
      elseif ($op == ',' and $expecting_op) { // did we just finish a function argument?
        while (($o2 = $stack->pop()) != '(') {
          if (is_null($o2)) {
            return $this->trigger("unexpected ',' in $expr $o2");
          } // oops, never had a (
          else {
            $output[] = $o2;
          } // pop the argument expression stuff and push onto the output
        }
        // make sure there was a function
        if (!preg_match("/^([a-z]\w*)\($/", $stack->last(2), $matches)) {
          return $this->trigger("unexpected ','");
        }
        $stack->push($stack->pop() + 1); // increment the argument count
        $stack->push('('); // put the ( back on, we'll need to pop back to it again
        $index++;
        $expecting_op = FALSE;

      }
      elseif ($op == '(' and !$expecting_op) {
        $stack->push('('); // that was easy
        $index++;
        $allow_neg = TRUE;

      }
      elseif ($ex and !$expecting_op) { // do we now have a function/variable/number?
        $expecting_op = TRUE;
        $val = $match[1];
        if (preg_match("/^([a-z]\w*)\($/", $val, $matches)) { // may be func, or variable w/ implicit multiplication against parentheses...
          if (isset($this->fb[$matches[1]]) or array_key_exists($matches[1], $this->f)) { // it's a func
            $stack->push($val);
            $stack->push(1);
            $stack->push('(');
            $expecting_op = FALSE;
          }
          else { // it's a var w/ implicit multiplication
            $val = $matches[1];
            $output[] = $val;
          }
        }
        else { // it's a plain old var or num
          $output[] = $val;
        }
        $index += strlen($val);

      }
      elseif ($op == ')') { // miscellaneous error checking
        return $this->trigger("unexpected ')'");
      }
      elseif (isset($ops[$op]) and !$expecting_op) {
        return $this->trigger("unexpected operator '$op'");
      }
      elseif ($op == '"') {
        // fetch a quoted string.
        $string = substr($expr, $index);
        if (preg_match('/"[^"\\\\]*(?:\\\\.[^"\\\\]*)*"/s', $string, $matches)) {
          $string = $matches[0];
          // trim the quotes off:
          $output[] = $string;
          $index += strlen($string);
          $expecting_op = TRUE;
        }
        else {
          return $this->trigger("open quote without close quote.");
        }
      }
      else { // I don't even want to know what you did to get here
        return $this->trigger("an unexpected error occured at $op");
      }
      if ($index == strlen($expr)) {
        if (isset($ops[$op])) { // did we end with an operator? bad.
          return $this->trigger("operator '$op' lacks operand");
        }
        else {
          break;
        }
      }
      while (substr($expr, $index, 1) == ' ') { // step the index past whitespace (pretty much turns whitespace
        $index++; // into implicit multiplication if no operator is there)
      }
    }
    while (!is_null($op = $stack->pop())) { // pop everything off the stack and push onto output
      if ($op == '(') {
        return $this->trigger("expecting ')'");
      } // if there are (s on the stack, ()s were unbalanced
      $output[] = $op;
    }

    return $output;
  }

  // evaluate postfix notation
  function pfx($tokens, $vars = array()) {

    if ($tokens == FALSE) {
      return FALSE;
    }

    $stack = new ctools_math_expr_stack;

    foreach ($tokens as $token) { // nice and easy
      // if the token is a binary operator, pop two values off the stack, do the operation, and push the result back on
      if (in_array($token, array('+', '-', '*', '/', '^', '==', '!=', '<', '<=', '>=', '>'))) {
        if (is_null($op2 = $stack->pop())) {
          return $this->trigger("internal error");
        }
        if (is_null($op1 = $stack->pop())) {
          return $this->trigger("internal error");
        }
        switch ($token) {
          case '+':
            $stack->push($op1 + $op2);
            break;
          case '-':
            $stack->push($op1 - $op2);
            break;
          case '*':
            $stack->push($op1 * $op2);
            break;
          case '/':
            if ($op2 == 0) {
              return $this->trigger("division by zero");
            }
            $stack->push($op1 / $op2);
            break;
          case '^':
            $stack->push(pow($op1, $op2));
            break;
          case '==':
            $stack->push(intval($op1 == $op2));
            break;
          case '!=':
            $stack->push(intval($op1 != $op2));
            break;
          case '<=':
            $stack->push(intval($op1 <= $op2));
            break;
          case '<':
            $stack->push(intval($op1 < $op2));
            break;
          case '>=':
            $stack->push(intval($op1 >= $op2));
            break;
          case '>':
            $stack->push(intval($op1 > $op2));
            break;
        }
        // if the token is a unary operator, pop one value off the stack, do the operation, and push it back on
      }
      elseif ($token == "_") {
        $stack->push(-1 * $stack->pop());
        // if the token is a function, pop arguments off the stack, hand them to the function, and push the result back on
      }
      elseif (preg_match("/^([a-z]\w*)\($/", $token, $matches)) { // it's a function!
        $fnn = $matches[1];
        if (isset($this->fb[$fnn])) { // built-in function:
          $args = array();
          for ($i = 0; $i < $this->fb[$fnn]['arguments']; $i++) {
            if (is_null($op1 = $stack->pop())) {
              return $this->trigger("function $fnn missing argument $i");
            }
            $args[] = $op1;
          }
          if (!empty($this->fb[$fnn]['max arguments'])) {
            for(;$i < $this->fb[$fnn]['max arguments']; $i++) {
              $arg = $stack->pop();
              if (!isset($arg)) {
                break;
              }
              $args[] = $arg;
            }
          }

          $stack->push(call_user_func_array($this->fb[$fnn]['function'], array_reverse($args)));
        }
        elseif (isset($fnn, $this->f)) { // user function
          // get args
          $args = array();
          for ($i = count($this->f[$fnn]['args']) - 1; $i >= 0; $i--) {
            if (is_null($args[$this->f[$fnn]['args'][$i]] = $stack->pop())) {
              return $this->trigger("internal error");
            }
          }
          $stack->push($this->pfx($this->f[$fnn]['func'], $args)); // yay... recursion!!!!
        }
        // if the token is a number or variable, push it on the stack
      }
      else {
        if (is_numeric($token) || $token[0] == '"') {
          $stack->push($token);
        }
        elseif (array_key_exists($token, $this->v)) {
          $stack->push($this->v[$token]);
        }
        elseif (array_key_exists($token, $vars)) {
          $stack->push($vars[$token]);
        }
        else {
          return $this->trigger("undefined variable '$token'");
        }
      }
    }
    // when we're out of tokens, the stack should have a single element, the final result
    if ($stack->count != 1) {
      return $this->trigger("internal error");
    }

    return $stack->pop();
  }

  // trigger an error, but nicely, if need be
  function trigger($msg) {
    $this->errors[] = $msg;
    $this->last_error = $msg;
    if (!$this->suppress_errors) {
      trigger_error($msg, E_USER_WARNING);
    }

    return FALSE;
  }
}

// for internal use
class ctools_math_expr_stack {

  var $stack = array();
  var $count = 0;

  function push($val) {
    $this->stack[$this->count] = $val;
    $this->count++;
  }

  function pop() {
    if ($this->count > 0) {
      $this->count--;

      return $this->stack[$this->count];
    }

    return NULL;
  }

  function last($n = 1) {
    return !empty($this->stack[$this->count - $n]) ? $this->stack[$this->count - $n] : NULL;
  }
}

function ctools_math_expr_if($expr, $if, $else = NULL) {
  return $expr ? $if : $else;
}

function ctools_math_expr_number($arg) {
  // Remove any non-digits so that numbers like $4,511.23 still work.
  // @todo: locale could break this sincein some locales that's $4.512,33
  // so there needs to be a way to detect that and make it work properly.
  $arg = preg_replace("/[^0-9\.]/", '', $arg);
  return $arg;
}