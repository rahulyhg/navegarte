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

use Core\Helpers\Helper;
use Core\Helpers\Str;

if (!function_exists('filter_value')) {
    /**
     * Verifica e formata o valor do post
     *
     * @param string|int|bool $value
     * @param string          $filter
     * @param string          $message
     * @param int             $code
     *
     * @return null
     * @throws \Exception
     */
    function filter_value($value, $filter = null, $message = null, $code = E_USER_WARNING)
    {
        if (empty($value) && $value != '0') {
            if (!empty($message)) {
                throw new Exception($message, $code);
            } else {
                return null;
            }
        }
        
        switch ($filter) {
            case 'onlyNumber':
                $value = onlyNumber($value);
                break;
            
            case 'dateDatabase':
                $value = date('Y-m-d', strtotime(str_replace('/', '-', $value)));
                break;
            
            case 'dateTimeDatabase':
                $value = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $value)));
                break;
            
            case 'moneyDatabase':
                $value = str_replace(',', '.', str_replace('.', '', $value));
                break;
        }
        
        return $value;
    }
}

if (!function_exists('json_trigger')) {
    /**
     * Gera a trigger no padrão das requisições ajax
     *
     * @param string     $message
     * @param string|int $code
     * @param array      $data
     * @param int        $status
     *
     * @return \Slim\Http\Response
     */
    function json_trigger($message, $code = 'success', array $data = [], $status = 200)
    {
        if (is_string($code) && $code !== 'success') {
            $code = E_USER_ERROR;
        }
        
        switch ($code) {
            case E_USER_NOTICE:
            case E_NOTICE:
                $code = 'info';
                break;
            case E_USER_WARNING:
            case E_WARNING:
                $code = 'warning';
                break;
            case E_USER_ERROR:
            case E_ERROR:
                $code = 'danger';
                break;
            case 'success':
                $code = 'success';
                break;
            
            default:
                $code = 'danger';
        }
        
        return json(array_merge([
            'trigger' => [$code, $message],
        ], $data), $status);
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

if (!function_exists('upload_image')) {
    /**
     * Upload de imagem
     *
     * @param array  $file
     * @param string $folder
     * @param string $name
     * @param int    $width
     * @param int    $height
     *
     * @return array
     * @throws \Exception
     */
    function upload_image($file, $folder, $name = null, $width = 500, $height = 500)
    {
        $directory = "/uploads/{$folder}";
        $extensions = ['jpg', 'png'];
        $images = [];
        
        foreach ($file as $key => $value) {
            $extension = substr(strrchr($value['name'], '.'), 1);
            $name = Str::slug((empty($name) ? substr($value['name'], 0, strrpos($value['name'], '.')) : $name));
            $path = "{$directory}/{$name}.{$extension}";
            
            if ($extension == 'jpeg') {
                $extension = 'jpg';
            }
            
            // Checa extension
            if (!in_array($extension, $extensions)) {
                throw new \Exception("Apenas as extenções <b>".strtoupper(implode(', ', $extensions))."</b> são aceito para enviar sua imagem.", E_USER_ERROR);
            }
            
            // Checa tamanho
            if (($value['size'] > $max_filesize = get_upload_max_filesize()) || $value['error'] == 1) {
                throw new \Exception("Sua imagem ultrapassou o limite de tamanho de <b>".Helper::convertBytes($max_filesize)."</b>.", E_USER_ERROR);
            }
            
            // Cria pasta
            if (!file_exists(PUBLIC_FOLDER.$directory)) {
                mkdir(PUBLIC_FOLDER.$directory, 0755, true);
            }
            
            // Verifica arquivo
            foreach ($extensions as $ext) {
                $deleted = str_replace(".{$extension}", ".{$ext}", $path);
                
                if (file_exists(PUBLIC_FOLDER."{$deleted}")) {
                    unlink(PUBLIC_FOLDER."{$deleted}");
                }
            }
            
            // Tamanho original
            list($widthOri, $heightOri) = getimagesize($value['tmp_name']);
            
            if (!imagem($value['tmp_name'], PUBLIC_FOLDER.$path, ($width > $widthOri ? $widthOri : $width), ($height > $heightOri ? $heightOri : $height), 90)) {
                throw new \Exception("Não foi possível enviar sua imagem, tente novamente em alguns segundos.", E_USER_ERROR);
            }
            
            $images[] = [
                'name' => $name,
                'path' => $path,
                'extension' => $extension,
                'size' => $value['size'],
                'md5' => md5_file(PUBLIC_FOLDER.$path),
            ];
        }
        
        return $images;
    }
}

if (!function_exists('upload_archive')) {
    /**
     * Upload de imagem
     *
     * @param array  $file
     * @param string $folder
     * @param string $name
     *
     * @return array
     * @throws \Exception
     */
    function upload_archive($file, $folder, $name = null)
    {
        $directory = "/uploads/{$folder}";
        $extensions = ['zip', 'rar', 'pdf', 'docx', 'jpg', 'png'];
        $archives = [];
        
        // Envia os arquivos
        foreach ($file as $key => $value) {
            $extension = substr(strrchr($value['name'], '.'), 1);
            $name = Str::slug((empty($name) ? substr($value['name'], 0, strrpos($value['name'], '.')) : $name));
            $path = "{$directory}/{$name}.{$extension}";
            
            // Checa extension
            if (!in_array($extension, $extensions)) {
                throw new \Exception("Apenas as extenções <b>".strtoupper(implode(', ', $extensions))."</b> são aceito para enviar o arquivo.", E_USER_ERROR);
            }
            
            // Checa tamanho
            if (($value['size'] > $max_filesize = get_upload_max_filesize()) || $value['error'] == 1) {
                throw new \Exception("Seu arquivo ultrapassou o limite de tamanho de <b>".Helper::convertBytes($max_filesize)."</b>.", E_USER_ERROR);
            }
            
            // Cria pasta
            if (!file_exists(PUBLIC_FOLDER.$directory)) {
                mkdir(PUBLIC_FOLDER.$directory, 0755, true);
            }
            
            // Verifica arquivo
            foreach ($extensions as $ext) {
                $deleted = str_replace(".{$extension}", ".{$ext}", $path);
                
                if (file_exists(PUBLIC_FOLDER.$deleted)) {
                    unlink(PUBLIC_FOLDER.$deleted);
                }
            }
            
            // Envia arquivo
            if (!move_uploaded_file($value['tmp_name'], PUBLIC_FOLDER.$path)) {
                throw new \Exception("Não foi possível enviar o arquivo, tente novamente em alguns segundos.", E_USER_ERROR);
            }
            
            $archives[] = [
                'name' => $name,
                'path' => $path,
                'extension' => $extension,
                'size' => $value['size'],
                'md5' => md5_file(PUBLIC_FOLDER.$path),
            ];
        }
        
        return $archives;
    }
}
