<?php
spl_autoload_register(function ($name) {
    echo "Tentative de chargement de $name.\n";
    throw new MissingException("Impossible de charger $name.");
});

try {
    $obj = new NonLoadableClass();
} catch (Exception $e) {
    echo $e->getMessage(), "\n";
}
?>