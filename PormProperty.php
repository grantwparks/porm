<?php
class PormProperty {

    // internal meta-management

    /**
     * Unsets the indicated properties in client class
     *
     * @param  object  $z
     */
    static function init($z) {

    }

    /**
     * Returns an array of info from a docblock
     *
     * @param  string $sDocComment  docblock
     * @param  string $pn           property name
     * @return array(               parsed docblock
     *     [label] => from short description
     *     [var]   => data type
     * )
     */
    public function getDocBlockInfo($sDocComment, $pn) {

        // get the "short description" from docblock (start in position 3)
        preg_match("/\.*\s\*\s*([^\n]*)/m", $sDocComment, $desc, 0, 3);
        $return = array('label' => isset($desc[1]) ? $desc[1] : $pn);

        preg_match_all("/[^@]+@(\S+)\s*(\S+)?\s*([^@]+)?\n/", $sDocComment, $matches, PREG_SET_ORDER, 3);
        foreach ($matches as $tokens) {
            // token name
            $token = $tokens[1];

            // if we only have the @token, then its value is true
            if (count($tokens) == 2) {

                $return[$token] = true;
            }
            // otherwise we need arguments to @token as the value
            else {
                array_shift($tokens); array_shift($tokens);
                $return[$token] = $tokens;
            }
        }
        return $return;
    }


    // property accessors

    /**
     * Public property accessor
     *
     * @param  string  $pn
     * @param  mixed   $z
     * @return mixed
     */
    static function get($pn, $z) {
        self::trace($z);
        return null;
    }


    /**
     * Public property accessor
     *
     * @param  string  $pn
     * @param  mixed   $pv
     * @return mixed   Passed value
     */
    function set($pn, $pv, $z) {
        self::trace($z);
        return $pv;
    }


    // -- logging

    /**
     *
     * Logs a trace to the point where called
     *
     * @param  mixed  String to print | TODO function that returns
     *                a string.
     *                (Arguments and return values are
     *                AUTOMATICALLY printed.)
     * @return void
     */
    static function trace() {

        $argc = count($argv = func_get_args());

        $backtrace = debug_backtrace();
        // default order is inside out, so this puts it in "top to bottom"
        $backtrace = array_reverse($backtrace);

        $indent = 0;

        // get rid of weird google analytic request var
        $request = $_REQUEST;
        //      unset($request['XXX']); // get rid of anything you don't always want logged

        $file = isset($backtrace[0]['file']) ? $backtrace[0]['file'] : '';
        $description = "<?php {$file}" . ($argc && count($backtrace) < 2 ? " [{$backtrace[0]['line']}] - {$argv[0]}" : '');

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        $description = "AJAX request - " . $description;

        self::line("{$description} \$_REQUEST: " . self::prin_r($request, true));

        // get rid of the last stack entry for having called the log method, itself
        array_pop($backtrace);
        if (count($backtrace) > 0) {
            for ($i = 0, $stackCount = count($backtrace) - 1; $i < $stackCount; $i++) {
                self::line(self::trace_line($indent, $backtrace[$i]));
            }
            self::line($argc ? self::trace_line($indent, $backtrace[$i], $argv[0]) : self::trace_line($indent, $backtrace[$i]));
        }
    }


    /**
     * Returns a single entry.
     *
     * @param  integer  $indent
     * @param  array    $trace_entry
     * @param  string   $argv[2]
     * @return string
     */
    private static function trace_line(&$indent, $trace_entry) {

        $line = array_key_exists('line', $trace_entry) ? "[{$trace_entry['line']}] " : '';
        $verb = $trace_entry['function'];

        if (array_key_exists('class', $trace_entry)) {
            $classfile = $trace_entry['class'];
            $verb = "->" . $verb;
            if (array_key_exists('object', $trace_entry) and $object = $trace_entry['object']) {
                $id = property_exists($object, 'id') ? $object->id : false;
                $class = "<" . ($classname = get_class($object)) . ($id ? "/{$id}>" : '>');
                if ($classfile != $classname)
                $class .= " {$classfile}";
                $verb = $class . $verb;
            }
            else
            $verb = $classfile . $verb;
        }
        else {
            $file = isset($trace_entry['file']) ? substr($trace_entry['file'], self::$_baseDirLength) : 'unknown file';
            $verb = "{$file} " . $verb;
        }
        $s = str_repeat(" ", $indent) . "{$line}{$verb}(" . self::prin_r($trace_entry['args']) . ")";
        $indent += strlen($line);

        if (count($argv = func_get_args()) > 2)
        return $s . "=" . self::prin_r($argv[2], true);
        else
        return $s;
    }


    static function line($string) {
        $line_start = date("m/d/Y H:i:s ");
        error_log("{$line_start}{$string}\n", 3, "__pm.log");
    }

    static function prin_r($arg) {
        $oneLine = preg_replace("/\s*\n\s*/" , ' ', print_r($arg, true));
        return gettype($arg) == 'object' ? '<'.$oneLine.'>' : $oneLine;
    }
}