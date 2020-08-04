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

// Name:    Determinação da perda de tensão por retração do concreto
// Version: PROTENSAO02_v00_perda_de_protensao_por_retração_do_concreto
// Notes:
// 
//
//
//
//
//
// ---------- Descrição do programa:
//
// O programa abaixo determina a perda de tensão devido a retração do concreto, que é uma das perdas lentas.
//
// ----------

// ---------- Descrição das variáveis
//
// Escalares:
//
// Pi            - Carga inicial da protensão                                                            (kN)
// deltaP        - Variação de carga devido a perda por retração no tempo (t,t0)                         (kN)
// deltaPinf     - Variação de carga devido a perda por retração no infinito                             (kN)
// P             - Carga final da protensão no tempo (t,t0)                                              (kN)
// Pinf          - Carga final da protensão no infinito                                                  (kN)
// $Pporcent     - Porcentagem de perda de protenção no tempo (t,t0)                                     (%)
// $Pinfporcent  - Porcentagem de perda de protenção no infinito                                         (%)
// Ep            - Módulo de elasticidade do aço de protensão                                            (kN/cm²)
// Ac            - Área de concreto da seção transversal                                                 (cm²)
// miar          - Parte do perimetro externo da seção transversal da peça em contato com o ar           (cm)
// abat          - Abatimento do concreto                                                                (cm)
// U             - Umidade relativa do ambiente                                                          (%)
// gama          - Coeficiente dependente da umidade relativa do ambiente                                (%)
// epsilon1s     - Coeficiente dependente da umidade relativa do ambiente e da consistência do concreto  (%)
// epsilon2s     - Coeficiente dependente da espessura fictícia da peça                                  (%)
// hfic          - Espessura fictícia, em centímetros                                                    (cm)
// h             - Espessura fictícia, em metros                                                         (m)
// epsiloncsinf  - Valor final da retração no tempo infinito                                             (%)
// epsiloncst    - Valor final da retração no tempo t                                                    (%)
// t             - Idade fictícia do concreto no instante considerado                                    (dias)
// t0            - Idade fictícia do concreto no instante em que o efeito da retração a peça começa a ser considerado (dias)
// deltatef      - Período durante o qual a temperatura Ti pode ser admitida constante                   (dias)
// Ti            - Temperatura média diária do ambiente                                                  (°C)
// betast        - Ceficiente relativo a retração no instante t. Deve ser maior ou igual a 3 dias        (adimensional)
// betast0       - Ceficiente relativo a retração no instante t0.                                        (adimensional)
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
//
//=========================================================================%
// STEP 1: DECLARAÇÃO DOS PARÂMETROS DE ENTRADA
//=========================================================================%
//
//
// print setup
echo "-----------CORETEC TOOLS - CONCRETE-----------\n";
echo "PROJETO DE PEÇAS PROTENDIDAS - CÁLCULO DA PERDA DE TENSAO POR RETRAÇÃO DO CONCRETO\n";
echo "-----------------------------------------------\n\n";

// Step 1.1: Definição dos parâmetros de entrada

$Pi       = 50;
$Ac       = 2000;
$miar     = 1000;
$U        = 40;
$abat     = 5;
$Ti       = 20;
$deltatef = 30;
$t0       = 5;
$Ep       = 20000;

// print setup
echo "-----------------------------------------------\n";
echo "PARÂMETROS DE ENTRADA:\n";
echo "-----------------------------------------------\n";
echo "Área da seção transvesal do cooncreto                               = $Ac cm²\n";
echo "Perímetro externo da seção transversal da peça em contato com o ar  = $miar cm\n";
echo "Umidade relativa do ambiente                                        = $U %\n";
echo "Abatimento                                                          = $abat cm\n";
echo "Módulo de elasticidade do aço                                       = $Ep kN/cm² \n";
echo "Idade fictícia do concreto no instante em que o efeito da retração a peça começa a ser considerado = $t0 dias \n";
echo "-----------------------------------------------\n\n";

// Step 1.2: Determinação do coeficiente gama

$expoenteparte1 = -7.8 + 0.1*$U;
$expoenteparte2 = exp($expoenteparte1);

$gama = 1 + $expoenteparte2;

// Step 1.3: Determinação da altura fictícia da peça

$hfic = ($gama * 2 * $Ac) / $miar;

// Step 1.4: Determinação do epsilon1s

$epsilon1sparte1 = 8.09;
$epsilon1sparte2 = $U/15;
$epsilon1sparte3 = pow($U,2) / 2.284;
$epsilon1sparte4 = pow($U,3) / 133.765;
$epsilon1sparte5 = pow($U,4) / 7608.150;

$epsilon1sfinal = (-$epsilon1sparte1+$epsilon1sparte2-$epsilon1sparte3-$epsilon1sparte4+$epsilon1sparte5) * 0.0001;

if ($abat >= 0 && $abat <= 4) {
$epsilon1s = 0.75 * $epsilon1sfinal;
}elseif ($abat >= 5 && $abat <= 9) {
$epsilon1s = $epsilon1sfinal;
}elseif ($abat >= 10 && $abat <= 15) {
$epsilon1s = 1.25 * $epsilon1sfinal;     
}

// Step 1.5: Determinação do epsilon2s

$epsilon2sparte1 = 33 + (2 * $hfic);
$epsilon2sparte2 = 20.8 + (3 * $hfic);

$epsilon2s = $epsilon2sparte1 / $epsilon2sparte2;

// Step 2: Determinação da retração no tempo infinito
// Step 2.1: Valor final da retração no tempo infinito

$epsiloncsinf = $epsilon1s * $epsilon2s;

// Step 1.12: Variação de tensão após a perda de protensão no tempo infinito

$deltasigmapcsinf = $epsiloncsinf * $Ep;

// Step 1.13: Variação de carga após a perda de protensão no tempo infinito

$deltaPinf = $deltasigmapcsinf / $Ac;

// Step 1.14:  Carga após a perda de protensão no tempo infinito

$Pinf = $Pi - $deltaPinf;

// Step : Porcentagem de perda no tempo (t,t0)

$Pinfporcent = $deltaPinf / $Pi;

//Step 3: Determinação da retração no tempo (t,t0)
// Step 1.7: Determinação do tempo fictício t

$t = (($Ti + 10) * $deltatef) / 30;

// Step 1.8: Espessura fictícia em metros

$h = $hfic / 10;

if ($h < 0.05) {
	$h = 0.05;
} elseif ($h > 1.6) {
	$h = 1.6;
} else
	$h = $h;

// Step 1.9: Determinação do coeficiente de retração no instante t0

$A = 40;
$B = 116 * pow($h, 3) - 282 * pow($h, 2) + 220 * $h - 4.8;
$C = 2.5 * pow($h, 3) - 8.8 * $h + 40.7;
$D = -75 * pow($h, 3) + 585 * pow($h, 2) + 496 * $h - 6.8;
$E = -169 * pow($h, 4) + 88 * pow($h, 3) + 584 * pow($h, 2) + 0.8;

$betast0parte1 = pow((0.01 * $t0), 3);
$betast0parte2 = $A * pow((0.01 * $t0), 2);
$betast0parte3 = $B * 0.01 * $t0;
$betast0parte4 = $C * pow((0.01 * $t0), 2);
$betast0parte5 = $D * 0.01 * $t0;
$betast0parte6 = $E;

$betast0 = ($betast0parte1+$betast0parte2+$betast0parte3)/($betast0parte1+$betast0parte4+$betast0parte5+$betast0parte6);

// Step 1.10: Determinação do coeficiente de retração no instante t

$A = 40;
$B = 116 * pow($h, 3) - 282 * pow($h, 2) + 220 * $h - 4.8;
$C = 2.5 * pow($h, 3) - 8.8 * $h + 40.7;
$D = -75 * pow($h, 3) + 585 * pow($h, 2) + 496 * $h - 6.8;
$E = -169 * pow($h, 4) + 88 * pow($h, 3) + 584 * pow($h, 2) + 0.8;

$betastparte1 = pow(0.01 * $t, 3);
$betastparte2 = $A * pow(0.01 * $t, 2);
$betastparte3 = $B * 0.01 * $t;
$betastparte4 = $C * pow(0.01 * $t, 2);
$betastparte5 = $D * 0.01 * $t;
$betastparte6 = $E;

$betast = ($betastparte1+$betastparte2+$betastparte3)/($betastparte1+$betastparte4+$betastparte5+$betastparte6);

// Step 1.11: Determinação do valor final da retração no tempo (t,t0)

$epsiloncst = $epsiloncsinf * ($betast - $betast0);

// Step 1.12: Variação de tensão após a perda de protensão no tempo (t,t0)

$deltasigmapcs = $epsiloncst * $Ep;

// Step 1.13: Variação de carga após a perda de protensão no tempo (t,t0)

$deltaP = $deltasigmapcs / $Ac;

// Step 1.14:  Carga após a perda de protensão no tempo (t,t0)

$P = $Pi - $deltaP;

// Step : Porcentagem de perda no tempo (t,t0)

$Pporcent = $deltaP / $Pi;

// print setup
echo "-----------------------------------------------\n";
echo "PROCESSAMENTO DA PERDA DE CARGA Pi:\n";
echo "-----------------------------------------------\n";
echo "Perda de protensão por retração do concreto no infinito     = $deltaPinf kN\n";
echo "Carga após a perda de protensão no infinito                 = $Pinf kN\n";
echo "Porcentagem de perda de protenção no infinito               = $Pinfporcent %\n";
echo "Perda de protensão por retração do concreto no tempo (t,t0) = $deltaP kN\n";
echo "Carga após a perda de protensão no tempo (t,t0)             = $P kN\n";
echo "Porcentagem de perda de protenção no tempo (t,t0)           = $Pporcent %\n";
echo "-----------------------------------------------\n\n";

?>