<?php

    class FileHandler {
        private $filepath, $error, $linebreak;

        /**
         * @param string $filepath
         * @param string $linebreak
         * @param boolean $createFile
         */
        public function __construct($filepath, $linebreak = "", $createFile = false) {
            if (!file_exists($filepath)) {
                if ($createFile) {
                    $f = @fopen($filepath, "w");
                    if (!$f) {
                        $this->error = "File creation error";
                    } else {
                        fclose($f);
                    }
                } else {
                    $this->error = "File not founded";
                }
            } else {
                $this->error = null;
            }
            $this->filepath = $filepath;
            $this->linebreak = $linebreak;
        }

        /**
         * @return string
         */
        public function getFilepath() {
            return $this->filepath;
        }

        /**
         * @param string $filepath
         */
        public function setFilepath($filepath) {
            $this->filepath = $filepath;
        }

        /**
         * @return null
         */
        public function getError() {
            return $this->error;
        }

        /**
         * @param null $error
         */
        public function setError($error) {
            $this->error = $error;
        }

        /**
         * @return string
         */
        public function getLinebreak() {
            return $this->linebreak;
        }

        /**
         * @param string $linebreak
         */
        public function setLinebreak($linebreak) {
            $this->linebreak = $linebreak;
        }

        /**
         * @return string
         */
        public function read() {
            if (!$this->fileExists()) {
                $this->error = "File not founded";
                return null;
            }
            $f = @fopen($this->filepath, "r");
            if (!$f) {
                $this->error = "File opening error";
                return null;
            }
            $str = "";
            while (!feof($f)) {
                $str .= fgets($f) . $this->linebreak;
            }
            fclose($f);
            return $str;
        }

        /**
         * @param string $text
         * @param boolean $clear
         * @return boolean
         */

        public function write($text, $clear = false) {
            if (!$this->fileExists()) {
                $this->error = "File not founded";
                return false;
            }
            $f = @fopen($this->filepath, ($clear ? "w" : "a"));
            if (!$f) {
                $this->error = "File opening error";
                return false;
            }
            $ex = fwrite($f, $text);
            if ($ex === false) {
                $this->error = "File writing error";
                return false;
            }
            fclose($f);
            return true;
        }

        /**
         * @return boolean
         */
        private function fileExists() {
            if (!file_exists($this->filepath)) {
                $this->error = "File not founded";
                return false;
            }
            return true;
        }
    }