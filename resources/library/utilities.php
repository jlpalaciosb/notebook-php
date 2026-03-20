<?php

$meses = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "setiembre", "octubre", "noviembre", "diciembre");

#Recibe = YYYY-MM
#Retorna = mes AÑO, ejemplo = mayo 2018
function legibleYM ($year_month) {
    return $GLOBALS["meses"][intval(substr($year_month, 5)) - 1] . " / " . substr($year_month, 0, 4);
}

#retorna la fecha en un formato más legible
function legibleYMD ($anho, $mes, $dia) {
    $str  = "";
    if ($dia < 10) {
        $str = $str . "0";
    }
    $str = $str . strval($dia) . " / ";
    $str = $str . $GLOBALS["meses"][$mes - 1] . " / ";
    $str = $str . strval($anho);
    return $str;
}

#retorna la fecha en un formato más legible de $dateuser
function legibleDate ($date) {
    $anho = intval(substr($date, 0, 4));
    $mes = intval(substr($date, 5, 2));
    $dia = intval(substr($date, 8, 2));
    return legibleYMD($anho, $mes, $dia);
}

#Retorna true si el string recibido $YM no es del formato YYYY-MM
function formatErrorYM ($ym) {
    $hasError = false;

    if (strlen($ym) != 7 || substr($ym, 4, 1) != "-") {
        $hasError = true;
    } else {
        $year = substr($ym, 0, 4);
        if (!ctype_digit($year) || intval($year) < 2000 || intval($year) > 2100) {
            $hasError = true;
        } else {
            $month = substr($ym, 5, 2);
            if (!ctype_digit($month) || intval($month) < 1 || intval($month) > 12) {
                $hasError = true;
            }
        }
    }

    return $hasError;
}

#Retorna true si el string recibido $YMD no es del formato YYYY-MM-DD
function formatErrorYMD ($ymd) {
    $date = DateTime::createFromFormat('Y-m-d', $ymd);
    return !($date && $date->format('Y-m-d') === $ymd);
}

#retorna un string en el formato de YYYY-MM correspondiente al mes anterior de $YEAR_MONTH
function mesAnterior($year_month) {
    $y = intval(substr($year_month, 0, 4));
    $m = intval(substr($year_month, 5));
    $m = $m - 1;
    if ($m == 0) {
        $m = 12;
        $y = $y - 1;
    }
    $s = "";
    if ($m < 10) {
        $s = "0";
    }
    return strval($y) . "-" . $s . strval($m);
}

#retorna un string en el formato de YYYY-MM correspondiente al mes siguiente de $YEAR_MONTH
function mesSiguiente($year_month) {
    $y = intval(substr($year_month, 0, 4));
    $m = intval(substr($year_month, 5));
    $m = $m + 1;
    if ($m == 13) {
        $m = 1;
        $y = $y + 1;
    }
    $s = "";
    if ($m < 10) {
        $s = "0";
    }
    return strval($y) . "-" . $s . strval($m);
}

#retorna la fecha actual en el formato de fecha que usamos
function getCurrentDate() {
    $ret = date("Y") . "-";
    $ret = $ret . date("m") . "-";
    $ret = $ret . date("d");
    return $ret;
}

#retorna dateuser con la fecha recibida
function toStringYMD($anho, $mes, $dia) {
    $str  = "";
    $str = $str . strval($anho) . "-";
    if ($mes < 10) {
        $str = $str . "0";
    }
    $str = $str . strval($mes) . "-";
    if ($dia < 10) {
        $str = $str . "0";
    }
    $str = $str . strval($dia);
    return $str;
}

function encrypt($content, $key) {
    return openssl_encrypt($content, 'AES-128-CBC', $key, 0, '0000000000000000');
}

function decrypt($crypted, $key) {
    return openssl_decrypt($crypted, 'AES-128-CBC', $key, 0, '0000000000000000');
}

# Carga variables de entorno desde archivo .env
# Si se pasa $variable, retorna su valor o el valor por defecto si no existe
function obtenerEnv($variable = null, $default = null) {
    static $env_cargado = false;

    if (!$env_cargado) {
        $env_file = realpath(dirname(__FILE__) . '/../../.env');
        if (file_exists($env_file)) {
            $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                # Ignorar comentarios y líneas sin =
                if (strpos($line, '#') === 0 || strpos($line, '=') === false) {
                    continue;
                }
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                # Remover comillas si existen
                $value = trim($value, '\'"');
                putenv($key . '=' . $value);
            }
        }
        $env_cargado = true;
    }

    if ($variable !== null) {
        $value = getenv($variable);
        return $value !== false ? $value : $default;
    } else {
        return null;
    }
}
