<?php

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Arr;
use Carbon\Carbon;

if (! function_exists('is_money')) {
    /**
     * Validate money in US and other patterns without the prefix or sufix.
     * Only validates numbers with commas and dots.
     * Ex: 100,00  // is valid
     * Ex: 100.00  // is valid
     * Ex: 100a00  // is invalid
     * Ex: 1,000.0 // is valid
     * Ex: 1.000,0 // is valid
     * @param string $number
     *
     * @return bool
     */
    function is_money($number)
    {
        return preg_match("/^[0-9]{1,3}(,?[0-9]{3})*(\.[0-9]{1,2})?$/", $number) ||
            preg_match("/^[0-9]{1,3}(\.?[0-9]{3})*(,[0-9]{1,2})?$/", $number);
    }

}

if(! function_exists('money_to_float')) {
    /**
     * Transforms a valid money string to float
     *
     * @param string $number
     *
     * @return float
     */
    function money_to_float ($number) {
        if (preg_match("/^(-)?[0-9]{1,3}(,?[0-9]{3})*(\.[0-9]{1,2})?$/", $number)) {
            return (float) str_replace(',', '', $number);
        } elseif(preg_match("/^(-)?[0-9]{1,3}(\.?[0-9]{3})*(,[0-9]{1,2})?$/", $number)) {
            return (float) str_replace(',', '.', str_replace('.', '', $number));
        } elseif(is_null($number)) {
            return (float) 0;
        } else {
            throw new InvalidArgumentException(
                'The parameter is not a valid money string. Ex.: 100.00, 100,00, 1.000,00, 1,000.00'
            );
        }
    }
}

if(! function_exists('float_to_money')) {
    /**
     * Transforms a float to a currency formatted string
     *
     * @param float $number
     *
     * @return string
     */
    function float_to_money($number, $prefix = 'R$ ')
    {
        return $prefix . number_format($number, 2, ',', '.');
    }
}

Collection::macro('dd', function () {
    dd($this);
});

EloquentCollection::macro('dd', function () {
    dd($this);
});

if(! function_exists('get_percentual_column')) {
    /**
     * Recebe o código do insumo e devolve o nome da coluna que contem a porcentagem
     * que gerou este insumo
     *
     * @return string
     */
    function get_percentual_column($codigo_insumo)
    {
        $insumos = [
            '34007' => 'porcentagem_material',
            '30019' => 'porcentagem_faturamento_direto',
            '37367' => 'porcentagem_locacao',
        ];

        return Arr::get($insumos, $codigo_insumo);
    }
}

if(! function_exists('to_fixed')) {
    /**
     * Equivalent to the toFixed method of Javascript Numbers
     * @param float $number
     * @param int $decimals = 2
     *
     * @return string
     */
    function to_fixed($number, $decimals = 2, $decimal_separator = '.')
    {
        return number_format((float) $number, $decimals, $decimal_separator, '');
    }
}

if(! function_exists('to_percentage')) {
    /**
     * Percentage format
     *
     * @return string
     */
    function to_percentage($number)
    {
        return to_fixed($number, 2, ',') . '%';
    }
}

if(! function_exists('mask')) {
    //echo mask($cnpj, '##.###.###/####-##');
    //echo mask($cpf,  '###.###.###-##');
    //echo mask($cep,  '#####-###');
    //echo mask($data, '##/##/####');
    //echo mask($data, '##/##/####');
    //echo mask($data, '[##][##][####]');
    //echo mask($data, '(##)(##)(####)');
    //echo mask($hora, 'Agora são ## horas ## minutos e ## segundos');
    //echo mask($hora, '##:##:##');
    function mask($val, $mask)
    {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k]))
                    $maskared .= $val[$k++];
            } else {
                if (isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }
}


if (! function_exists('datatables_format_date')) {
    function datatables_format_date($column, $text = 'Sem data')
    {
        return function ($model) use ($column, $text) {
            if (empty($model->{$column})) {
                return $text;
            }

            if ($model->{$column} instanceof Carbon) {
                return $model->{$column}->format('d/m/Y');
            }

            return with(new Carbon($model->{$column}))->format('d/m/Y');
        };
    }
}

if (! function_exists('datatables_float_to_money')) {
    function datatables_float_to_money($column, $text = '0,00')
    {
        return function ($model) use ($column, $text) {
            if (empty($model->{$column})) {
                return $text;
            }

            return float_to_money($model->{$column});
        };
    }
}

if(! function_exists('datatables_empty_column')) {
    function datatables_empty_column($column, $msg = 'Sem dados')
    {
        return function($model) use ($msg, $column) {
            return $model->{$column} ?: $msg;
        };
    }
}
