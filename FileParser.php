<?php

    class FileParser {
        private $fileHandler, $rowDelimiter, $columnDelimiter;

        /**
         * FileParser constructor.
         * @param $fileHandler FileHandler
         * @param string $rowDelimiter
         * @param string $columnDelimiter
         */

        public function __construct($fileHandler, $rowDelimiter = PHP_EOL, $columnDelimiter = ";") {
            $this->fileHandler = $fileHandler;
            $this->rowDelimiter = $rowDelimiter;
            $this->columnDelimiter = $columnDelimiter;
        }

        /**
         * @return FileHandler
         */
        public function getFileHandler() {
            return $this->fileHandler;
        }

        /**
         * @param FileHandler $fileHandler
         */
        public function setFileHandler($fileHandler) {
            $this->fileHandler = $fileHandler;
        }

        /**
         * @return string
         */
        public function getRowDelimiter() {
            return $this->rowDelimiter;
        }

        /**
         * @param string $rowDelimiter
         */
        public function setRowDelimiter($rowDelimiter) {
            $this->rowDelimiter = $rowDelimiter;
        }

        /**
         * @return string
         */
        public function getColumnDelimiter() {
            return $this->columnDelimiter;
        }

        /**
         * @param string $columnDelimiter
         */
        public function setColumnDelimiter($columnDelimiter) {
            $this->columnDelimiter = $columnDelimiter;
        }

        /**
         * @param array data
         * @throws Exception
         */
        public function saveData($data) {
            $str = "";
            foreach ($data as $val) {
                if (gettype($val) === "array") {
                    $str .= implode($this->columnDelimiter, $val);
                } else {
                    $str .= $val;
                }
                $str .= $this->rowDelimiter;
            }
            try {
                $this->fileHandler->write($str, true);
            } catch (Exception $e) {
                $this->throwError($e);
            }
        }

        /**
         * @return array
         * @throws Exception
         */
        public function loadData() {
            $arr = [];
            try {
                $arr = $this->fileHandler->dataRead();
            } catch (Exception $e) {
                $this->throwError($e);
            }
            $r = [];
            foreach ($arr as $row) {
                $columns = explode($this->columnDelimiter, $row);
                foreach($columns as $key => $column) {
                    $columns[$key] = trim($column);
                }
                array_push($r, $columns);
            }
            return $r;
        }

        /**
         * @param $data string|array
         * @throws Exception
         */
        public function addRow($data) {
            if (gettype($data) === "array") {
                $str = implode($this->columnDelimiter, $data);
            } else {
                $str = $data;
            }
            try {
                $this->fileHandler->write($str . $this->rowDelimiter);
            } catch (Exception $e) {
                $this->throwError($e);
            }
        }

        /**
         * @param $search string
         * @return boolean
         * @throws Exception
         */
        public function deleteRow($search) {
            $arr = [];
            try {
                $arr = $this->loadData();
            } catch (Exception $e) {
                $this->throwError($e);
            }
            $rm = $this->findRow($search);
            if (is_null($rm)) return false;

            unset($arr[$rm]);
            try {
                $this->saveData($arr);
            } catch (Exception $e) {
                $this->throwError($e);
            }
            return true;
        }

        /**
         * @param $search string
         * @param $data array|string
         * @return boolean
         * @throws Exception
         */
        public function editRow($search, $data) {
            $arr = [];
            try {
                $arr = $this->loadData();
            } catch (Exception $e) {
                $this->throwError($e);
            }
            $edit = $this->findRow($search);
            if (is_null($edit)) return false;
            $arr[$edit] = $data;
            try {
                $this->saveData($arr);
            } catch (Exception $e) {
                $this->throwError($e);
            }
            return true;
        }

        /**
         * @param $header array
         * @throws Exception
         */
        public function printTable($header) {
            $arr = [];
            try {
                $arr = $this->loadData();
            } catch (Exception $e) {
                $this->throwError($e);
            }

            $str = "<table><tr>";
            foreach ($header as $th) {
                $str .= "<th>$th</th>";
            }
            $str .= "</tr>";

            foreach ($arr as $row) {
                $str .= "<tr>";
                foreach ($row as $col) {
                    $str .= "<td>$col</td>";
                }
                $str .= "</tr>";
            }

            $str .= "</table>";

            echo $str;
        }

        /**
         * @param $search
         * @return null|string
         * @throws Exception
         */
        private function findRow($search) {
            $arr = [];
            try {
                $arr = $this->loadData();
            } catch (Exception $e) {
                $this->throwError($e);
            }
            foreach ($arr as $key => $row) {
                foreach ($row as $col) {
                    if (trim($col) == $search) {
                        return $key;
                        break;
                    }
                }
            }
            return null;
        }

        /**
         * @param $exception Exception
         * @throws Exception
         */
        private function throwError($exception) {
            throw new Exception("Parsing Error: " . $exception->getMessage());
        }
    }