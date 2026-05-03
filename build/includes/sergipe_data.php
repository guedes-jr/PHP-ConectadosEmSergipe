<?php

function get_sergipe_regions(): array {
    return [
        'Agreste Central',
        'Baixo Cotinguiba',
        'Baixo São Francisco',
        'Centro Sul',
        'Grande Aracaju',
        'Leste Sergipano',
        'Médio Sertão',
        'Sertão Ocidental',
        'Sertão Oriental',
        'Sul Sergipano'
    ];
}

function get_city_region_mapping(): array {
    return [
        // Grande Aracaju
        'Aracaju' => 'Grande Aracaju',
        'Barra dos Coqueiros' => 'Grande Aracaju',
        'Itaporanga d\'Ajuda' => 'Grande Aracaju',
        'Laranjeiras' => 'Grande Aracaju',
        'Maruim' => 'Grande Aracaju',
        'Nossa Senhora do Socorro' => 'Grande Aracaju',
        'Riachuelo' => 'Grande Aracaju',
        'Santo Amaro das Brotas' => 'Grande Aracaju',
        'São Cristóvão' => 'Grande Aracaju',

        // Agreste Central
        'Itabaiana' => 'Agreste Central',
        'Areia Branca' => 'Agreste Central',
        'Campo do Brito' => 'Agreste Central',
        'Carira' => 'Agreste Central',
        'Frei Paulo' => 'Agreste Central',
        'Macambira' => 'Agreste Central',
        'Malhador' => 'Agreste Central',
        'Moita Bonita' => 'Agreste Central',
        'Nossa Senhora Aparecida' => 'Agreste Central',
        'Pedra Mole' => 'Agreste Central',
        'Pinhão' => 'Agreste Central',
        'Ribeirópolis' => 'Agreste Central',
        'São Domingos' => 'Agreste Central',
        'São Miguel do Aleixo' => 'Agreste Central',

        // Centro Sul
        'Lagarto' => 'Centro Sul',
        'Poço Verde' => 'Centro Sul',
        'Riachão do Dantas' => 'Centro Sul',
        'Simão Dias' => 'Centro Sul',
        'Tobias Barreto' => 'Centro Sul',

        // Baixo São Francisco
        'Propriá' => 'Baixo São Francisco',
        'Amparo de São Francisco' => 'Baixo São Francisco',
        'Brejo Grande' => 'Baixo São Francisco',
        'Canhoba' => 'Baixo São Francisco',
        'Cedro de São João' => 'Baixo São Francisco',
        'Ilha das Flores' => 'Baixo São Francisco',
        'Japoatã' => 'Baixo São Francisco',
        'Malhada dos Bois' => 'Baixo São Francisco',
        'Muribeca' => 'Baixo São Francisco',
        'Neópolis' => 'Baixo São Francisco',
        'Pacatuba' => 'Baixo São Francisco',
        'Santana do São Francisco' => 'Baixo São Francisco',
        'São Francisco' => 'Baixo São Francisco',
        'Telha' => 'Baixo São Francisco',

        // Sul Sergipano
        'Estância' => 'Sul Sergipano',
        'Arauá' => 'Sul Sergipano',
        'Boquim' => 'Sul Sergipano',
        'Cristinápolis' => 'Sul Sergipano',
        'Indiaroba' => 'Sul Sergipano',
        'Itabaianinha' => 'Sul Sergipano',
        'Pedrinhas' => 'Sul Sergipano',
        'Santa Luzia do Itanhy' => 'Sul Sergipano',
        'Tomar do Geru' => 'Sul Sergipano',
        'Umbaúba' => 'Sul Sergipano',

        // Médio Sertão
        'Nossa Senhora das Dores' => 'Médio Sertão',
        'Aquidabã' => 'Médio Sertão',
        'Cumbe' => 'Médio Sertão',
        'Feira Nova' => 'Médio Sertão',
        'Graccho Cardoso' => 'Médio Sertão',
        'Itabi' => 'Médio Sertão',

        // Sertão Ocidental (Alto Sertão)
        'Nossa Senhora da Glória' => 'Sertão Ocidental',
        'Canindé de São Francisco' => 'Sertão Ocidental',
        'Gararu' => 'Sertão Ocidental',
        'Monte Alegre de Sergipe' => 'Sertão Ocidental',
        'Nossa Senhora de Lourdes' => 'Sertão Ocidental',
        'Poço Redondo' => 'Sertão Ocidental',
        'Porto da Folha' => 'Sertão Ocidental',

        // Sertão Oriental
        'Amparo de São Francisco' => 'Sertão Oriental', // Sometimes overlaps
        'Gararu' => 'Sertão Oriental', // Overlaps
        'Nossa Senhora de Lourdes' => 'Sertão Oriental',
        'Propriá' => 'Sertão Oriental',
        'Telha' => 'Sertão Oriental',

        // Leste Sergipano
        'Japaratuba' => 'Leste Sergipano',
        'Capela' => 'Leste Sergipano',
        'Carmópolis' => 'Leste Sergipano',
        'General Maynard' => 'Leste Sergipano',
        'Pirambu' => 'Leste Sergipano',
        'Rosário do Catete' => 'Leste Sergipano',
        'Siriri' => 'Leste Sergipano',

        // Baixo Cotinguiba
        'Divina Pastora' => 'Baixo Cotinguiba',
        'Riachuelo' => 'Baixo Cotinguiba',
        'Santa Rosa de Lima' => 'Baixo Cotinguiba'
    ];
}
