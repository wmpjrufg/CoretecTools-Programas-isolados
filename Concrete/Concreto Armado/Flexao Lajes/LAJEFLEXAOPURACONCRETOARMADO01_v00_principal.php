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

// Nome:    Dimensionamento de seções lajes submetidas à flexão pura
// Versão:  LAJEFLEXAOPURACONCRETOARMADO01_v00_principal
// Notas:
// 20-04-20 - T. L. F. Moura finalizaram a versão beta
//
//
//
//
//
// ----------

// ---------- Descrição do programa
//
// O programa abaixo determina a quantidade armadura necessária para seções retangulares de concreto armado submetidas à momento fletor
//
// ----------

// ---------- Variáveis e suas descrições
//
// Escalares
//
// fck           - Resistência característica à compressão do concreto (MPa)
// dmax          - Diâmetro máximo do agregado graúdo                  (cm)
// fyk           - Resistência característica à tração do aço          (MPa)
// bw            - Largura da seção de concreto                        (cm)
// h             - Altura da seção de concreto                         (cm)
// cob           - Cobrimento das armaduras                            (cm)
// phiestribo    - Diâmetro do estribo                                 (mm)
// d             - Altura útil da peça (0.8.h <= d <= 0.9.h)           (cm)
// md            - Momento fletor de cálculo                           (kN.m)
// fcd           - Resistência de cálculo à compressão do concreto     (MPa)
// fyd           - Resistência de cálculo à tração do aço              (MPa)
// dlinha        - Altura útil complementar da peça                    (cm)
// ac            - Área da seção transversal                           (cm2)
// inercia       - Momento de inércia                                  (cm4)
// ycg           - Baricentro da peça sem armadura no estádio I        (cm)
// w             - Módulo resistente da peça sem armadura no estádio   (cm3)
// lambda        - Redutor da altura y do diagrama de tensões do concreto
// alfac         - Redutor da tensão (sigma_cd) do diagrama de tensões do concreto
// epsiloncu     - Deformação máximo suportada pelo concreto na compressão
// duct          - Coeficiente de ductilidade NBR 6118 (item 14.6.4.3)
// fctm          - Resistência de cálculo à tração do concreto no ensaio de comp. diametral  (MPa)
// fctksup       - Resistência de cálculo à tração do concreto no ensaio de tração na flexão (MPa)
// kmd           - Momento de cálculo (md) admensional
// kx            - Linha neutra (x) admensional
// kz            - Braço de alavanca (z) admensional
// x e xx        - Linha neutra  por dois modelso de cálculo           (cm)
// num1 e num2   - Verificadores da seção para o momento informado
// epsilonsyield - Deformação de escoamento do aço escolhido
// lim23         - Fronteira do domínio 2 com o 3
// lnlim23       - Limite de linha neutra para D2 com D3
// lim34         - Fronteira do domínio 3 com o 4
// lnlim34       - Limite de linha neutra para D3 com D4
// dominio       - Tipo do domínio
// armadura      - Modelo de armação escolhido podendo ser simples ou dupla
// mdmin         - Momento fletor mínimo                              (kN.cm)
// kmdmin        - Momento de cálculo mínimo (mdmin) admensional
// kxmin         - Linha neutra (x) admensional para um mdmin
// kzmin         - Braço de alavanca (z) admensional para um mdmin
// asmin         - Armadura de aço mínima                             (cm2)
// ro            - Taxa de armadura na seção transversal              (%)
// asmax         - Armadura de aço máxima                             (cm2)
// ascalc        - Área de aço necessária na região tracionada        (cm2)
// as1           - Área de aço na região tracionada                   (cm2)
// as2           - Área de aço na região comprimida                   (cm2)
// xlim          - Linha neutra limite para seção                     (cm)
// xlim          - Linha neutra limite fronteira D3-4 para seção      (cm)
// zlim          - Braço de alavanca limite fronteira D3-4 para seção (cm)
// mlim          - Momento limite fronteira D3-4 para seção           (kN.cm)
// m2            - Momento fletor para armadura comprimida            (kN.cm)
// astot         - Área de aço total na seção                         (cm2)
// aspele        - Armadura de pele da seção                          (cm2)
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

// ---------- Chamada de funções
//
include_once('LAJEFLEXAOPURACONCRETOARMADO01_v00_erros.php');
include_once('LAJEFLEXAOPURACONCRETOARMADO01_v00_avisos.php');
include_once('LAJEFLEXAOPURACONCRETOARMADO01_v00_detalhamento.php');
include_once('LAJEFLEXAOPURACONCRETOARMADO01_v00_armadura.php');

//
// ----------

//
//
//=========================================================================%
// STEP 1: DECLARAÇÃO DE VARIÁVEIS E SETUP INICIAL
//=========================================================================%
//
//
// print setup
echo "-----------CORETEC TOOLS - CONCRETE-----------\n";
echo "CONCRETO ARMADO - DIMENSIONAMENTO DE LAJES SOB AÇÃO DE UM MOMENTO FLETOR\n";
echo "-----------------------------------------------\n\n";

// Step 1.1: Parâmetros de entrada do algoritmo
$fck        = 55;
$dmax       = 1.9;
$fyk        = 500;
$h          = 12;
$cob        = 2.5;
$dy         = 9;
$dx         = 8;
$d_neg      = 9;
$Lx         = 300;
$Ly         = 300;
$Mdx        = 25;
$Mdy        = 27;
$Mdneg_1    = 21;
$Mdneg_2    = 20;
$Mdneg_3    = 22;
$Mdneg_4    = 23;
$bw         =100;      // valor nunca deve ser alterado

// print setup
echo "-----------------------------------------------\n";
echo "PARÂMETROS DE ENTRADA:\n";
echo "-----------------------------------------------\n";
echo "fck = $fck MPa\n";
echo "DMC = $dmax cm\n";
echo "fyk = $fyk MPa\n";
echo "bw  = $bw cm\n";
echo "h   = $h cm\n";
echo "Lx  = $Lx cm\n";
echo "Ly  = $Ly cm\n";
echo "cob = $cob cm\n";
echo "dx  = $dx cm\n";
echo "dy  = $dy cm\n";
echo "d_neg  = $d_neg cm\n";
echo "Momento (Mdx)  = $Mdx kN.m/m\n";
echo "Momento (Mdy)  = $Mdy kN.m/m\n";
echo "Momento (Mdneg_1)  = $Mdneg_1 kN.m/m\n";
echo "Momento (Mdneg_2)  = $Mdneg_2 kN.m/m\n";
echo "Momento (Mdneg_3)  = $Mdneg_3 kN.m/m\n";
echo "Momento (Mdneg_4)  = $Mdneg_4 kN.m/m\n";
echo "-----------------------------------------------\n\n";
//
//
//
//=========================================================================%
// STEP 2: PARÂMETROS INICIAIS PARA O DIMENSIONAMENTO
//=========================================================================%
//
//
// Step 2.1: Determinação da resistência de cálculo para o concreto e aço
$fcd = $fck / 1.4;
$fyd = $fyk / 1.15;

// Step 2.2: Determinação altura dlinha
$dylinha = $h - $dy;
$dxlinha = $h - $dx;

// Step 2.3: Propriedades da seção sem a consideração de barras de aço
$ac      = $bw * $h;
$inercia = ($bw*pow($h, 3))/12;
$ycg     = $h/2;
$w       = $inercia/$ycg;

// Step 2.4: Outros parâmetros para dimensionamento em função do fck
if ($fck <= 50) {
    $lambda    = 0.8;
    $alfac     = 0.85;
    $epsiloncu = 0.0035;
    $duct      = 0.45;
    $fctm      = 0.30*pow($fck, (2/3));
} elseif ($fck > 50 && $fck <= 90) {
    $lambda    = 0.8 - ($fck - 50)/400;
    $alfac     = 0.85 * (1 - ($fck - 50)/200);
    $epsiloncu = 0.0026+0.035*pow((90-$fck)/100, 4);
    $duct      = 0.35;
    $fctm      = 2.12*log(1+0.11*$fck);
}
$fctksup     = 1.3*$fctm;

// Step 2.5: Deformação de escoamento do aço a ser considerada
if ($fyk == 250) {
    $epsilonsyield = 1.04/1000;
} elseif ($fyk == 500) {
    $epsilonsyield = 2.07/1000;
} else {
    $epsilonsyield = 2.48/1000;
}

echo "-----------------------------------------------\n";
echo "PROCESSAMENTO DAS PROPRIEDADES DO MATERIAL E SISTEMA:\n";
echo "-----------------------------------------------\n";
echo "lambda         = $lambda\n";
echo "alfac          = $alfac\n";
echo "epsiloncu      = $epsiloncu\n";
echo "epsilonsescoa  = $epsilonsyield\n";
echo "x/d máximo     = $duct\n";
echo "fcd            = $fcd MPa\n";
echo "fyd            = $fyd MPa\n";
echo "fctm           = $fctm MPa\n";
echo "dxlinha inicial = $dxlinha cm\n";
echo "dylinha inicial = $dylinha cm\n";
echo "Área concreto  = $ac cm2\n";
echo "ycg seção      = $ycg cm\n";
echo "Inércia        = $inercia cm4\n";
echo "Módulo W       = $w cm3\n";
echo "-----------------------------------------------\n\n";
//
//
//=========================================================================%
// STEP 3: DIMENSIONAMENTO DA ARMADURA
//=========================================================================%
//
//
// Step 3.1: Dimensionamento por formulações admiensionais Capítulo 3 --> CARVALHO, R.C.; PINHEIRO, L.M. Cálculo e Detalhamento de Estruturas Usuais de Concreto Armado, v2. 1.ed. São Paulo: Editora PINI Ltda., 2009. 589p.

// Step 3.1.1: Profundidade da linha neutra
LAJEFLEXAOPURACONCRETOARMADO01_v00_armadura($dx,$bw,$fcd,$fyd,$Mdx,$lambda,$alfac,$epsiloncu,$epsilonsyield,$fctksup,$ac,$w,$duct); 
LAJEFLEXAOPURACONCRETOARMADO01_v00_armadura($dy,$bw,$fcd,$fyd,$Mdy,$lambda,$alfac,$epsiloncu,$epsilonsyield,$fctksup,$ac,$w,$duct);
LAJEFLEXAOPURACONCRETOARMADO01_v00_armadura($d_neg,$bw,$fcd,$fyd,$Mdneg_1,$lambda,$alfac,$epsiloncu,$epsilonsyield,$fctksup,$ac,$w,$duct);
LAJEFLEXAOPURACONCRETOARMADO01_v00_armadura($d_neg,$bw,$fcd,$fyd,$Mdneg_2,$lambda,$alfac,$epsiloncu,$epsilonsyield,$fctksup,$ac,$w,$duct);
LAJEFLEXAOPURACONCRETOARMADO01_v00_armadura($d_neg,$bw,$fcd,$fyd,$Mdneg_3,$lambda,$alfac,$epsiloncu,$epsilonsyield,$fctksup,$ac,$w,$duct);
LAJEFLEXAOPURACONCRETOARMADO01_v00_armadura($d_neg,$bw,$fcd,$fyd,$Mdneg_4,$lambda,$alfac,$epsiloncu,$epsilonsyield,$fctksup,$ac,$w,$duct);

//
//
//
/*
echo "-----------------------------------------------\n";
echo "ÁREA DE AÇO DA LAJE:\n";
echo "-----------------------------------------------\n";
echo "As_x     = $As_x cm2/m\n";
echo "As_y     = $As_y cm2/m\n";
echo "As_neg_1 = $As_neg_1 cm2/m\n";
echo "As_neg_2 = $As_neg_2 cm2/m\n";
echo "As_neg_3 = $As_neg_3 cm2/m\n";
echo "As_neg_4 = $As_neg_4 cm2/m\n";
echo "-----------------------------------------------\n";
*/

?>