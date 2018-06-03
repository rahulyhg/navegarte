<?php

/**
 * VCWeb <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 2017-2018 Vagner Cardoso
 */

// Adicionar as funcoes custum

if (!function_exists('onlyNumber')) {
    /**
     * Retorna apenas os números de uma string passada
     *
     * @param string $value
     *
     * @return mixed
     */
    function onlyNumber($value)
    {
        return preg_replace('/[^0-9]/', '', $value);
    }
}

if (!function_exists('json_trigger')) {
    /**
     * Gera a trigger no padrão das requisições ajax
     *
     * @param string $message
     * @param int    $type
     * @param int    $status
     *
     * @return \Slim\Http\Response
     */
    function json_trigger($message, $type = null, $status = 200)
    {
        switch ($type) {
            case E_USER_NOTICE:
            case E_NOTICE:
                $type = 'info';
                break;
            case E_USER_WARNING:
            case E_WARNING:
                $type = 'warning';
                break;
            case E_USER_ERROR:
            case E_ERROR:
                $type = 'danger';
                break;
            case 'success':
                $type = 'success';
                break;
            
            default:
                $type = 'danger';
        }
        
        return json(['trigger' => [$type, $message]], $status);
    }
}

if (!function_exists('link_youtube')) {
    /**
     * Recupera o código do vídeo do Youtube
     *
     * @param string $url
     *
     * @return string|bool
     */
    function link_youtube($url)
    {
        if (strpos($url, 'youtu.be/')) {
            preg_match('/(https:|http:|)(\/\/www\.|\/\/|)(.*?)\/(.{11})/i', $url, $matches);
            
            return $matches[4];
        } else if (strstr($url, "/v/")) {
            $aux = explode("v/", $url);
            $aux2 = explode("?", $aux[1]);
            $cod_youtube = $aux2[0];
            
            return $cod_youtube;
        } else if (strstr($url, "v=")) {
            $aux = explode("v=", $url);
            $aux2 = explode("&", $aux[1]);
            $cod_youtube = $aux2[0];
            
            return $cod_youtube;
        } else if (strstr($url, "/embed/")) {
            $aux = explode("/embed/", $url);
            $cod_youtube = $aux[1];
            
            return $cod_youtube;
        } else if (strstr($url, "be/")) {
            $aux = explode("be/", $url);
            $cod_youtube = $aux[1];
            
            return $cod_youtube;
        }
        
        return false;
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
        $folder = "fotos/{$table}/{$id}";
        $name = mb_strtoupper($name, 'UTF-8');
        
        /**
         * Pega as imagem
         */
        if (file_exists(PUBLIC_FOLDER."/{$folder}") && is_dir(PUBLIC_FOLDER."/{$folder}")) {
            if (file_exists(PUBLIC_FOLDER."/{$folder}/{$name}.{$ext}")) {
                return "/{$folder}/{$name}.{$ext}";
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
        $path = "fotos/{$table}/{$id}";
        $name = mb_strtoupper($name, 'UTF-8');
        $array = [];
        
        /**
         * Pega as imagem
         */
        if (file_exists(PUBLIC_FOLDER."/{$path}/galeria_{$name}")) {
            $images = array_values(array_diff(scandir(PUBLIC_FOLDER."/{$path}/galeria_{$name}/0"), ['.', '..']));
            
            foreach ($images as $key => $image) {
                if (strpos($image, '.jpg') !== false || strpos($image, '.png') !== false) {
                    $array[] = "/{$path}/galeria_{$name}/%s/{$image}";
                }
            }
        }
        
        return $array;
    }
}

if (!function_exists('get_month')) {
    /**
     * Retorna o mes do ano em pt-BR
     *
     * @param string $month
     *
     * @return string
     */
    function get_month($month)
    {
        $months = [
            '01' => 'Janeiro',
            '02' => 'Fevereiro',
            '03' => 'Março',
            '04' => 'Abril',
            '05' => 'Maio',
            '06' => 'Junho',
            '07' => 'Julho',
            '08' => 'Agosto',
            '09' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro',
        ];
        
        if (array_key_exists($month, $months)) {
            return $months[$month];
        }
        
        return '';
    }
}

if (!function_exists('get_day')) {
    /**
     * Retorna o dia da semana pt-BR
     *
     * @param string $day
     *
     * @return string
     */
    function get_day($day)
    {
        $days = [
            '0' => 'Domingo',
            '1' => 'Segunda Feira',
            '2' => 'Terça Feira',
            '3' => 'Quarta Feira',
            '4' => 'Quinta Feira',
            '5' => 'Sexta Feira',
            '6' => 'Sábado',
        ];
        
        if (array_key_exists($day, $days)) {
            return $days[$day];
        }
        
        return '';
    }
}
