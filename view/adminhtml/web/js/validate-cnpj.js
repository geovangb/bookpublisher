define([
    'jquery',
    'Magento_Ui/js/form/element/abstract',
    'mage/validation'
], function ($, Abstract) {
    'use strict';

    $.validator.addMethod('validate-cnpj', function (value) {
        if (!value) {
            return false;
        }

        // Remove caracteres não numéricos
        var cnpj = value.replace(/[^\d]+/g, '');
        if (cnpj.length !== 14) {
            return false;
        }

        // Elimina CNPJs inválidos conhecidos
        if (/^(\d)\1{13}$/.test(cnpj)) {
            return false;
        }

        // Validação dos dígitos verificadores
        var tamanho = cnpj.length - 2,
            numeros = cnpj.substring(0, tamanho),
            digitos = cnpj.substring(tamanho),
            soma = 0,
            pos = tamanho - 7,
            i, resultado;

        for (i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) pos = 9;
        }

        resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
        if (resultado != digitos.charAt(0)) {
            return false;
        }

        tamanho = tamanho + 1;
        numeros = cnpj.substring(0, tamanho);
        soma = 0;
        pos = tamanho - 7;

        for (i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) pos = 9;
        }

        resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
        return resultado == digitos.charAt(1);
    }, $.mage.__('CNPJ inválido. Informe um CNPJ válido.'));

    return Abstract;
});
