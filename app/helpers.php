<?php

/**
 * VCWeb Networks <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb Networks
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 13/01/2018 Vagner Cardoso
 */

use Core\App;
use Core\Helpers\Helper;
use Core\Helpers\Str;
use Slim\Http\StatusCode;

/**
 * CONSTANTS
 */

if (!defined('DATE_BR')) {
    define('DATE_BR', 'd/m/Y');
}

if (!defined('DATE_TIME_BR')) {
    define('DATE_TIME_BR', 'd/m/Y H:i:s');
}

/**
 * FUNCTIONS
 */

if (!function_exists('validate_params')) {
    /**
     * Realiza a validação do post
     *
     * @param array $params
     * @param array $rules
     */
    function validate_params(array $params, array $rules)
    {
        // Percorre os parâmetros
        foreach ($rules as $index => $rule) {
            // Força checagem
            if (!empty($rule['force']) && $rule['force'] == true) {
                if (!array_key_exists($index, $params)) {
                    $params[$index] = null;
                }
            }
            
            // Verifica caso esteja preenchido
            if (!empty($params[$index]) && is_array($params[$index])) {
                $params[$index] = array_filter($params[$index]);
            }
            
            if (array_key_exists($index, $params) && (empty($params[$index]) && $params[$index] != '0')) {
                if (!empty($rule['id']) && $rule['id'] == true) {
                    continue;
                } else {
                    if (is_string($rule)) {
                        throw new \InvalidArgumentException($rule, E_USER_NOTICE);
                    } else {
                        $code = (!empty($rule['code']) ? $rule['code'] : E_USER_NOTICE);
                        
                        throw new \InvalidArgumentException($rule['message'], $code);
                    }
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
    function json_trigger($message, $type = 'success', array $params = [], $status = StatusCode::HTTP_OK)
    {
        return json(array_merge([
            'trigger' => [error_type($type), $message],
        ], $params), $status);
    }
}

if (!function_exists('json_error')) {
    /**
     * Gera o erro no padrão das requisições api
     *
     * @param \Exception $exception
     * @param array $params
     * @param int $status
     *
     * @return \Slim\Http\Response
     */
    function json_error($exception, array $params = [], $status = StatusCode::HTTP_BAD_REQUEST)
    {
        return json(array_merge([
            'error' => [
                'code' => $exception->getCode(),
                'status' => $status,
                'type' => error_type($exception->getCode()),
                'file' => str_replace([
                    APP_FOLDER,
                    PUBLIC_FOLDER,
                    RESOURCE_FOLDER,
                ], '', $exception->getFile()),
                'line' => $exception->getLine(),
                'message' => $exception->getMessage(),
            ],
        ], $params), $status);
    }
}

if (!function_exists('json_success')) {
    /**
     * Gera o sucesso no padrão das requisições apis
     *
     * @param string $message
     * @param array $params
     * @param int $status
     *
     * @return \Slim\Http\Response
     */
    function json_success($message, array $params = [], $status = StatusCode::HTTP_OK)
    {
        // Caso seja web
        if (in_web()) {
            // Caso seja retorno pra API remove
            if (!empty($params['data'])) {
                unset($params['data']);
            }
            
            // Caso a mensagem seja vázia
            // envia apenas os parametros e status
            if (empty($message)) {
                return json($params, $status);
            }
            
            return json_trigger($message, 'success', $params, $status);
        }
        
        // Filtra os parametros caso seja da web
        $params = array_filter($params, function ($param) {
            if (!in_array($param, [
                'storage',
                'object',
                'clear',
                'trigger',
                'switch',
                'location',
                'reload',
            ])) {
                return $param;
            }
        }, ARRAY_FILTER_USE_KEY);
        
        return json(array_merge([
            'error' => false,
            'message' => $message,
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
        if (!empty($id) && $id != '0') {
            $name = mb_strtoupper($name, 'UTF-8');
            $path = "/fotos/{$table}/{$id}/{$name}";
            
            foreach ([$extension, strtoupper($extension)] as $ext) {
                if ($asset = asset("{$path}.{$ext}", $baseUrl, $version)) {
                    return $asset;
                }
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

if (!function_exists('upload')) {
    /**
     * Upload de arquivos/images
     *
     * @param array $file
     * @param string $directory
     * @param string $name
     * @param int $width
     * @param int $height
     * @param bool $forceJpg
     * @param bool $whExact
     *
     * @return array
     * @throws \Exception
     */
    function upload(array $file, $directory, $name = null, $width = 500, $height = 500, $forceJpg = false, $whExact = false)
    {
        $extFiles = ['zip', 'rar', 'pdf', 'docx'];
        $extImages = ['jpg', 'jpeg', 'png', 'gif'];
        $extensions = array_merge($extFiles, $extImages);
        $uploads = [];
        
        // Percore os arquivos
        foreach ($file as $key => $value) {
            $extension = mb_strtolower(substr(strrchr($value['name'], '.'), 1), 'UTF-8');
            $name = (empty($name) ? Str::slug(substr($value['name'], 0, strrpos($value['name'], '.'))) : $name);
            
            // Muda extenção caso seja JPEG
            if ($extension == 'jpeg' || ($forceJpg === true && in_array($extension, $extImages))) {
                $extension = 'jpg';
            }
            
            // Path do arquivo
            $path = "{$directory}/{$name}.{$extension}";
            
            // Checa extension
            if (in_array($extension, $extImages)) {
                if (!in_array($extension, $extImages)) {
                    throw new \Exception("Opsss, apenas as extenções <b>".strtoupper(implode(', ', $extImages))."</b> são aceita para enviar sua imagem.", E_USER_ERROR);
                }
            } else {
                if (!in_array($extension, $extensions)) {
                    throw new \Exception("Opsss, apenas as extenções <b>".strtoupper(implode(', ', $extensions))."</b> são aceita para enviar seu arquivo.", E_USER_ERROR);
                }
            }
            
            // Checa tamanho
            if (($value['size'] > $max_filesize = get_upload_max_filesize()) || $value['error'] == 1) {
                throw new \Exception("Opsss, seu upload ultrapassou o limite de tamanho de <b>".Helper::convertBytes($max_filesize)."</b>.", E_USER_ERROR);
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
            
            // Corrige orientação da imagem
            // Normalmente quando é enviada pelo celular
            if ($extension == 'jpg' && in_array($value['type'], ['image/jpeg', 'image/jpg'])) {
                upload_fix_orientation($value['tmp_name'], $extension);
            }
            
            // Verifica se é arquivo ou imagem para upload
            $uploadError = upload_error($value['error']);
            
            if (in_array($extension, $extFiles) || $extension === 'gif') {
                if (!move_uploaded_file($value['tmp_name'], PUBLIC_FOLDER.$path)) {
                    throw new \Exception("<p>Não foi possível enviar seu arquivo no momento!</p><p>{$uploadError}</p>", E_USER_ERROR);
                }
            } else {
                // Verifica se é o tamanho exato da imagem
                if ($whExact === true) {
                    $fnImg = 'imagemTamExato';
                } else {
                    $fnImg = 'imagem';
                    
                    // Calcula o tamanho com base no original
                    list($widthOri, $heightOri) = getimagesize($value['tmp_name']);
                    $width = ($width > $widthOri ? $widthOri : $width);
                    $height = ($height > $heightOri ? $heightOri : $height);
                }
                
                if (!$fnImg($value['tmp_name'], PUBLIC_FOLDER.$path, $width, $height, 90)) {
                    throw new \Exception("<p>Não foi possível enviar sua imagem no momento!</p><p>{$uploadError}</p>", E_USER_ERROR);
                }
            }
            
            $uploads[] = [
                'name' => $name,
                'path' => $path,
                'extension' => $extension,
                'size' => $value['size'],
                'md5' => md5_file(PUBLIC_FOLDER.$path),
            ];
        }
        
        return $uploads;
    }
}

if (!function_exists('upload_fix_orientation')) {
    /**
     * Corrige orientação da imagem
     *
     * @param string $pathImage [Caminho do arquivo ou file enviado pelo formulário]
     * @param string $extension
     */
    function upload_fix_orientation($pathImage, $extension)
    {
        if (file_exists($pathImage) && function_exists('exif_read_data')) {
            // Variáveis
            $exifData = exif_read_data($pathImage);
            $originalImage = null;
            $rotateImage = null;
            
            // Verifica se existe a orientação na imagem
            if (!empty($exifData['Orientation'])) {
                // Verifica a orientação e ajusta a rotação
                switch ($exifData['Orientation']) {
                    case 3:
                        $rotation = 180;
                        break;
                    
                    case 6:
                        $rotation = -90;
                        break;
                    
                    case 8:
                        $rotation = 90;
                        break;
                    
                    default:
                        $rotation = null;
                }
                
                // Caso a rotação e extenção seja válida
                if ($rotation !== null && $extension !== null) {
                    // Cria a imagem original dependendo da extenção
                    switch ($extension) {
                        case 'jpg':
                        case 'jpeg':
                            $originalImage = imagecreatefromjpeg($pathImage);
                            break;
                        
                        case 'png':
                            $originalImage = imagecreatefrompng($pathImage);
                            imagealphablending($originalImage, false);
                            imagesavealpha($originalImage, true);
                            break;
                        
                        case 'gif':
                            $originalImage = imagecreatefromgif($pathImage);
                            break;
                    }
                    
                    // Rotaciona a imagem corretamente
                    $rotateImage = imagerotate($originalImage, $rotation, 0);
                    
                    // Cria a imagem
                    switch ($extension) {
                        case 'jpg':
                        case 'jpeg':
                            imagejpeg($rotateImage, $pathImage, 100);
                            break;
                        
                        case 'png':
                            imagepng($rotateImage, $pathImage, 80);
                            break;
                        
                        case 'gif':
                            imagegif($rotateImage, $pathImage);
                            break;
                    }
                    
                    // Destroi as imagens
                    imagedestroy($originalImage);
                    imagedestroy($rotateImage);
                }
            }
        }
    }
}

if (!function_exists('upload_error')) {
    /**
     * Recupera o tipo do erro do upload
     *
     * @param int $code
     *
     * @return string
     */
    function upload_error($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = "O arquivo enviado excede o limite definido na diretiva `upload_max_filesize` do php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "O arquivo excede o limite definido em `MAX_FILE_SIZE` no formulário HTML.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "O upload do arquivo foi feito parcialmente.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "Nenhum arquivo foi enviado.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Pasta temporária ausênte.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Falha em escrever o arquivo em disco.";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "Uma extensão do PHP interrompeu o upload do arquivo.";
                break;
            
            default:
                $message = "";
                break;
        }
        
        return $message;
    }
}

if (!function_exists('upload_image')) {
    /**
     * Upload de imagem
     *
     * @param array $file
     * @param string $directory
     * @param string $name
     * @param int $width
     * @param int $height
     * @param bool $forceJpg
     * @param bool $whExact
     *
     * @return array
     * @throws \Exception
     */
    function upload_image($file, $directory, $name = null, $width = 500, $height = 500, $forceJpg = false, $whExact = false)
    {
        return upload($file, $directory, $name, $width, $height, $forceJpg, $whExact);
    }
}

if (!function_exists('upload_archive')) {
    /**
     * Upload de arquivo
     *
     * @param array $file
     * @param string $directory
     * @param string $name
     *
     * @return array
     * @throws \Exception
     */
    function upload_archive($file, $directory, $name = null)
    {
        return upload($file, $directory, $name);
    }
}

if (!function_exists('delete_recursive_directory')) {
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
        if (empty($dateTime)) {
            return '-';
        }
        
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
        $currentTime = datetime()->getTimestamp();
        $dateTime = datetime($dateTime)->getTimestamp();
        
        // Quanto tempo já passou da data atual - a data passada
        if ($dateTime > $currentTime) {
            $passed = $dateTime - $currentTime;
        } else {
            $passed = $currentTime - $dateTime;
        }
        
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
        $string = preg_replace('/\r\n|\r|\n|\t/m', '', $string);
        $string = preg_replace('/^\s+|\s+$|\s+(?=\s)/m', '', $string);
        
        // Remove tag `p` vázia
        $string = preg_replace('/<p[^>]*>[\s\s|&nbsp;]*<\/p>/m', '', $string);
        
        // Remove todas tags vázia
        //$string = preg_replace('/<[\w]*[^>]*>[\s\s|&nbsp;]*<\/[\w]*>/m', '', $string);
        
        return $string;
    }
}

if (!function_exists('database_format_money')) {
    /**
     * @param string|int|float $money
     *
     * @return mixed
     */
    function database_format_money($money)
    {
        return database_format_float($money);
    }
}

if (!function_exists('database_format_float')) {
    /**
     * @param string|int|float $value
     *
     * @return mixed
     */
    function database_format_float($value)
    {
        $value = str_replace(',', '.', str_replace('.', '', $value));
        
        return (float) $value;
    }
}

if (!function_exists('database_format_datetime')) {
    /**
     * @param string|null $dateTime
     * @param string $type
     *
     * @return string
     */
    function database_format_datetime($dateTime = 'now', $type = 'full')
    {
        $dateFormat = 'Y-m-d';
        $timeFormat = 'H:i:s';
        $dateTimeFormat = "{$dateFormat} {$timeFormat}";
        
        return datetime($dateTime)->format(
            ($type == 'time' ? $timeFormat : ($type == 'date' ? $dateFormat : $dateTimeFormat))
        );
    }
}

if (!function_exists('datetime')) {
    /**
     * @param string|\DateTime $dateTime
     * @param \DateTimeZone|null $timeZone
     *
     * @return \DateTime
     */
    function datetime($dateTime = 'now', \DateTimeZone $timeZone = null)
    {
        if (empty($dateTime)) {
            return null;
        }
        
        // Data atual
        if ($dateTime === 'now') {
            $dateTime = (new \DateTime($dateTime, $timeZone));
        }
        
        // Verifica a data passada
        if (!$dateTime instanceof \DateTimeInterface) {
            if (is_int($dateTime)) {
                $dateTime = \DateTime::createFromFormat('U', $dateTime, $timeZone);
            } else {
                try {
                    $dateTime = str_replace('/', '-', $dateTime);
                    $dateTime = (new \DateTime($dateTime, $timeZone));
                } catch (Exception $e) {
                    throw new InvalidArgumentException("Data <b>{$dateTime}</b> informada não é válida, favor corriga.");
                }
            }
        }
        
        return $dateTime;
    }
}

if (!function_exists('check_phone')) {
    /**
     * @param string|int $phone
     *
     * @return bool|string
     */
    function check_phone(&$phone)
    {
        $phone = onlyNumber($phone);
        
        if (strlen($phone) < 10 || strlen($phone) > 12) {
            return false;
        }
        
        return $phone;
    }
}

if (!function_exists('flash')) {
    /**
     * Cria as flash message simplificado
     *
     * @param $name
     * @param $value
     * @param string|int|null $error
     */
    function flash($name, $value, $error = null)
    {
        if (in_web()) {
            if (!empty($error)) {
                $value = [$value, error_type($error)];
            }
            
            App::getInstance()
                ->resolve('flash')
                ->add($name, $value);
        }
    }
}

if (!function_exists('in_web')) {
    /**
     * Verifica se está no site
     *
     * return bool
     */
    function in_web()
    {
        /** @var \Slim\Http\Request $request */
        $request = App::getInstance()->resolve('request');
        
        if (!empty($request->getHeaderLine('X-Csrf-Token')) || !empty(params('_csrfToken'))) {
            return true;
        }
        
        if ((empty($_SERVER['HTTP_REFERER']) && empty($_SERVER['HTTP_ORIGIN'])) && has_route('/api/')) {
            return false;
        }
        
        return true;
    }
}

if (!function_exists('placeholder')) {
    /**
     * @param int $dimension
     * @param array $params
     *
     * @return string
     */
    function placeholder($dimension = 150, $params = [])
    {
        $params = http_build_query($params);
        
        return "//via.placeholder.com/{$dimension}?{$params}";
    }
}

if (!function_exists('cnh_validate')) {
    /**
     * Valida as categorias do CHN
     *
     * @param string $cnh (A,B)...
     *
     * @return string
     */
    function cnh_validate($cnh)
    {
        if (empty($cnh)) {
            return '';
        }
        
        // Variáveis
        $max = 2;
        $valids = ['A', 'B', 'C', 'D', 'E'];
        $possible = ['A,B', 'A,C', 'A,D', 'A,E'];
        $possibleReverse = array_map(function ($item) {
            $reverse = array_reverse(explode(',', $item));
            
            return implode(',', $reverse);
        }, $possible);
        
        if (is_string($cnh)) {
            $cnh = explode(',', $cnh);
        }
        
        if (count($cnh) > $max) {
            throw new \InvalidArgumentException("È possível selecionar apenas 2 categorias da CNH.", E_USER_WARNING);
        }
        
        foreach ($cnh as $cn) {
            if (array_search($cn, $valids) === false) {
                throw new \InvalidArgumentException(
                    sprintf("Categoria <b>%s</b> da CNH não é válida.", $cn), E_USER_ERROR
                );
            }
        }
        
        $cnh = implode(',', $cnh);
        
        if (strpos($cnh, ',') !== false && array_search($cnh, $merged = array_merge($possible, $possibleReverse)) === false) {
            throw new \InvalidArgumentException(
                sprintf("Categoria da CNH possíveis de junção é apenas <b>%s</b>.", implode(' ou ', $merged))
            );
        }
        
        return $cnh;
    }
}
