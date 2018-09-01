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

use Core\Helpers\Str;

if (!function_exists('onlyNumber')) {
    /**
     * Retorna apenas os números de uma string passada
     *
     * @param string $value
     *
     * @return int|string
     */
    function onlyNumber($value)
    {
        if (!empty($value)) {
            return preg_replace('/[^0-9]/', '', $value);
        }
    }
}

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
        if ((empty($value) || $value == 'null' || $value == 'false') && $value != '0') {
            if (!empty($message)) {
                throw new Exception($message, $code);
            } else {
                return null;
            }
        }
        
        if (!empty($filter)) {
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
        }
        
        return $value;
    }
}

if (!function_exists('json_trigger')) {
    /**
     * Gera a trigger no padrão das requisições ajax
     *
     * @param string     $message
     * @param string|int $error
     * @param int        $status
     *
     * @return \Slim\Http\Response
     */
    function json_trigger($message, $error = 'success', $status = 200)
    {
        if (is_string($error) && $error !== 'success') {
            $error = E_USER_ERROR;
        }
        
        switch ($error) {
            case E_USER_NOTICE:
            case E_NOTICE:
                $error = 'info';
                break;
            case E_USER_WARNING:
            case E_WARNING:
                $error = 'warning';
                break;
            case E_USER_ERROR:
            case E_ERROR:
                $error = 'danger';
                break;
            case 'success':
                $error = 'success';
                break;
            
            default:
                $error = 'danger';
        }
        
        return json(['trigger' => [$error, $message]], $status);
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
                throw new \Exception("Sua imagem ultrapassou o limite de tamanho de <b>".bytes_convert($max_filesize)."</b>.", E_USER_ERROR);
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
                throw new \Exception("Seu arquivo ultrapassou o limite de tamanho de <b>".bytes_convert($max_filesize)."</b>.", E_USER_ERROR);
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

if (!function_exists('organize_multiple_files')) {
    /**
     * Reorganiza o array dos files
     *
     * @param array $files
     *
     * @return array
     */
    function organize_multiple_files($files)
    {
        $newFiles = [];
        $multiple = is_array($files);
        $fileCount = $multiple ? count($files['name']) : 1;
        $fileKeys = array_keys($files);
        
        for ($i = 0; $i < $fileCount; $i++) {
            foreach ($fileKeys as $fileKey) {
                $newFiles[$i][$fileKey] = $multiple ? $files[$fileKey][$i] : $files[$fileKey];
            }
        }
        
        return $newFiles;
    }
}

if (!function_exists('get_upload_max_filesize')) {
    /**
     * Converte o `filesize` máximo configurado
     * para upload de arquivos/images
     *
     * @return float|int
     */
    function get_upload_max_filesize()
    {
        $mb = ini_get('upload_max_filesize');
        $maxFileSize = 0;
        
        if (preg_match('/([0-9])+([a-zA-Z])/', $mb, $matche)) {
            switch ($matche[2]) {
                case 'K':
                case 'KB':
                    $maxFileSize = ($matche[1] * pow(1024, 1));
                    break;
                
                case 'M':
                case 'MB':
                    $maxFileSize = ($matche[1] * pow(1024, 2));
                    break;
                
                case 'G':
                case 'GB':
                    $maxFileSize = ($matche[1] * pow(1024, 3));
                    break;
                
                case 'T':
                case 'TB':
                    $maxFileSize = ($matche[1] * pow(1024, 4));
                    break;
                
                case 'P':
                case 'PB':
                    $maxFileSize = ($matche[1] * pow(1024, 5));
                    break;
            }
        }
        
        return $maxFileSize;
    }
}
