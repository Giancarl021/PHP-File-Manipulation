<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Exemplo</title>
    <meta charset="utf-8"/>
    <style>
        * {
            font-family: 'Consolas', monospace;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        table, tr, th, td {
            border: 2px solid black;
            border-collapse: collapse;
            text-align: center;
        }

        form {
            display: block;
        }

        h1 {
            width: 100%;
            text-align: center;
        }

        input {
            width: 100%;
            display: block;
            margin: 10px 0;
            outline: none;
            border: 2px solid black;
        }

        button {
            background-color: white;
            border: 2px solid black;
            outline: none;
            color: black;
            float: right;
        }

        td {
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="center">
        <?php
            require("FileHandler.php");
            require("FileParser.php");

            $fh = new FileHandler("data.txt", PHP_EOL, true);
            $fp = new FileParser($fh);

            $fp->printTable(["Número 1", "Número 2", "Número 3"]);
        ?>
        <form method="get" action="test.php">
            <h1>Cadastrar</h1>
            <input name="a" type="text" required/>
            <input name="b" type="text" required/>
            <input name="c" type="text" required/>
            <button type="submit" name="acc" value="add">Enviar</button>
        </form>
        <br/>
        <form method="get" action="test.php">
            <h1>Editar</h1>
            <input name="a" type="text" required/>
            <input name="b" type="text" required/>
            <input name="c" type="text" required/>
            <button type="submit" name="acc" value="edt">Enviar</button>
        </form>
        <br/>
        <form method="get" action="test.php">
            <h1>Remover</h1>
            <input name="a" type="text" required/>
            <button type="submit" name="acc" value="rmv">Enviar</button>
        </form>
        <?php
            foreach (["acc", "a"] as $val) {
                if (!isset($_GET[$val]) || trim($_GET[$val]) === "") {
                    die;
                } else {
                    $$val = $_GET[$val];
                }
            }
            if ($acc === "rmv") {
                try {
                    $fp->deleteRow($a);
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                reload();
            }
            foreach (["b", "c"] as $val) {
                if (!isset($_GET[$val]) || trim($_GET[$val]) === "") {
                    die;
                } else {
                    $$val = $_GET[$val];
                }
            }
            try {
                switch ($acc) {
                    case "add":
                        $fp->addRow([$a, $b, $c]);
                        break;
                    case "edt":
                        $fp->editRow($a, [$a, $b, $c]);
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            reload();
            function reload() {
                header("location: test.php");
                die;
            }
        ?>
    </div>
</body>
</html>
