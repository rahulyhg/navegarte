<?php

/**
 * VCWeb <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb Networks
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
     * @param string $filter
     * @param string $message
     * @param int $code
     *
     * @return null
     */
    function filter_value($value, $filter = null, $message = null, $code = E_USER_WARNING)
    {
        if (empty($value) && $value != '0') {
            if (!empty($message)) {
                throw new \InvalidArgumentException($message, $code);
            } else {
                $value = '';
            }
        }
        
        if (!empty($value)) {
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

if (!function_exists('validate_post')) {
    /**
     * Realiza a validação do post
     *
     * @param array $post
     * @param array $params
     */
    function validate_post(array $post, array $params)
    {
        // Percorre os parâmetro do post
        foreach ($params as $index => $param) {
            // Força checagem
            if (!empty($param['force']) && $param['force'] == true) {
                if (!array_key_exists($index, $post)) {
                    $post[$index] = '';
                }
            }
            
            // Verifica caso esteja preenchido
            if (isset($post[$index]) && (empty($post[$index]) && $post[$index] != '0')) {
                if (is_string($param)) {
                    throw new InvalidArgumentException($param, E_USER_WARNING);
                } else {
                    $code = (!empty($param['code']) ? $param['code'] : E_USER_WARNING);
                    
                    throw new InvalidArgumentException($param['message'], $code);
                }
            }
        }
    }
}

if (!function_exists('json_trigger')) {
    /**
     * Gera a trigger no padrão das requisições ajax
     *
     * @param string $message
     * @param string|int $type
     * @param array $params
     * @param int $status
     *
     * @return \Slim\Http\Response
     */
    function json_trigger($message, $type = 'success', array $params = [], $status = 200)
    {
        return json(array_merge([
            'trigger' => [error_type($type), $message],
        ], $params), $status);
    }
}

if (!function_exists('json_error')) {
    /**
     * Gera o erro no padrão das requisições ajax
     *
     * @param \Exception $exception
     * @param array $params
     * @param int $status
     *
     * @return \Slim\Http\Response
     */
    function json_error($exception, array $params = [], $status = 200)
    {
        return json(array_merge([
            'error' => [
                'code' => $exception->getCode(),
                'type' => error_type($exception->getCode()),
                'file' => str_replace([APP_FOLDER, PUBLIC_FOLDER, RESOURCE_FOLDER], '', $exception->getFile()),
                'line' => $exception->getLine(),
                'message' => $exception->getMessage(),
            ],
        ], $params), $status);
    }
}

if (!function_exists('error_type')) {
    /**
     * Verifica o tipo de erro e retorna a classe css
     *
     * @param string|int $type
     *
     * @return string
     */
    function error_type($type)
    {
        if (is_string($type) && $type !== 'success') {
            $type = E_USER_ERROR;
        }
        
        switch ($type) {
            case E_USER_NOTICE:
            case E_NOTICE:
                $result = 'info';
                break;
            case E_USER_WARNING:
            case E_WARNING:
                $result = 'warning';
                break;
            case E_USER_ERROR:
            case E_ERROR:
            case '0':
                $result = 'danger';
                break;
            case 'success':
                $result = 'success';
                break;
            
            default:
                $result = 'danger';
        }
        
        return $result;
    }
}

if (!function_exists('get_image')) {
    /**
     * Recupera a imagem do asset
     *
     * @param string $table
     * @param int|string $id
     * @param string $name
     * @param bool $baseUrl
     * @param bool $version
     * @param string $extension
     *
     * @return bool|string
     */
    function get_image($table, $id, $name, $baseUrl = true, $version = true, $extension = 'jpg')
    {
        $name = mb_strtoupper($name, 'UTF-8');
        $path = "/fotos/{$table}/{$id}/{$name}";
        
        foreach ([$extension, strtoupper($extension)] as $ext) {
            if ($asset = asset("{$path}.{$ext}", $baseUrl, $version)) {
                return $asset;
            }
        }
        
        return '';
    }
}

if (!function_exists('get_galeria')) {
    /**
     * Recupera a imagem do asset
     *
     * @param string $table
     * @param int|string $id
     * @param string|array $name
     *
     * @return array|bool|string
     */
    function get_galeria($table, $id, $name)
    {
        $name = mb_strtoupper($name, 'UTF-8');
        $path = ["fotos/{$table}/{$id}/galeria_{$name}", "fotos/fotos_album/{$id}"];
        $array = [];
        $images = [];
        
        // Imagens antigas
        if (file_exists(PUBLIC_FOLDER."/{$path[1]}")) {
            $images = array_values(array_diff(scandir(PUBLIC_FOLDER."/{$path[1]}"), ['.', '..']));
            $path = $path[1];
        } else {
            // Imagens novas
            if (file_exists(PUBLIC_FOLDER."/{$path[0]}")) {
                $images = array_values(array_diff(scandir(PUBLIC_FOLDER."/{$path[0]}/0"), ['.', '..']));
                $path = "{$path[0]}/";
            }
        }
        
        // Percore as imagens
        foreach ($images as $key => $image) {
            if (preg_match('/(\.jpg|\.jpeg|\.png|\.gif)/i', $image)) {
                $array[] = "/{$path}%s/{$image}";
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
     * @param array $file
     * @param string $folder
     * @param string $name
     * @param int $width
     * @param int $height
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
            $name = (empty($name) ? Str::slug(substr($value['name'], 0, strrpos($value['name'], '.'))) : $name);
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
            
            // Verifica se e gif
            if ($extension == 'gif') {
                if (!move_uploaded_file($value['tmp_name'], PUBLIC_FOLDER.$path)) {
                    throw new \Exception("Não foi possível enviar sua imagem, tente novamente em alguns segundos.", E_USER_ERROR);
                }
            } else {
                // Tamanho original
                list($widthOri, $heightOri) = getimagesize($value['tmp_name']);
                
                if (!imagem($value['tmp_name'], PUBLIC_FOLDER.$path, ($width > $widthOri ? $widthOri : $width), ($height > $heightOri ? $heightOri : $height), 90)) {
                    throw new \Exception("Não foi possível enviar sua imagem, tente novamente em alguns segundos.", E_USER_ERROR);
                }
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
     * @param array $file
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

if (!file_exists('delete_recursive_directory')) {
    /**
     * Remove os arquivos e os diretórios do path passado
     *
     * @param string $path
     */
    function delete_recursive_directory($path)
    {
        if (file_exists($path)) {
            $interator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path),
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            
            $interator->rewind();
            
            while ($interator->valid()) {
                if (!$interator->isDot()) {
                    if ($interator->isFile()) {
                        unlink($interator->getPathname());
                    } else {
                        rmdir($interator->getPathname());
                    }
                }
                
                $interator->next();
            }
            
            rmdir($path);
        }
    }
}

if (!function_exists('date_for_human')) {
    /**
     * Diferença das data em olhos humanos
     *
     * @param int|string $dateTime
     * @param int $precision
     *
     * @return string
     */
    function date_for_human($dateTime, $precision = 2)
    {
        // Variáveis
        $minute = 60;
        $hour = 3600;
        $day = 86400;
        $week = 604800;
        $month = 2629743;
        $year = 31556926;
        $century = $year * 10;
        $decade = $century * 10;
        
        // Tempos
        $periods = array(
            $decade => array("decada", "decadas"),
            $century => array("seculo", "seculos"),
            $year => array("ano", "anos"),
            $month => array("mês", "mêses"),
            $week => array("semana", "semanas"),
            $day => array("dia", "dias"),
            $hour => array("hora", "horas"),
            $minute => array("minuto", "minutos"),
            1 => array("segundo", "segundos"),
        );
        
        // Time atual
        $currentTime = (new \DateTime())->getTimestamp();
        
        // Verifica a data passada
        if ($dateTime instanceof \DateTimeInterface) {
            $dateTime = $dateTime->getTimestamp();
        } else if (is_int($dateTime)) {
            $dateTime = DateTime::createFromFormat('U', $dateTime)->getTimestamp();
        } else {
            $dateTime = str_replace('/', '-', $dateTime);
            $dateTimeCheck = explode('-', explode(' ', $dateTime)[0]);
            
            if (!checkdate($dateTimeCheck[1], $dateTimeCheck[2], $dateTimeCheck[0])) {
                throw new \InvalidArgumentException("Date passed not valid.", E_USER_ERROR);
            }
            
            $dateTime = (new \DateTime($dateTime))->getTimestamp();
        }
        
        // Quanto tempo já passou da data atual - a data passada
        $passed = $currentTime - $dateTime;
        
        // Monta o resultado
        if ($passed < 5) {
            $output = "5 segundos";
        } else {
            $output = array();
            $exit = 0;
            
            foreach ($periods as $period => $name) {
                if ($exit >= $precision || $exit > 0 && $period < 1) {
                    break;
                }
                
                $result = floor($passed / $period);
                
                if ($result > 0) {
                    $output[] = $result." ".($result == 1 ? $name[0] : $name[1]);
                    $passed -= $result * $period;
                    $exit++;
                }
            }
            
            $output = implode(" e ", $output);
        }
        
        return $output;
    }
}

if (!function_exists('preg_replace_space')) {
    /**
     * Remove tags e espaços vázios
     *
     * @param string $string
     *
     * @return string
     */
    function preg_replace_space($string)
    {
        // Remove comentários
        $string = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $string);
        
        // Remove espaço com mais de um espaço
        $string = preg_replace('/^\s+|\s+$|\r\n|\r|\n|\t|\s+(?=\s)/m', '', $string);
        
        // Remove tag `p` vázia
        $string = preg_replace('/<p[^>]*>[\s\s|&nbsp;]*<\/p>/m', '', $string);
        
        // Remove todas tags vázia
        //$string = preg_replace('/<[\w]*[^>]*>[\s\s|&nbsp;]*<\/[\w]*>/m', '', $string);
        
        return $string;
    }
}
