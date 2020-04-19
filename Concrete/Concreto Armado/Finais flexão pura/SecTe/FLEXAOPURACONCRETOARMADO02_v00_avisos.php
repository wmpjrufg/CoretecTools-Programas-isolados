<?php
// ######################################################################
// UNIVERSIDADE FEDERAL DE CATALÃO (UFCat)
// DEPARTAMENTO DE ENGENHARIA CIVIL & DEPARTAMENTO DE CIÊNCIAS DA COMPUTAÇÃO
// Autores
// Aleff Lucas Gonçalves Martins
// Roberto Viegas Dutra
// Colaboradores
// Luiz Eduardo Costa Mota Leite
// Newton Peixoto
// Pedro Henrique dos Santos Marques Vieira
// Luanna Lopes Lobato
// Wanderlei Malaquias Pereira Junior
// ######################################################################

// Nome:    Informa os avisos identificados no programa de dimensionamento de seções retangulares submetidas à flexão pura
// Versão:  FLEXAOPURACONCRETOARMADO01_v00_avisos
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
// O programa abaixo verifica avisos no programa FLEXAOPURACONCRETOARMADO01_v00_principal
//
// ----------

// ---------- Variáveis e suas descrições
//
// Escalares
//
// aviso  - Código do aviso no programa FLEXAOPURACONCRETOARMADO01_v00_principal
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


function FLEXAOPURACONCRETOARMADO02_v00_avisos($aviso)
{

//
//
    //=========================================================================%
    // STEP 1: RETURN EM FUNÇÃO DOS AVISOS DA ROTINA PRINCIPAL
    //=========================================================================%
//
//
    // Step 1.1: Verificação de avisos

    if ($aviso == 1) {
        echo "A peça será calculada com situação de armadura dupla\n\n";
    } elseif ($aviso == 2) {
        echo "A peça necessita de uma área de aço > As,max \n\n";
    } elseif ($aviso == 3) {
        echo "NÃO OK ! A bitola escolhida gera dreal < d \n\n";
    } elseif ($aviso == 4) {
        echo "NÃO OK ! A bitola escolhida gera a > 10%.h. A armadura não pode ser considerada concentrada\n";
    }

    return;
}
