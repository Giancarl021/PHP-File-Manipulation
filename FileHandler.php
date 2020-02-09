<?php

    class FileHandler {
        private $filepath, $linebreak;

        /**
         * @param string $filepath
         * @param string $linebreak
         * @param boolean $createFile
         * @throws Exception
         */
        public function __construct($filepath, $linebreak = "", $createFile = false) {
            if (!file_exists($filepath)) {
                if ($createFile) {
                    $f = @fopen($filepath, "w");
                    if (!$f) {
                        throw new Exception("File creation error");
                    } else {
                        fclose($f);
                    }
                } else {
                    throw new Exception("File not founded");
                }
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
         * @throws Exception
         */
        public function read() {
            if (!$this->fileExists()) {
                throw new Exception("File not founded");
            }
            $f = @fopen($this->filepath, "r");
            if (!$f) {
                throw new Exception("File opening error");
            }
            $str = "";
            while (!feof($f)) {
                $str .= fgets($f) . $this->linebreak;
            }
            fclose($f);
            return $str;
        }

        /**
         * @return array
         * @throws Exception
         */
        public function dataRead() {
            if (!$this->fileExists()) {
                throw new Exception("File not founded");
            }
            $f = @fopen($this->filepath, "r");
            if (!$f) {
                throw new Exception("File opening error");
            }
            $arr = [];
            while (!feof($f)) {
                $tmp = fgets($f);
                if(!$tmp || trim($tmp) == "") continue;
                $arr[] = $tmp;
            }
            fclose($f);
            return $arr;
        }

        /**
         * @param string $text
         * @param boolean $clear
         * @return boolean
         * @throws Exception
         */

        public function write($text, $clear = false) {
            if (!$this->fileExists()) {
                throw new Exception("File not founded");
            }
            $f = @fopen($this->filepath, ($clear ? "w" : "a"));
            if (!$f) {
                throw new Exception("File opening error");
            }
            $ex = fwrite($f, $text);
            if ($ex === false) {
                throw new Exception("File writing error");
            }
            fclose($f);
            return true;
        }

        /**
         * @return boolean
         * @throws Exception
         */
        private function fileExists() {
            return file_exists($this->filepath);
        }
    }
