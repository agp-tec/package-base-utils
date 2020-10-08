<?php

namespace Agp\BaseUtils\Helper;

use DateTime;
use Illuminate\Support\Str;

/**
 * Class Utils
 * @package App\Utils
 */
class Utils
{
    /**
     * Mostra quanto tempo da ocorrência até agora se passou. Ex.: há 2 dias, 1 hora, 1 min, 18 segs
     * @param $date
     *      Tempo da ocorrência
     * @param $full
     *      Mostra segundos
     * @return string
     *
     */
    public static function getTimeElapsed($date, $full)
    {
        $now = new DateTime();
        $ago = new DateTime($date);
        $diff = $now->diff($ago);

        if ($diff->days > 7)
            return 'há ' . $diff->days . ' dias, ' . $ago->format('H:m d/m');

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'ano',
            'm' => 'mês',
            'w' => 'sem',
            'd' => 'dia',
            'h' => 'hora',
            'i' => 'min',
            's' => 'seg',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? 'há ' . implode(', ', $string) : 'agora';
    }

    /**
     * @param $uglyName
     * @param bool $remModName
     * @param bool $trimSpaces
     * @param bool $ucFirst
     * @return string
     */
    public static function getPrettyNames($uglyName, $remModName = false, $trimSpaces = true, $ucFirst = true)
    {
        $name = $uglyName;
        $name = str_replace('_', ' ', $name);
        $name = ucwords($name);
        if ($trimSpaces)
            $name = str_replace(' ', '', $name);
        if ($remModName)
            $name = substr($name, 3);
        return $ucFirst ? ucfirst($name) : lcfirst($name);
    }

    /**
     * Retorna o IP da requisição na hierarquia: HTTP_X_REAL_IP, HTTP_CLIENT_IP, HTTP_X_FORWARDED_FOR, request()->ip()
     */
    public static function getIpRequest()
    {
        if (!empty($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return $_SERVER['HTTP_X_FORWARDED_FOR'];
        return request()->ip();
    }

    /** Retorna o user agent como paremtro de api ou da requisição
     * @return mixed
     */
    public static function getUserAgent()
    {
        if (request()->get('client')) return request()->get('client')['user_agent']; //Se possuir client, é chamada de API
        return request()->userAgent();
    }

    /** Retorna o dígito do módulo 10
     * @param string $dado
     * @return int
     */
    public static function mod10(string $dado)
    {
        $mult = 2;
        $soma = 0;
        $s = "";
        for ($i = Str::length($dado) - 1; $i >= 0; $i--) {
            $s = ($mult * (int)$dado[$i]) . $s;
            if (--$mult < 1) {
                $mult = 2;
            }
        }
        for ($i = 0; $i < Str::length($s); $i++) {
            $soma = $soma + ((int)$s[$i]);
        }
        $soma = $soma % 10;
        if ($soma != 0) {
            $soma = 10 - $soma;
        }
        return $soma;
    }

    /** Retorna a diferença de $str_interval entre duas datas
     * @param string $str_interval Indica o formato de resposta y=anos, m=meses, d=dias, h=horas, i=minutos, s=segundos
     * @param string|DateTime $dt_menor Data menor
     * @param string|DateTime $dt_maior Data maior
     * @param bool $relative Indica se resultado mostra negativo
     * @return int
     */
    public static function date_diff($str_interval, $dt_menor, $dt_maior, $relative = false)
    {

        if (is_string($dt_menor)) $dt_menor = date_create($dt_menor);
        if (is_string($dt_maior)) $dt_maior = date_create($dt_maior);

        $diff = date_diff($dt_menor, $dt_maior, !$relative);
        $total = 0;
        switch ($str_interval) {
            case "y":
                $total = $diff->y + $diff->m / 12 + $diff->d / 365.25;
                break;
            case "m":
                $total = $diff->y * 12 + $diff->m + $diff->d / 30 + $diff->h / 24;
                break;
            case "d":
                $total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h / 24 + $diff->i / 60;
                break;
            case "h":
                $total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i / 60;
                break;
            case "i":
                $total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s / 60;
                break;
            case "s":
                $total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i) * 60 + $diff->s;
                break;
        }
        if ($diff->invert)
            return -1 * $total;
        return $total;
    }

    /**
     * Format a number with grouped thousands
     * @link https://php.net/manual/en/function.number-format.php
     * @param float $number <p>
     * The number being formatted.
     * </p>
     * @param int $decimals [optional] <p>
     * Sets the number of decimal points.
     * </p>
     * @param string $dec_point [optional]
     * @param string $thousands_sep [optional]
     * @return string A formatted version of number.
     */
    public static function number_format($number, $decimals = 2, $dec_point = ',', $thousands_sep = '.')
    {
        return number_format($number, $decimals, $dec_point, $thousands_sep);
    }
}
