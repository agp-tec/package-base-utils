<?php

namespace Agp\BaseUtils\Helper;

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
        $now = new \DateTime();
        $ago = new \DateTime($date);
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
}
