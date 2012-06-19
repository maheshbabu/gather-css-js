<?

class AutoVersion {

    public function fly($folder, $files_array = array(), $content_type = 'text/css', $compress = true) {
        $code = '';
        $path = $_SERVER['DOCUMENT_ROOT'] . $folder;
        $last_modified = $this->last_modified($folder, $files_array);
        $max_age = 60 * 60 * 24 * 365; // refresh if older than a year

        foreach ($files_array as $f) {
            $code .= file_get_contents($path . $f) . "\n";
        }

        if($compress) {
            $code = $this->_compress($code);
        }

        header("Content-type: " . $content_type);
        header("Last-Modified: " . gmdate("D, d M Y H:i:s", $last_modified) . " GMT");
        header("Cache-Control: max-age=$max_age, must-revalidate");
        header('Content-Length: ' . strlen($code));

        echo $code;
    }

    public function last_modified($folder, $files_array = array()) {

        $path = $_SERVER['DOCUMENT_ROOT'] . $folder;
        $last_modified = 0;

        foreach ($files_array as $f) {

            if (!$mtime = @filemtime($path . $f)) {
                $mtime = time();
            }

            if ($last_modified < $mtime) {
                $last_modified = $mtime;
            }
        }

        return $last_modified;
    }

    private function _compress($buffer) {
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer); // remove comments
        $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer); // remove tabs, spaces, newlines, etc.
        $buffer = str_replace(' {', '{', $buffer); // remove space before {
        $buffer = str_replace(': ', ':', $buffer); // remove space after :
        return $buffer;
    }
}
