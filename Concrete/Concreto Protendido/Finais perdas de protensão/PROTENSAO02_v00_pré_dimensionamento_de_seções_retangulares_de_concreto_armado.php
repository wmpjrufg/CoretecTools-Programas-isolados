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

// Name:    Pré dimensionamento de seções retangulares de concreto armado
// Version: PROTENSAO02_v00_pré_dimensionamento_de_seções_retangulares_de_concreto_armado
// Notes:
// 
//
//
//
//
//
// ---------- Descrição do programa:
//
// O programa abaixo determina a linha neutra de uma seção retangular e a área de aço mínima.
//
// ----------

// ---------- Descrição das variáveis
//
// Escalares:
//
// bw         - Largura da seção                                                            (cm)
// h          - Altura total da secão                                                       (cm)
// d          - Altura útil da secão                                                        (cm)
// fck        - Resistência a compressão característica do concreto                         (MPa)
// fcd        - Resistência a compressão de cálculo do concreto                             (MPa)
// fyd        - Resistência a compressão característica do concreto                         (kN/cm²)
// X          - Posição da linha neutra                                                     (cm)
// Mk         - Momento fletor característico                                               (kNm)
// Md         - Momento fletor de cálculo                                                   (kNm)
// As         - Área de aço                                                                 (cm²)
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
echo "PROJETO DE PEÇAS PROTENDIDAS - PRÉ DIMENSIONAMENTO DE SEÇÕES RETANGULARES DE CONCRETO ARMADO\n";
echo "-----------------------------------------------\n\n";

// Step 1.1: Definição dos parâmetros de entrada

$bw       = 70;
$h        = 160;
$d        = 145;
$fck      = 26;
$fyd      = 50;
$Mk       = 5410;

// print setup
echo "-----------------------------------------------\n";
echo "PARÂMETROS DE ENTRADA:\n";
echo "-----------------------------------------------\n";
echo "Largura da seção                                    = $bw cm\n";
echo "Altura total da secão                               = $h cm\n";
echo "Altura útil da secão                                = $d cm\n";
echo "Resistência a compressão característica do concreto = $fck MPa\n";
echo "Resistência de cálculo do aço                       = $fyd kN/cm²\n";
echo "Momento fletor característico                       = $Mk kNm\n";
echo "-----------------------------------------------\n\n";

// Step 1.2: Determinação da resistência a compressão de cálculo do concreto (fcd)

$fcd = ($fck/1.4) / 10;

// Step 1.3: Determinação do momento fletor de cálculo

$Md = ($Mk*1.4) * 100;

// Step 1.4: Determinação da linha neutra

$Xparte1 = 1 - ($Md / (0.425*$d*$d*$fcd*$bw));
$Xparte2 = sqrt($Xparte1);

$X = 1.25 * $d * (1-$Xparte2);

// Conferência do limite da posição da linha neutra

if ($fck <= 50 && $X/$d <= 0.45) {
	echo "Atende a NBR 6118";
}

if ($fck >> 50 && $X/$d <= 0.35) {
	echo "Atende a NBR 6118";
}

else {
	echo "NÃO ATENDE A NBR 6118! \n\n";
}

// Determinação da área de aço

$As = $Md / (($d - (0.4 * $X)) * ($fyd / 1.15));

// print setup
echo "-----------------------------------------------\n";
echo "PROCESSAMENTO DA PERDA DE CARGA Pi:\n";
echo "-----------------------------------------------\n";
echo "Linha neutra             = $X cm               \n";
echo "Área de aço              = $As cm²             \n";
echo "-----------------------------------------------\n\n";

?>