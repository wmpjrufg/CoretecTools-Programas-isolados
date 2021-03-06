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

// Nome:    Dimensionamento de seções retangulares submetidas à flexão pura
// Versão:  FLEXAOPURACONCRETOPROTENDIDO01_v00_principal
// Notas:
// 01-01-19 - A. L. G. Martins and R. V. Dutra finalizaram a versão beta
// 01-01-20 - Prof. W. M. Pereira Junior and L. E. C. Mota Leite introduziram as verificações de erros e avisos
//
//
//
//
//
// ----------

// ---------- Descrição do programa
//
// O programa abaixo determina a quantidade armadura necessária para seções retangulares de concreto armado submetidas à momento fletor
// Para teste utilize: https://repl.it/~
//
// ----------

// ---------- Variáveis e suas descrições
//
// Escalares
//
// fck           - Resistência característica à compressão do concreto         (MPa)
// dmax          - Diâmetro máximo do agregado graúdo                          (cm)
// fpyk          - Resistência característica à tração do aço de protensão     (MPa)
// fptk          - Resistência mínima à ruptura por tração do aço de protenção (MPa)
// bw            - Largura da seção de concreto                              (cm)
// h             - Altura da seção de concreto                               (cm)
// cob           - Cobrimento das armaduras                                  (cm)
// phiestribo    - Diâmetro do estribo                                       (mm)
// d             - Altura útil da peça (0.8.h <= d <= 0.9.h)                 (cm)
// md            - Momento fletor de cálculo                                 (kN.m)
// fcd           - Resistência de cálculo à compressão do concreto           (MPa)
// fyd           - Resistência de cálculo à tração do aço                    (MPa)
// fpd           - Tensão mínima de ruptura                                  (MPa)
// fpyd          - Tensão mínima a 1% de deformação                          (MPa)
// sigmapdinf    - Tensão de protenção                                       (MPa)
// dlinha        - Altura útil complementar da peça                          (cm)
// ac            - Área da seção transversal                                 (cm2)
// inercia       - Momento de inércia                                        (cm4)
// ycg           - Baricentro da peça sem armadura no estádio I              (cm)
// w             - Módulo resistente da peça sem armadura no estádio         (cm3)
// lambda        - Redutor da altura y do diagrama de tensões do concreto
// alfac         - Redutor da tensão (sigma_cd) do diagrama de tensões do concreto
// epsiloncu     - Deformação máximo suportada pelo concreto na compressão
// epsilons      - Alongamento do aço no ELU
// epsilonalong  - Alongamento do aço na protenção
// epsilonu      - Alongamento sobre carga
// epsilonts     - Alongamento total
// duct          - Coeficiente de ductilidade NBR 6118 (item 14.6.4.3)
// kmd           - Momento de cálculo (md) admensional
// kx            - Linha neutra (x) admensional
// kz            - Braço de alavanca (z) admensional
// x e xx        - Linha neutra  por dois modelso de cálculo           (cm)
// num1 e num2   - Verificadores da seção para o momento informado
// epsilonu      - Deformação de escoamento do aço de protenção
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
include_once('FLEXAOPURACONCRETOPROTENDIDO01_v00_erros.php');
include_once('FLEXAOPURACONCRETOPROTENDIDO01_v00_avisos.php');
include_once('FLEXAOPURACONCRETOPROTENDIDO01_v00_detalhamento.php');
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
echo "CONCRETO PROTENDIDO - DIMENSIONAMENTO DE SEÇÃO RETANGULARES SOB AÇÃO DE UM MOMENTO FLETOR\n";
echo "-----------------------------------------------\n\n";

// Step 1.1: Parâmetros de entrada do algoritmo
$fck        = 50;
$dmax       = 1.9;
$fpyk       = 1691.89;
$fptk       = 1897.7;
$bw         = 120;
$h          = 20;
$cob        = 5.0;
$phiestribo = 6.3;
$d          = 80;
$md         = 148.71;
$epsilonu   = 0.035;
$Ep         = 202000;
$sigmapdinf = 1150.5;
$fpd        = 1500;

// Step 1.2: Verificação de erros na fase de dados de entrada
if ($fck > 90) {
    $erro = 1;
    FLEXAOPURACONCRETOPROTENDIDO01_v00_erros($erro);
    return;
}

if ($cob > 5.5) {
    $erro = 2;
    FLEXAOPURACONCRETOPROTENDIDO01_v00_erros($erro);
    return;
}

if ($phiestribo > 16) {
    $erro = 3;
    FLEXAOPURACONCRETOPROTENDIDO01_v00_erros($erro);
    return;
}

if ($fpyk > 1900) {
    $erro = 4;
    FLEXAOPURACONCRETOPROTENDIDO01_v00_erros($erro);
    return;
}

if ($fck <= 0 || $fyk <= 0 || $bw <= 0 || $h <= 0 || $cob <= 0 || $phiestribo <= 0 || $d <= 0 || $md <= 0 || $dmax <=0) {
    $erro = 5;
    FLEXAOPURACONCRETOPROTENDIDO01_v00_erros($erro);
    return;
}

if ($fck > $fyk) {
    $erro = 6;
    FLEXAOPURACONCRETOPROTENDIDO01_v00_erros($erro);
    return;
}

if ($d >= $h) {
    $erro = 7;
    FLEXAOPURACONCRETOPROTENDIDO01_v00_erros($erro);
    return;
}

if ($dmax > 10) {
    $erro = 8;
    FLEXAOPURACONCRETOPROTENDIDO01_v00_erros($erro);
    return;
}

if ($sigmapdinf>$fpyd {
    $erro = 9;
    FLEXAOPURACONCRETOPROTENDIDO01_v00_erros($erro);
    return;
}


// print setup
echo "-----------------------------------------------\n";
echo "PARÂMETROS DE ENTRADA:\n";
echo "-----------------------------------------------\n";
echo "fck  = $fck MPa\n";
echo "DMC  = $dmax cm\n";
echo "fpyk = $fpyk MPa\n";
echo "bw   = $bw cm\n";
echo "h    = $h cm\n";
echo "cob  = $cob cm\n";
echo "d    = $d cm\n";
echo "Φe   = $phiestribo mm\n";
echo "Momento (Md)  = $md kN.m\n";
echo "-----------------------------------------------\n\n";
//
//
//=========================================================================%
// STEP 2: PARÂMETROS INICIAIS PARA O DIMENSIONAMENTO
//=========================================================================%
//
//
// Step 2.1: Determinação da resistência de cálculo para o concreto e aço
$fcd  = $fck / 1.4;
$fpyd = $fpyk / 1.15;
// Step 2.2: Determinação altura dlinha
$dlinha = $h - $d;

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

echo "-----------------------------------------------\n";
echo "PROCESSAMENTO DAS PROPRIEDADES DO MATERIAL E SISTEMA:\n";
echo "-----------------------------------------------\n";
echo "lambda                 = $lambda\n";
echo "alfac                  = $alfac\n";
echo "Módulo de elasticidade = $Ep\n";
echo "Tensão de protensão    = $sigmapdinf\n";
echo "epsiloncu              = $epsiloncu\n";
echo "epsilonsescoa          = $epsilonu\n";
echo "x/d máximo             = $duct\n";
echo "fcd                    = $fcd MPa\n";
echo "fpyd                   = $fpyd MPa\n";
echo "dlinha inicial         = $d cm\n";
echo "Área concreto          = $ac cm2\n";
echo "ycg seção              = $ycg cm\n";
echo "Inércia                = $inercia cm4\n";
echo "Módulo W               = $w cm3\n";
echo "-----------------------------------------------\n\n";
//
//
//=========================================================================%
// STEP 3: DIMENSIONAMENTO DA ARMADURA
//=========================================================================%
//
//
// Step 3.1: Dimensionamento por formulações adimensionais Capítulo 3 --> CARVALHO, R.C.; PINHEIRO, L.M. Cálculo e Detalhamento de Estruturas Usuais de Concreto Armado, v2. 1.ed. São Paulo: Editora PINI Ltda., 2009. 589p.

// Step 3.1.1: Fatores adimensionais e linha neutra da seção
$kmd  = $md * 100 / ($bw * pow($d, 2)* ($fcd/10));

// Step 3.1.2: Trechos da equação de linha neutra (x) na forma geral
$num1 = 2*($md*100/($bw * $alfac * ($fcd/10)));
$num2 = pow($d, 2);

// Step 3.1.3: Cálculo do restante dos fatores admensionais e linha neutra
$kx   = (1 - sqrt(1-(2*$kmd)/$alfac))/$lambda;

// Step 3.1.4: Verificação de erros na fase de dimensionamento
if (is_nan($kx)) {
    $erro = 10;
    FLEXAOPURACONCRETOPROTENDIDO01_v00_erros($erro);
    return;
}


$kz   = (1-0.5*$lambda*$kx);
$xx   = ($d-sqrt($num2-$num1))/$lambda;
$x    = $kx * $d;

// Step 3.2: Checando o domínio da peça
// Step 3.2.2: Determinação do limite entre domínio 2 e 3
$lim23   = $epsiloncu / ($epsiloncu + 0.01);
$lnlim23 = $lim23*$d;

// Step 3.2.3: Determinação do limite entre domínio 3 e 4
$lim34   = $epsiloncu / ($epsiloncu + $epsilonu);
$lnlim34 = $lim34*$d;

// Step 3.2.4: Escolhendo o modelo de dimensionamento (pode ser armadura simples ou dupla)
$dominio  = "";
$armadura = "";

if ($kx < $lim23) {
    $dominio  = "Domínio 2";
    $armadura = "SIMPLES";
    $epsilons = 0.1;
} elseif ($kx >= $lim23 && $kx <= $duct) {
    $dominio  = "Domínio 3";
    $armadura = "SIMPLES";
    $epsilons = ($epsiloncu * $d / $x) - $epsiloncu;
} elseif ($kx > $duct) {
    $dominio  = "Domínio 3";
    $armadura = "DUPLA";
    $epsilons = ($epsiloncu * $d / $x) - $epsiloncu;
    $aviso = 1;
    FLEXAOPURACONCRETOPROTENDIDO01_v00_avisos($aviso);
}

// Step 3.3: Determinação dos alongamentos
// Step 3.3.1: Alongamento do aço no ELU                                
$epsilons = ($epsiloncu * $d / $x) - $epsiloncu;

// Step 3.3.2: Alongamento do aço na protenção                         
if ($sigmapdinf <= $fpyk) {
    $epsilonalong = $sigmapdinf / $Ep;
} elseif ($sigmapdinf > $fpyk) {
    $epsilonalong = ($sigmapdinf-$fpyd)/(($fpd - $fpyd)/($epsilonu - $epsilons));
}

// Step 3.3.3: Alongamento total
$epsilonts = $epsilons + $epsilonalong;                    

// Step 3.3.4: Tensão do aço
if ($epsilonalong < ($fpyd/$Ep) {
	$sigmapd = $Ep * $epsilonts
} elseif ($epsilonalong >= ($fpyd/$Ep) {
	$sigmapd = $fpyd + (($fpd - $fpyd)/($epsilonu - $epsilons)) * ($epsilonts - $epsilons);
}

echo "-----------------------------------------------\n";
echo "DIMENSIONAMENTO:\n";
echo "-----------------------------------------------\n";
echo "kmd                 = $kmd\n";
echo "kx                  = $kx\n";
echo "kz                  = $kz\n";
echo "Linha neutra        = $x cm\n";
echo "Fronteira D2 com D3 = $lim23\n";
echo "LN Front  D2 com D3 = $lnlim23 cm\n";
echo "Fronteira D3 com D4 = $lim34\n";
echo "LN Front  D3 com D4 = $lnlim34 cm\n";
echo "Domínio             = $dominio\n";
echo "Tipo de armadura    = $armadura\n";
echo "-----------------------------------------------\n\n";

// Step 3.3: Momento fletor mínimo
$mdmin  = 0.8 * $w * ($fctksup/10);
$kmdmin = $mdmin / ($bw * pow($d, 2)* ($fcd/10));
$kxmin  = (1 - sqrt(1-(2*$kmdmin)/$alfac))/$lambda;
$kzmin  = (1-0.5*$lambda*$kxmin);

// Step 3.4: Área de aço mínima
$asmin = $mdmin/($kzmin*$d*$fyd/10);

// Step 3.4.1: Taxa de armadura da seção
$ro = ($asmin/$ac)*100;

// Step 3.4.2: Verificação da armadura mínima
if ($ro < 0.15) {
    $asmin = $ac * 0.15/100;
} else {
    $asmin = $asmin;
}

// Step 3.5: Verificação da área de aço máxima
$asmax = $ac * (4/100);

echo "-----------------------------------------------\n";
echo "CRITÉRIOS PARA ARMADURA:\n";
echo "-----------------------------------------------\n";
echo "Momento mínimo       = $mdmin kN.cm\n";
echo "kmdmin               = $kmdmin\n";
echo "kxmin                = $kz\n";
echo "kzmin                = $kzmin\n";
echo "Asmin                = $asmin cm2\n";
echo "Asmax                = $asmax cm2  \n";
echo "Taxa mínima de aço   = $ro %\n";
echo "-----------------------------------------------\n\n";

// Step 3.6: Área de aço necessária
// Step 3.6.1: Condição de armadura simples
if ($armadura == "SIMPLES") {
    $ascalc = ($md*100)/($kz*$d*$fyd/10);
    $as2    = 0;
    if ($ascalc < $asmin) {
        $as1 = $asmin;
    } else {
        $as1 = $ascalc;
    }
}
// Step 3.6.2: Condição de armadura dupla
else {
    $xlim = $duct * $d;
    $zlim = $d - 0.5 * $lambda * $xlim;
    $mlim = $bw * $alfac * ($fcd/10) * $lambda * $xlim * $zlim;
    $m2   = ($md*100) - $mlim;
    $as1  = $mlim / ($zlim * $fyd/10);
    $as2  = $m2 / (($d - $dlinha) * ($fyd/10));
}

// Step 3.6.3: Área total na seção (seja ela tracionada ou comprimida)
$astot  = $as1 + $as2;

// Step 3.6.3: Verificação da armadura máxima da seção
if ($astot > $asmax) {
    $aviso = 2;
    FLEXAOPURACONCRETOPROTENDIDO01_v00_avisos($aviso);
}

// Step 3.7: Armadura de pele na seção
if ($h <= 60) {
    $aspele = 0;
} else {
    $aspele = 0.10 * $ac / 100; //cm²
}
//
//
//=========================================================================%
// STEP 4: IMPRESSÃO DOS RESULTADOS DAS ARMADURAS
//=========================================================================%
//
//
// Step 4.1: Linha neutra e relação βx
// print setup
echo "-----------------------------------------------\n";
echo "RESULTADOS FINAIS DO DIMENSIONAMENTO:\n";
echo "-----------------------------------------------\n";
if ($armadura == "DUPLA") {
    echo "x  = $xlim cm \n";
    echo "βx = x/d = $duct \n";
} elseif ($armadura == "SIMPLES") {
    echo "x  = $x cm \n";
    echo "βx = x/d = $kx \n";
}
// Step 4.2: Dados finais da armadura
echo "$dominio\n";
echo "Asmín   = $asmin cm²\n";
echo "Asmáx   = $asmax cm²\n";
echo "As      = $as1 cm²\n";
echo "As'     = $as2 cm²\n";
echo "As,pele = $aspele cm²\n";
echo "-----------------------------------------------\n";
//
//
//=========================================================================%
// STEP 5: DETALHAMENTO DAS ARMADURAS
//=========================================================================%
//
//
// Step 5.1: Detalhamento das armaduras
echo "\n";
echo "-----------------------------------------------\n";
echo "DETALHAMENTO DAS ARMADURAS\n";
echo "-----------------------------------------------\n";

if ($armadura == "DUPLA") {

echo "\n";
echo "-----------------------------------------------\n";
echo "DETALHAMENTO PARA UMA SITUAÇÃO DE ARMADURA DUPLA\n";
echo "-----------------------------------------------\n";

echo "\nBARRAS TRACIONADAS TIPO AS1\n";
    FLEXAOPURACONCRETOPROTENDIDO01_v00_detalhamento($dmax, $as1, $h, $d, $bw, $cob, $phiestribo);

echo "\nBARRAS COMPRIMIDAS TIPO AS2\n";
    FLEXAOPURACONCRETOPROTENDIDO01_v00_detalhamento($dmax, $as2, $h, $d, $bw, $cob, $phiestribo);

} elseif ($armadura == "SIMPLES") {

echo "\n";
echo "-----------------------------------------------\n";
echo "DETALHAMENTO PARA UMA SITUAÇÃO DE ARMADURA SIMPLES\n";
echo "-----------------------------------------------\n";

echo "\nBARRAS TRACIONADAS TIPO AS1\n";
    FLEXAOPURACONCRETOPROTENDIDO01_v00_detalhamento($dmax, $as1, $h, $d, $bw, $cob, $phiestribo);
    
}
?>