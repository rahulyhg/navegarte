<?php

/**
 * VCWeb <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 2017-2017 Vagner Cardoso
 */

// Adicionar as funcoes custum

if (!function_exists('onlyNumber')) {
    /**
     * Retorna apenas os números de uma string passada
     *
     * @param $value
     *
     * @return mixed
     */
    function onlyNumber($value)
    {
        return preg_replace('/[^0-9]/', '', $value);
    }
}

if (!function_exists('convert_minutes')) {
    /**
     * Converte os minutos
     *
     * @param      $time
     * @param null $full
     *
     * @return string
     */
    function convert_minutes($time, $full = null)
    {
        $string = '';

        if ($time > 0) {
            // Transforma em segundos
            $seconds = $time * 60;
            $days = floor($seconds / 86400);
            $seconds -= $days * 86400;
            $hours = floor($seconds / 3600);
            $seconds -= $hours * 3600;
            $minutes = floor($seconds / 60);
            $seconds -= $minutes * 60;

            if ($days > 0 && $full) {
                $string .= "{$days}d ";
            }

            if ($hours > 0) {
                $string .= "{$hours}h ";
            }

            $string .= "{$minutes}m ";

            if ($full) {
                $string .= "{$seconds}s";
            }
        }

        return $string;
    }
}

if (!function_exists('link_youtube')) {
    /**
     * Transforma a url passada pegando o seu hash
     *
     * @param string $url
     *
     * @return mixed
     */
    function link_youtube($url)
    {
        if (strstr($url, "/v/")) {
            $aux = explode("v/", $url);
            $aux2 = explode("?", $aux[1]);
            $cod_youtube = $aux2[0];

            return $cod_youtube;
        } elseif (strstr($url, "v=")) {
            $aux = explode("v=", $url);
            $aux2 = explode("&", $aux[1]);
            $cod_youtube = $aux2[0];

            return $cod_youtube;
        } elseif (strstr($url, "/embed/")) {
            $aux = explode("/embed/", $url);
            $cod_youtube = $aux[1];

            return $cod_youtube;
        } elseif (strstr($url, "be/")) {
            $aux = explode("be/", $url);
            $cod_youtube = $aux[1];

            return $cod_youtube;
        }
    }
}

if (!function_exists('get_image')) {
    /**
     * Recupera a imagem do asset
     *
     * @param string     $table
     * @param int|string $id
     * @param string     $name
     * @param string     $ext
     *
     * @return bool|string
     */
    function get_image($table, $id, $name, $ext = 'jpg')
    {
        /*if (strpos($table, 'pm_') === false) {
            $table = "pm_{$table}";
        }*/

        $folder = "fotos/{$table}/{$id}";

        $name = mb_strtoupper($name, 'UTF-8');

        /**
         * Pega as imagem
         */
        if (file_exists(PUBLIC_FOLDER . "/{$folder}") && is_dir(PUBLIC_FOLDER . "/{$folder}")) {
            if (file_exists(PUBLIC_FOLDER . "/{$folder}/{$name}_0.{$ext}")) {
                return asset("/{$folder}/{$name}_0.{$ext}");
            }
        }

        return false;
    }
}

if (!function_exists('get_galeria')) {
    /**
     * Recupera a imagem do asset
     *
     * @param string       $table
     * @param int|string   $id
     * @param string|array $name
     *
     * @return array|bool|string
     */
    function get_galeria($table, $id, $name)
    {
        $imagemGaleria = [];

        if (!is_array($name)) {
            $name = (array) $name;
        }

        /*if (strpos($table, 'pm_') === false) {
            $table = "pm_{$table}";
        }*/

        $folder = "fotos/{$table}/{$id}";

        $name = array_map(
            function ($name) {
                return mb_strtoupper($name, 'UTF-8');
            }, $name
        );

        /**
         * Pega as imagem
         */
        if (file_exists(PUBLIC_FOLDER . "/{$folder}") && is_dir(PUBLIC_FOLDER . "/{$folder}")) {
            foreach ($name as $n) {
                if (file_exists(PUBLIC_FOLDER . "/{$folder}/galeria_{$n}/0")) {
                    $imagemGaleria[$n] = array_diff(scandir(PUBLIC_FOLDER . "/{$folder}/galeria_{$n}/0"), ['.', '..']);
                }
            }

            foreach ($imagemGaleria as $id => $images) {
                foreach ($images as $k => $image) {
                    $imagemGaleria[$id][$k] = asset("{$folder}/galeria_{$id}/0/{$image}");
                }
            }

            return $imagemGaleria;
        }

        return false;
    }
}

if (!function_exists('get_month')) {
    /**
     * Get name month
     *
     * @param $month
     *
     * @return string
     */
    function get_month($month)
    {
        $return = '---';

        switch ($month) {
            case '01':
                $return = 'Janeiro';
                break;
            case '02':
                $return = 'Fevereiro';
                break;
            case '03':
                $return = 'Março';
                break;
            case '04':
                $return = 'Abril';
                break;
            case '05':
                $return = 'Maio';
                break;
            case '06':
                $return = 'Junho';
                break;
            case '07':
                $return = 'Julho';
                break;
            case '08':
                $return = 'Agosto';
                break;
            case '09':
                $return = 'Setembro';
                break;
            case '10':
                $return = 'Outubro';
                break;
            case '11':
                $return = 'Novembro';
                break;
            case '12':
                $return = 'Dezembro';
                break;
        }

        return $return;
    }
}
