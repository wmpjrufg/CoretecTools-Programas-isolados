<?php
// ######################################################################
// UNIVERSIDADE FEDERAL DE CATALÃO (UFCat)
// DEPARTAMENTO DE ENGENHARIA CIVIL & DEPARTAMENTO DE CIÊNCIAS DA COMPUTAÇÃO
// Autores
// Carlos Magno Silva Carneiro
// Walfrido Carvalho do Valle
// Colaboradores
// Wanderlei Malaquias Pereira Junior
// ######################################################################

// Nome:    Informa os erros identificados no programa de dimensionamento de seções retangulares submetidas à flexão pura
// Versão:  FLEXAOPURACONCRETOPROTENDIDO01_v00_erros
// Notas:
// 01-01-20 - Prof. W. M. Pereira Junior and L. E. C. Mota Leite introduziram as verificações de erros e avisos
//
//
//
//
//
// ----------

// ---------- Descrição do programa
//
// O programa abaixo verifica erros no programa FLEXAOPURACONCRETOPROTENDIDO01_v00_principal
// Para teste utilize: https://repl.it/~
//
// ----------

// ---------- Variáveis e suas descrições
//
// Escalares
//
// erro  - Código do erro no programa FLEXAOPURACONCRETOPROTENDIDO01_v00_principal
//
// Vetores:
//
//
//
//
// Matrizes:
//
//
//
//
// ----------


function FLEXAOPURACONCRETOPROTENDIDO01_v00_erros($erro)
{

//
//
    //=========================================================================%
    // STEP 1: RETURN EM FUNÇÃO DOS ERROS DA ROTINA PRINCIPAL
    //=========================================================================%
//
//
    // Step 1.1: Verificação de erros

    if ($erro == 1) {
        echo "O fck deve ser inferior a 90 MPa\n";
    } elseif ($erro == 2) {
        echo "O cobrimento (cob) recomendado deve ser inferior a 5.5 cm conforme Tabela 7.2 NBR 6118 \n";
    } elseif ($erro == 3) {
        echo "O diâmetro do estribo deve ser inferior a 16 mm\n";
    } elseif ($erro == 4) {
        echo "Aço com fyk > 600 não são normatizados\n";
    } elseif ($erro == 5) {
        echo "Não é permitida entrada de dados do tipo <=0 \n";
    } elseif ($erro == 6) {
        echo "Verifique o valor do fck introduzido pos fck indicado maior que fyk\n";
    } elseif ($erro == 7) {
        echo "Valor de deverá respeitar a relação d < h\n";
    } elseif ($erro == 8) {
        echo "Valor do DMC (Diâmetro Máximo Característico) do agregado está maior que 10 cm\n";
    } elseif ($erro == 9) {
        echo "A seção não suporta o momento informado nem com o uso de armadura dupla\n";
    }

    return;
}

?>