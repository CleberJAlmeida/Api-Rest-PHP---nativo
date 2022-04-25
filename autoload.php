<?php
spl_autoload_register(
    function (string $nomeCompletodaClasse) {
        $diretorio = __DIR__ . DIRECTORY_SEPARATOR;
        $caminhoArquivo = $diretorio . str_replace('\\', DIRECTORY_SEPARATOR, $nomeCompletodaClasse);
        $caminhoArquivo .= '.php';
        if (file_exists($caminhoArquivo)) {
            require_once $caminhoArquivo;
        }
    }
);
