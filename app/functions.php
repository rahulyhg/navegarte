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

if (!function_exists('json_trigger')) {
    /**
     * @param string $type
     * @param string $message
     * @param int    $status
     *
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    function json_trigger($type, $message = '', $status = 200)
    {
        if ($type == 'error' && empty($message)) {
            $type = 'danger';
            $message = 'Erro, atualize a página e tente novamente.';
            $status = 500;
        }
        
        return json(['trigger' => [$type, $message]], $status);
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
                $array[] = "/{$path}/galeria_{$name}/%s/{$image}";
            }
        }
        
        return $array;
    }
}

if (!function_exists('get_month')) {
    /**
     * Get name month
     *
     * @param string $month
     *
     * @return string|bool
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
        
        return false;
    }
}

if (!function_exists('get_day')) {
    /**
     * Get name day
     *
     * @param string $day
     *
     * @return string|bool
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
        
        return false;
    }
}
