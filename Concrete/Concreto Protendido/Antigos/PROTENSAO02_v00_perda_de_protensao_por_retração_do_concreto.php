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
// Pi            - Carga inicial da protensão                                                            (ton)
// deltaP        - Variação de carga devido a perda por retração                                         (ton)
// P             - Carga final da protensão                                                              (ton)
// Ac            - Área de concreto da seção transversal                                                 (cm²)
// miar          - Parte do perimetro externo da seção transversal da peça em contato com o ar           (cm)
// U             - Umidade relativa do ambiente                                                          (%)
// gama          - Coeficiente dependente da umidade relativa do ambiente                                (%)
// episilon1s    - Coeficiente dependente da umidade relativa do ambiente e da consistência do concreto  (%)
// episilon2s    - Coeficiente dependente da espessura fictícia da peça                                  (%)
// hfic          - Espessura fictícia, em centímetros                                                    (cm)
// h             - Espessura fictícia, em metros                                                         (m)
// episiloncs - Valor final da retração no tempo infinito                                             (%)
// episiloncst   - Valor final da retração no tempo t                                                    (%)
// t             - Tempo fictício                                                                        (dias)
// deltatef      - Período durante o qual a temperatura Ti pode ser asmitida constante                   (dias)
// Ti            - Temperatura média diária do ambiente                                                  (°C)
// betast        - Ceficiente relativo a retração no instante t. Deve ser maior ou igual a 3 dias        (adimensional)
// betast0       - Ceficiente relativo a retração no instante t0. Deve ser maior ou igual a 3 dias       (adimensional)
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

$Ac       = 500;
$miar     = 40;
$U        = 70;
$abat     = 4;
$Ti       = 20;
$deltatef = 5;

// print setup
echo "-----------------------------------------------\n";
echo "PARÂMETROS DE ENTRADA:\n";
echo "-----------------------------------------------\n";
echo "Área da seção transvesal do cooncreto                               = $Ac mm²\n";
echo "Perímetro externo da seção transversal da peça em contato com o ar  = $miar mm\n";
echo "Umidade relativa do ambiente                                        = $U %\n";
echo "Abatimento                                                          = $abat cm\n";
echo "-----------------------------------------------\n\n";

// Step 1.2: Determinação do coeficiente gama

$expoenteparte1 = -7.8 + 0.1*$U;
$expoenteparte2 = exp($expoenteparte1);

$gama = 1 + $expoenteparte2;

// Step 1.3: Determinação da altura fictícia da peça

$hfic = ($gama * 2 * $Ac) / $miar;

// Step 1.4: Determinação do episilon1s

$episilon1sparte1 = 0.89;
$episilon1sparte2 = $U/15;
$episilon1sparte3 = pow($U,2) / 2.284;
$episilon1sparte4 = pow($U,3) / 133.765;
$episilon1sparte5 = pow($U,4) / 7608.150;

$episilon1sfinal = -$episilon1sparte1+$episilon1sparte2-$episilon1sparte3-$episilon1sparte4+$episilon1sparte5;

if ($abat > 0 && $abat < 4) {
$episilon1s = 0.75 * $episilon1sfinal;
}elseif ($abat > 5 && $abat < 9) {
$episilon1s = $episilon1sfinal;
}else ($abat > 10 && $abat < 15) {
$episilon1s = 1.25 * $episilon1sfinal        
}

// Step 1.5: Determinação do episilon2s

$episilon2sparte1 = 33 + (2 * $hfic);
$episilon2sparte2 = 20.8 + (3 * $hfic);

$episilon2s = $episilon2sparte1 / $episilon2sparte2;

// Step 1.6: Valor final da retração no tempo infinito

$episiloncs = $episilon1s * $episilon2s;

// Step 1.7: Determinação do tempo fictício t

$t = ($Ti + 10) * $deltatef / 30;

// Step 1.8: Determinação do coeficiente de retração no instante t

$h = $hfic / 10

$A = 40
$B = 116 * pow($h, 3) - 282 * pow($h, 2) + 220 * $h - 4.8
$C = 2.5 * pow($h, 3) - 8.8 * $h + 40.7
$D = -75 * pow($h, 3) + 585 * pow($h, 2) + 496 * $h - 6.8
$E = -169 * pow($h, 4) + 88 * pow($h, 3) + 584 * pow($h, 2) + 0.8

$betastparte1 = pow(0,01 * $t, 3) 
$betastparte2 = $A * pow(0,01 * $t, 2) 
$betastparte3 = $B * 0,01 * $t
$betastparte4 = $C * pow(0,01 * $t, 2)
$betastparte5 = $D * 0,01 * $t
$betastparte6 = $E

$betast = ($betastparte1+$betastparte2+$betastparte3)/($betastparte1+$betastparte4+$betastparte5+$betastparte6);

// Step 1.9: Determinação do valor final da retração no tempo t

$episiloncst = $episiloncs * $betast;

// Step 1.10: Variação de carga após a perda de protensão no tempo infinito

$deltaP = $Pi * ($episiloncst / 100);

// Step 1.10:  Carga após a perda de protensão no tempo infinito

$P = $Pi - $deltaP;

// print setup
echo "-----------------------------------------------\n";
echo "PROCESSAMENTO DA PERDA DE CARGA Pi:\n";
echo "-----------------------------------------------\n";
echo "Perda de protensão por retração do concreto   = $deltaP ton\n";
echo "Carga após a perda de protensão no tempo t    = $P ton\n";
echo "-----------------------------------------------\n\n";

?>