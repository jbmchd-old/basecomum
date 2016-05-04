<?php

/**
 * Created by PhpStorm.
 * User: jb
 * Date: 05/02/14
 * Time: 12:03
 */

namespace Nucleo\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Nucleo\Service\ServiceManager;

class DatePlugin extends AbstractPlugin {

    public function getDateInfo($data, $formato = 'pt-br') {
        
        if(empty($data)){
            return ['is_valid'=>false];
        }
        
        $formats = [
            'pt-br' => 'DDMMAAAA', 
            'en-us' => 'MMDDAAAA',
            'iso'   => 'AAAAMMDD',
        ];
        
        $delimiter_origem = preg_replace('/[0-9]+/', '', $data)[0];
        $data_only_numbers = str_replace($delimiter_origem, '', $data);

        switch ($formats[$formato]) {
            case 'DDMMAAAA':
                $d = substr($data_only_numbers, 0, 2);
                $m = substr($data_only_numbers, 2, 2);
                $a = substr($data_only_numbers, 4, 4);
                break;
            case 'MMDDAAAA':
                $m = substr($data_only_numbers, 0, 2);
                $d = substr($data_only_numbers, 2, 2);
                $a = substr($data_only_numbers, 4, 4);
                break;
            case 'AAAAMMDD':
                $a = substr($data_only_numbers, 0, 4);
                $m = substr($data_only_numbers, 4, 2);
                $d = substr($data_only_numbers, 6, 2);
                break;
            default:
                throw new \Exception("Formato de data inválido");
                break;
        }

        return [
            'is_valid' => (int) checkdate($m, $d, $a),
            'date' => $data,
            'format_type' => $formato,
            'delimiter' => $delimiter_origem,
        ];
    }
    
    public function isValidDate($data, $formato = 'pt-br'){
        return $this->getInfoData($data,$formato)['is_valid'];
    }
    
    public function parseDate($s_date, $s_to = 'iso', $s_from = 'pt-br', $s_return_delimiter = false) {
        
        $date = $this->getInfoData($s_date, $s_from);
        
        if (!$date['is_valid']) {
            return $date;
        }

        $delimiter_origem = $date['delimiter'];

        if (!preg_match('/[-.\/]/', $delimiter_origem)) {
            throw new \Exception("Delimitador de data inválido.");
        }

        $formats_delimiter = ['pt-br' => '/', 'en-us' => '/', 'iso' => '-',];

        $delimiter_destino = ($s_return_delimiter) ? $s_return_delimiter : $formats_delimiter[$s_to];

        $formats = [
            'pt-br' => 'd' . $delimiter_destino . 'm' . $delimiter_destino . 'Y H:i:s',
            'en-us' => 'm' . $delimiter_destino . 'd' . $delimiter_destino . 'Y H:i:s',
            'iso'   => 'Y' . $delimiter_destino . 'm' . $delimiter_destino . 'd H:i:s',
        ];

        if ($date['format_type'] == 'pt-br') {
            list($d, $m, $a) = preg_split('/[-.\/ ]/', $s_date);
        } else if ($date['format_type'] == 'iso') {
            list($a, $m, $d) = preg_split('/[-.\/ ]/', $s_date);
        }

        return (new \DateTime($m . '/' . $d . '/' . $a))->format($formats[$s_to]);
    }
        
    public function getDateTimeIsoNow(){
        return (new \DateTime('now'))->format('Y-m-d H:i:s');
    }


}
