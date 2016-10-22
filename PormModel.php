<?php
// Shawn Massa - 7302 Powderhorn Dr.
//
// set_include_path($_SERVER['DOCUMENT_ROOT'] . PATH_SEPARATOR . get_include_path());
require 'PormProperty.php';
require 'PormCache.php';


class PormModel {

    /**
     *
     * Initializes the class __pm array and unsets the managed properties
     * in the current instance.
     *
     * Creates everything for this class
     *
     * For every @porm property, unset that property in this
     * instance.  Allows us to declare the properties in the class definition, and still
     * use the __get/__set interceptor methods.
     *
     * @param $zObj
     * @return void
     */
    static function Init($zObj) {
        $className = get_class($zObj);
        self::trace("PormModel::::Init");
        if (PormCache::get($className)) {

        }
        else PormCache::set($className, function () {
            $oReflectClass = new ReflectionClass($className);
            // For each public property
            foreach ($oReflectClass->getProperties(ReflectionProperty::IS_PUBLIC) as $oReflectProperty) {
                if (!($sDocBlock = $oReflectProperty->getDocComment()) || false === strpos($sDocBlock, " @porm")) continue;

                $propertyInfo = self::getDocBlockInfo($sDocBlock, $pn = $oReflectProperty->getName());
                error_log(__METHOD__.print_r($propertyInfo, true));
                unset($z->{$pn});
            }
        });

    }

    private function _initClass() {

    }

    function __construct() {
        PormProperty::init($this);
    }

    /**
     * Generic getter
     *
     * Called everytime a @pm property is referenced
     *
     * @param  string  $prop  Name of object property
     * @return mixed          Value of property.
     */
    function __get($prop) {
        try {
            return PormProperty::get($prop, $this);
        }
        catch (Exception $e) {
            error_log($e);
            throw $e;
        }
    }

    /**
     * Generic setter
     *
     * Called everytime a @pm property of an object is set
     *
     * @param  string   $prop     Name of object property
     * @param  mixed    $value    Value to set object property to
     * @return mixed
     */
    function __set($prop, $value) {
        try {
            return PormProperty::set($prop, $value, $this);
        }
        catch (Exception $e) {
            error_log($e);
            throw $e;
        }
    }
    /**
     *
     * Logs a trace to the point where called
     *
     * @param  mixed  String to print | TODO function that returns
     *                a string.  (Arguments and return
     *                values are AUTOMATICALLY printed.)
     * @return void
     */
    static function trace() {

        $argc = count($argv = func_get_args());

        $backtrace = debug_backtrace();
        // default order is inside out, so this puts it in "top to bottom"
        $backtrace = array_reverse($backtrace);

        $indent = 0;

        $request = $_REQUEST;
//      unset($request['XXX']); // get rid of anything you don't always want logged

        $file = isset($backtrace[0]['file']) ? $backtrace[0]['file'] : '';
        $description = "<?php {$file}" . ($argc ? " [{$backtrace[0]['line']}] - {$argv[0]}" : '');

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        $description = "AJAX request - " . $description;

        self::line("\n{$description} \$_REQUEST: " . self::prin_r($request, true));

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
        $s = str_repeat(" ", $indent) . "{$line}{$verb}(" . self::prin_r($trace_entry['args'], true) . ")";
        $indent += strlen($line);

        if (count($argv = func_get_args()) > 2)
            return $s . " \"" . self::prin_r($argv[2], true) . "\"";
        else
            return $s;
    }

    static function line($string) {
        $line_start = date("m/d/Y H:i:s ");
        error_log("{$line_start}{$string}\n", 3, "__pm.log");
    }

    static function prin_r($arg, $unwrap = false) {
        $oneLine = preg_replace("/\s*\n\s*/" , ' ', print_r($arg, true));
        if ($unwrap)
            $oneLine = preg_replace("/ \[\d*\] => /" , '', $oneLine);
        return gettype($arg) == 'object' ? '<'.$oneLine.'>' : $oneLine;
    }
}