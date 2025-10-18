<?php
// método encapsulador del envío por POST o GET de la información de los elementos del formulario
class ValorEncapsulado {
  public function obtenerValor($campo, $default = 0) {
    $valor = $default;

    if (isset($_POST[$campo])) {
      $valor = trim($_POST[$campo]);
    } elseif (isset($_GET[$campo])) {
      $valor = trim($_GET[$campo]);
    }

    return $valor;
  }
}
