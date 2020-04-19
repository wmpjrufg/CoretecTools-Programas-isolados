<?php
// ######################################################################
// UNIVERSIDADE FEDERAL DE CATALÃO (UFCat)
// DEPARTAEMNTO DE ENGENHARIA CIVIL
// Autores
// Carlos Magno Silva Carneiro
// Walfrido Carvalho do Valle
// Colaboradores
// Wanderlei Malaquias Pereira Junior
// ######################################################################

// Nome:    Determinação da perda de tensão por atrito na protensão
// Versão: PROTENSAO02_v00_perda_de_protensao_por_atrito_nos_cabos
// Notas:
// 15-04-20 - C. M. S. Carneiro and W. C. do Valle finalizaram a versão beta
//
//
//
//
//
// ---------- Descrição do programa:
//
// O programa abaixo determina a perda de tensão devido ao atrito nos cabos
//
// ----------

// ---------- Descrição das variáveis
//
// Escalares:
//
// Pi          - Força máxima admitida do aço                                                       (kN)
// X           - Comprimento do cabo medido a partir da ancoragem                                   (m)
// alfa        - Ângulo de desvio entre a ancoragem e o ponto da abscissa x.                        (radianos)
// fator micro - Ângulo de atrito aparente entre o cabo e a bainha.                                 (1/radianos)
// fator k     - Coeficiente de perda por metro provocadas por curvaturas não intencionais do cabo. (1/m)
//
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
//
// ---------- Chamada de funções
//
//
//=========================================================================%
// STEP 1: DECLARAÇÃO DOS PARÂMETROS DE ENTRADA
//=========================================================================%
//
//
// print setup
echo "-----------CORETEC TOOLS - CONCRETE-----------\n";
echo "PROJETO DE PEÇAS PROTENDIDAS - CÁLCULO DA PERDA DE TENSAO POR ATRITO\n";
echo "-----------------------------------------------\n\n";

// Step 1.1: Definição dos parâmetros de entrada que vêm do Front End
$Pi           = 1000;
$X            = 10;
$fatoralfa    = 0.17;
$fatormicro   = 0.50;
$fatork       = 0.0033;

// print setup
echo "-----------------------------------------------\n";
echo "PARÂMETROS DE ENTRADA:\n";
echo "-----------------------------------------------\n";
echo "Carga de protensão  = $Pi kN\n";
echo "Distância           = $X m\n";
echo "Alpha               = $fatoralfa radianos\n";
echo "Micro               = $fatormicro 1/radianos\n";
echo "k                   = $fatork 1/m\n";
echo "-----------------------------------------------\n\n";


//if ($fatoralfa == "entre cabo e concreto") {
//    echo "0.50";
//    $fatoralfa=0.50;
//} elseif ($fatoralfa == "entre barras ou fios ou saliência e bainha metálica") {
//    echo "0.30";
//    $fatoralfa=0.30;
//} elseif ($fatoralfa == "entre fios lisos e bainha metálica lubrificada") {
//    echo "0.20";
//    $fatoralfa=0.20;
//} else {
//    echo "0.05";
//    $fatoralfa=0.05;
//}

// Step 1.3: Determinação da força no cabo considerando a perda por atrito
$expoenteparte1 = $fatormicro*$fatoralfa;
$expoenteparte2 = $fatork*$X;
$expoentetotal  = -($fatormicro*$fatoralfa+$expoenteparte2);
$Perdacarga     = $Pi-($Pi*exp($expoentetotal));
$Perdaperc      = ($Perdacarga)/$Pi*100;
$Piaposperda    = $Pi*exp($expoentetotal);

// print setup
echo "-----------------------------------------------\n";
echo "PROCESSAMENTO DA PERDA DE CARGA Pi:\n";
echo "-----------------------------------------------\n";
echo "micro x alpha      = $expoenteparte1\n";
echo "k x l              = $expoenteparte2\n";
echo "expoente total     = $expoentetotal \n";
echo "Total da perda     = $Perdacarga kN\n";
echo "Total percentual   = $Perdaperc %\n";
echo "Carga após a perda = $Piaposperda kN\n";
echo "-----------------------------------------------\n\n";
