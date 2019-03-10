<?php

/**
 * VCWeb Networks <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb Networks
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 07/02/2019 Vagner Cardoso
 */

namespace App\Support {
    
    use App\Models\FreteWay\Cidade;
    use App\Models\FreteWay\Estado;
    use App\Models\FreteWay\Frete\Frete;
    use App\Models\FreteWay\Frete\MapLink;
    use Core\Helpers\Helper;
    
    /**
     * Class Kml
     *
     * @package App\Support
     * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
     */
    class Kml
    {
        /**
         * @param $codFrete
         *
         * @return string
         * @throws \Exception
         */
        public function output($codFrete)
        {
            // Variávies
            $codFrete = filter_params($codFrete)[0];
            $output = '';
            
            // Cria o dom
            $dom = new \DOMDocument('1.0', 'UTF-8');
            
            // Abre kml
            $kml = $dom->createElementNS('http://earth.google.com/kml/2.2', 'kml');
            $kml = $dom->appendChild($kml);
            
            // Verifica frete
            $mmaplink = new MapLink();
            $frete = (new Frete())->reset()
                ->select("*")
                ->join("LEFT JOIN {$mmaplink->table()} USING (COD_FRETE)")
                ->fetchById($codFrete);
            
            if (!empty($frete)) {
                // O Frete
                $origem = (new Cidade())->reset()->fetchById($frete['ORIGEM_FRETE']);
                $origemEstado = (new Estado())->reset()->fetchById($origem['ESTADO']);
                $destino = (new Cidade())->reset()->fetchById($frete['DESTINO_FRETE']);
                $destinoEstado = (new Estado())->reset()->fetchById($destino['ESTADO']);
                
                $distancia = (!empty($frete['DISTANCIA_SOLUCAO']) ? floor($frete['DISTANCIA_SOLUCAO'] / 1000) : 0);
                $media = (!empty($frete['MEDIA_SOLUCAO']) ? number_format($frete['MEDIA_SOLUCAO'], 2, '.', '') : 0);
                $duracao = (!empty($frete['DURACAO_SOLUCAO']) ? $this->calcDuration($frete['DURACAO_SOLUCAO']) : 0);
                
                // Document
                $document = $dom->createElement('Document');
                $documentNode = $kml->appendChild($document);
                $documentName = $dom->createElement('name', config('client.name')." - Frete #{$frete['COD_FRETE']}");
                $documentDescription = $dom->createElement('description', $freteDetalhe = "Detalhes do Frete #{$frete['COD_FRETE']} - {$origem['NOME_CIDADE']}/{$origemEstado['SIGLA_ESTADO']} - {$destino['NOME_CIDADE']}/{$destinoEstado['SIGLA_ESTADO']}");
                $documentNode->appendChild($documentName);
                $documentNode->appendChild($documentDescription);
                
                // Style padrão
                $style = $dom->createElement('Style');
                $style->setAttribute('id', 'PONTO-FRETE');
                $document->appendChild($style);
                $styleBalloon = $dom->createElement('BalloonStyle');
                $style->appendChild($styleBalloon);
                $styleBalloonText = $dom->createElement('text');
                $styleBalloonTextCData = $dom->createCDATASection("<div class='maplink-frete'><h2>{$freteDetalhe}</h2> Distância: <b>{$distancia} KM</b>. <br /> Duração: <b>{$duracao}</b>. <br /> Velocidade Média: <b>{$media} KM/h</b></div>");
                $styleBalloonText->appendChild($styleBalloonTextCData);
                $styleBalloon->appendChild($styleBalloonText);
                
                // Placemark retorno (rota)
                $retorno = Helper::checkJson($frete['RETORNO_SOLUCAO']);
                if ($retorno) {
                    // Cordenadas
                    $coordinates = '';
                    
                    if (!empty($retorno['legs'][0]['points'])) {
                        foreach ($retorno['legs'][0]['points'] as $point) {
                            $point['latitude'] = str_replace(',', '.', $point['latitude']);
                            $point['longitude'] = str_replace(',', '.', $point['longitude']);
                            
                            $coordinates .= "{$point['longitude']},{$point['latitude']},0\n";
                        }
                    }
                    
                    // Placemark
                    $placemark = $dom->createElement('Placemark');
                    $placemarkName = $dom->createElement('name', $freteDetalhe);
                    $placemarkStyleUrl = $dom->createElement('styleUrl', '#PONTO-FRETE');
                    $placemarkLineString = $dom->createElement('LineString');
                    $placemarkLineStringAltitudeMode = $dom->createElement('altitudeMode', 'relative');
                    $placemarkLineStringCoordinates = $dom->createElement('coordinates', (string) $coordinates);
                    $document->appendChild($placemark);
                    $placemark->appendChild($placemarkName);
                    $placemark->appendChild($placemarkStyleUrl);
                    $placemark->appendChild($placemarkLineString);
                    $placemarkLineString->appendChild($placemarkLineStringAltitudeMode);
                    $placemarkLineString->appendChild($placemarkLineStringCoordinates);
                }
                
                // Placemark retorno (pedágios)
                $pedagios = Helper::checkJson($frete['PEDAGIO_SOLUCAO']);
                if (!empty($pedagios['legs'][0])) {
                    foreach ($pedagios['legs'] as $index => $items) {
                        foreach ($items['tolls'] as $pedagio) {
                            // Estilo icone
                            $style = $dom->createElement('Style');
                            $style->setAttribute('id', "PONTO-{$pedagio['id']}");
                            $document->appendChild($style);
                            $styleIconStyle = $dom->createElement('IconStyle');
                            $styleIconStyleIcon = $dom->createElement('Icon');
                            $styleIconStyleIconHref = $dom->createElement('href', asset('/assets/freteway/images/marker/pedagio.png', true));
                            $styleLineStyle = $dom->createElement('LineStyle');
                            $styleLineStyleWidth = $dom->createElement('width', '2');
                            $style->appendChild($styleIconStyle);
                            $styleIconStyle->appendChild($styleIconStyleIcon);
                            $styleIconStyleIcon->appendChild($styleIconStyleIconHref);
                            $style->appendChild($styleLineStyle);
                            $styleLineStyle->appendChild($styleLineStyleWidth);
                            
                            // Tratamentos
                            $pedagio['coordinates']['longitude'] = str_replace(',', '.', $pedagio['coordinates']['longitude']);
                            $pedagio['coordinates']['latitude'] = str_replace(',', '.', $pedagio['coordinates']['latitude']);
                            $pedagio['price'] = 'R$ '.number_format($pedagio['price'], 2, ',', '.');
                            
                            // Placemark
                            $placemark = $dom->createElement('Placemark');
                            $placemarkName = $dom->createElement('name', "{$pedagio['concession']} | {$pedagio['name']}");
                            $placemarkStyleUrl = $dom->createElement('styleUrl', "#PONTO-{$pedagio['id']}");
                            $placemarkDescription = $dom->createElement('description', "{$pedagio['address']} - {$pedagio['price']}");
                            $placemarkPoint = $dom->createElement('Point');
                            $placemarkPointCoordinates = $dom->createElement('coordinates', "{$pedagio['coordinates']['latitude']},{$pedagio['coordinates']['longitude']}");
                            $document->appendChild($placemark);
                            $placemark->appendChild($placemarkName);
                            $placemark->appendChild($placemarkStyleUrl);
                            $placemark->appendChild($placemarkDescription);
                            $placemark->appendChild($placemarkPoint);
                            $placemarkPoint->appendChild($placemarkPointCoordinates);
                        }
                    }
                }
                
                // Verifica a latitude e longitude e monta o estilo e placemark
                foreach (['ORIGEM', 'DESTINO'] as $item) {
                    if (!empty($frete["{$item}_LAT_FRETE"]) && !empty($frete["{$item}_LON_FRETE"])) {
                        // Estilo icone
                        $style = $dom->createElement('Style');
                        $style->setAttribute('id', "PONTO-{$item}");
                        $document->appendChild($style);
                        $styleIconStyle = $dom->createElement('IconStyle');
                        $styleIconStyleIcon = $dom->createElement('Icon');
                        $styleIconStyleIconHref = $dom->createElement('href', asset('/assets/freteway/images/marker/'.strtolower($item).'.png', true));
                        $styleLineStyle = $dom->createElement('LineStyle');
                        $styleLineStyleWidth = $dom->createElement('width', '2');
                        $style->appendChild($styleIconStyle);
                        $styleIconStyle->appendChild($styleIconStyleIcon);
                        $styleIconStyleIcon->appendChild($styleIconStyleIconHref);
                        $style->appendChild($styleLineStyle);
                        $styleLineStyle->appendChild($styleLineStyleWidth);
                        
                        // Placemark
                        $placemark = $dom->createElement('Placemark');
                        
                        if ($item === 'ORIGEM') {
                            $placemarkName = $dom->createElement('name', "Origem - {$origem['NOME_CIDADE']}/{$origemEstado['SIGLA_ESTADO']}");
                        } else {
                            $placemarkName = $dom->createElement('name', "Destino - {$destino['NOME_CIDADE']}/{$destinoEstado['SIGLA_ESTADO']}");
                        }
                        
                        $placemarkStyleUrl = $dom->createElement('styleUrl', "#PONTO-{$item}");
                        $placemarkDescription = $dom->createElement('description', '');
                        $placemarkPoint = $dom->createElement('Point');
                        $placemarkPointCoordinates = $dom->createElement('coordinates', "{$frete["{$item}_LON_FRETE"]},{$frete["{$item}_LAT_FRETE"]}");
                        $document->appendChild($placemark);
                        $placemark->appendChild($placemarkName);
                        $placemark->appendChild($placemarkStyleUrl);
                        $placemark->appendChild($placemarkDescription);
                        $placemark->appendChild($placemarkPoint);
                        $placemarkPoint->appendChild($placemarkPointCoordinates);
                    }
                }
            } else {
                $message = $dom->createElement('message', 'Não existe resultado.');
                $kml->appendChild($message);
            }
            
            // Salva o dom em xml
            $output = $dom->saveXML();
            
            return $output;
        }
        
        /**
         * @param int $duration
         *
         * @return string
         */
        private function calcDuration($duration)
        {
            $calculate = [];
            
            // Horas e minutos
            if ($duration > 60) {
                // Horas
                $hours = floor($duration / (60 * 60));
                $duration -= $hours * (60 * 60);
                
                if ($hours > 0) {
                    $calculate[] = "{$hours} horas";
                }
                
                // Minutos
                $minutes = floor($duration / 60);
                $duration -= $minutes * 60;
                
                if ($minutes > 0) {
                    $calculate[] = "{$minutes} minutos";
                }
            } else {
                // Segundos
                $calculate[] = "{$duration} segundos";
            }
            
            return implode(' e ', $calculate);
        }
    }
}
