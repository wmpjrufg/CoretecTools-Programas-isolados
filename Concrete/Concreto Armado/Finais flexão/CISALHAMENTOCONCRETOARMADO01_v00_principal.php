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

// Nome:    Dimensionamento de seções retangulares submetidas à esforço de cisalhamento
// Versão:  CISALHAMENTOCONCRETOARMADO01_v00_principal
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
// O programa abaixo determina a quantidade armadura necessária para seções retangulares de concreto armado submetidas à um esforço de cisalhamento
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
// vsd           - cortante de cálculo                                 (kN)
// modelo        - Modelo de cálculo para o cisalhamento podendo ser I ou II
// fcd           - Resistência de cálculo à compressão do concreto     (MPa)
// fyd           - Resistência de cálculo à tração do aço              (MPa)
// fywd          - Resistência de cálculo à tração do aço corrigida    (MPa) 
// dlinha        - Altura útil complementar da peça                    (cm)
// ac            - Área da seção transversal                           (cm2)
// inercia       - Momento de inércia                                  (cm4)
// ycg           - Baricentro da peça sem armadura no estádio I        (cm)
// w             - Módulo resistente da peça sem armadura no estádio   (cm3)
// fctm          - Resistência de cálculo à tração do concreto no ensaio de comp. diametral  (MPa)
// fctinf        - Resistência de cálculo à tração do concreto no ensaio de tração direta (MPa)
// fctd          - Resistência de cálculo à tração do concreto de cálculo (MPa)
// alfav2        - Fator para cálculo da biela comprimida
// vrd2          - Cisalhamento máximo na Biela comprimida
// vc            - Resistência ao cisalhamento parcela do concreto    (kN)
// vsw           - Força resistente necessária em armadura de aço     (kN)         
// aswmin        - Armadura de aço mínima                             (cm2/m)
// asw           - Área de aço necessária para cisalhamento           (cm2/m)
// aswfinal      - Área de aço necessária para cisalhamento final     (cm2/m)
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
include_once('CISALHAMENTOCONCRETOARMADO01_v00_erros.php');
include_once('CISALHAMENTOCONCRETOARMADO01_v00_avisos.php');
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
echo "CONCRETO ARMADO - DIMENSIONAMENTO DE SEÇÃO RETANGULARES SOB AÇÃO DE UM ESFORÇO CORTANTE\n";
echo "-----------------------------------------------\n\n";

// Step 1.1: Parâmetros de entrada do algoritmo
$fck        = 25;
$dmax       = 1.9;   
$fyk        = 500;
$bw         = 20;
$h          = 61;
$cob        = 2.0;
$d          = 30;
$vsd        = 30;
$modelo     = 1;

// Step 1.2: Verificação de erros na fase de dados de entrada
if ($fck > 90){
$erro = 1;
CISALHAMENTOCONCRETOARMADO01_v00_erros($erro);
return;
}

if ($cob > 5.5){
$erro = 2;
CISALHAMENTOCONCRETOARMADO01_v00_erros($erro);
return;
}

if ($fyk > 600){
$erro = 3;
CISALHAMENTOCONCRETOARMADO01_v00_erros($erro);
return;
}

if ($fck <= 0 || $fyk <= 0 || $bw <= 0 || $h <= 0 || $cob <= 0 || $d <= 0 || $vsd <= 0 || $dmax <=0){
$erro = 4;
CISALHAMENTOCONCRETOARMADO01_v00_erros($erro);
return;
}

if ($fck > $fyk){
$erro = 5;
CISALHAMENTOCONCRETOARMADO01_v00_erros($erro);
return;
}

if ($d >= $h){
$erro = 6;
CISALHAMENTOCONCRETOARMADO01_v00_erros($erro);
return;
}

if ($dmax > 10){
$erro = 7;
CISALHAMENTOCONCRETOARMADO01_v00_erros($erro);
return;
}


// print setup
echo "-----------------------------------------------\n";
echo "PARÂMETROS DE ENTRADA:\n";
echo "-----------------------------------------------\n";
echo "fck = $fck MPa\n";
echo "fyk = $fyk MPa\n";
echo "bw  = $bw cm\n";
echo "h   = $h cm\n";
echo "cob = $cob cm\n";
echo "d   = $d cm\n";
echo "Cortante (Vd)  = $vsd kN\n";
echo "Modelo de cis. = $modelo\n";
echo "-----------------------------------------------\n\n";
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
// Step 2.1.1: Tratamento da variável fyd conforme orientãção da NBR 6118 (ABNT, 2014)
if ($fyd < 435){
  $fywd = $fyd;
}
else{
  $fywd = 435;
}

// Step 2.2: Determinação altura dlinha
$dlinha = $h - $d;

// Step 2.3: Propriedades da seção sem a consideração de barras de aço
$ac      = $bw * $h;
$inercia = ($bw*pow($h,3))/12;
$ycg     = $h/2;
$w       = $inercia/$ycg;

// Step 2.4: Outros parâmetros para dimensionamento em função do fck
if ($fck <= 50){
  $fctm      = 0.30*pow($fck,(2/3));
} elseif ($fck > 50 && $fck <= 90){
  $fctm      = 2.12*log(1+0.11*$fck);
}
$fctinf = 0.7 * $fctm;
$fctd   = $fctinf / 1.4;

echo "-----------------------------------------------\n";
echo "PROCESSAMENTO DAS PROPRIEDADES DO MATERIAL E SISTEMA:\n";
echo "-----------------------------------------------\n";
echo "fcd            = $fcd MPa\n";
echo "fyd            = $fyd MPa\n";
echo "fctm           = $fctm MPa\n";
echo "fctinf         = $fctinf MPa\n";
echo "fctd           = $fctd MPa\n";
echo "dlinha inicial = $d cm\n";
echo "Área concreto  = $ac cm2\n";
echo "ycg seção      = $ycg cm\n";
echo "Inércia        = $inercia cm4\n";
echo "Módulo W       = $w cm3\n";
echo "-----------------------------------------------\n\n";
//
//
//=========================================================================%
// STEP 3: VERIFICAÇÃO DA BIELA E DIMENSIONAMENTO DA ARMADURA
//=========================================================================%
//
//
// Step 3.1: Verificação da biela de compressão pelo modelo I
if ($modelo == 1){
	$alfav2 = 1 - ($fck/250);
	$vrd2   = 0.27 * $alfav2 * ($fcd/10) * $bw * $d;
} elseif ($modelo == 2){
    //AINDA VAI SER IMPLEMENTADO POR OUTRO ALUNO
}

// Step 3.2: Verificação de aviso da biela comprimida
if ($vsd > $vrd2){
  $aviso = 1;
  CISALHAMENTOCONCRETOARMADO01_v00_avisos($aviso);
}

// Step 3.2: Verificação do valor da contribuição do concreto
if ($modelo == 1){
	$vc = 0.60 * ($fctd / 10) * $bw * $d;
} elseif ($modelo == 2){
    //AINDA VAI SER IMPLEMENTADO POR OUTRO ALUNO
}

// Step 3.3: Total de esforço absorvido pelo aço
$vsw = $vsd - $vc;

echo "-----------------------------------------------\n";
echo "DIMENSIONAMENTO:\n";
echo "-----------------------------------------------\n";
echo "alfav2              = $alfav2\n";
echo "Vrd2                = $vrd2 kN\n";
echo "Vc                  = $vc kN\n";
echo "Vsw                 = $vsw kN\n";
echo "-----------------------------------------------\n\n";

// Step 3.4: Determinação da armadura para o cisalhamento
// Step 3.4.1: Armadura mínima
$aswmin = 20 * ($fctm / $fyk) * $bw;

echo "-----------------------------------------------\n";
echo "CRITÉRIOS PARA ARMADURA:\n";
echo "-----------------------------------------------\n";
echo "Asmin                = $aswmin cm2/m\n";
echo "-----------------------------------------------\n\n";

// Step 3.4.2: Todas as condições de determinação da armadura de cisalhamento
if ($vc >= $vsd){
  $aviso = 2;
  CISALHAMENTOCONCRETOARMADO01_v00_avisos($aviso);
  $aswfinal = $aswmin;
}
else{
  
  $asw = ($vsw / (0.9 * $d * ($fywd / 10))) * 100;

  if ($asw <= $aswmin){
    $aswfinal = $aswmin;
  }
  else{
    $aswfinal = $asw;
  }
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
echo "asw,90   = $aswfinal cm²/m\n";
echo "-----------------------------------------------\n";

?>



