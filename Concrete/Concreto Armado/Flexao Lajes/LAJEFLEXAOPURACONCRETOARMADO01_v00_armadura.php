<?php
// ######################################################################
// UNIVERSIDADE FEDERAL DE CATALÃO (UFCat)
// DEPARTAMENTO DE ENGENHARIA CIVIL & DEPARTAMENTO DE CIÊNCIAS DA COMPUTAÇÃO
// Autores
// Thales Leonardo Ferreira Moura
// Luanna Lopes Lobato
// Wanderlei Malaquias Pereira Junior
// ######################################################################

// Nome:    Dimensionamento de seções lajes submetidas à flexão pura
// Versão:  LAJEFLEXAOPURACONCRETOARMADO01_v00_principal

// ---------- Chamada de funções
//
include_once('LAJEFLEXAOPURACONCRETOARMADO01_v00_erros.php');
include_once('LAJEFLEXAOPURACONCRETOARMADO01_v00_avisos.php');
//
// ----------

function LAJEFLEXAOPURACONCRETOARMADO01_v00_armadura($d,$bw,$fcd,$fyd,$md,$lambda,$alfac,$epsiloncu,$epsilonsyield,$fctksup,$ac,$w,$duct)
{
// Step 3.1.1: Fatores admiensionais e linha neutra da seção
$kmd  = $md * 100 / ($bw * pow ($d, 2)* ($fcd/10));

// Step 3.1.2: Trechos da equação de linha neutra (x) na forma geral
$num1 = 2*($md*100/($bw * $alfac * ($fcd/10)));
$num2 = pow ($d, 2);

// Step 3.1.3: Cálculo do restante dos fatores admensionais e linha neutra
$kx   = (1 - sqrt (1-(2*$kmd)/$alfac))/$lambda;

// Step 3.1.4: Verificação de erros na fase de dimensionamento
if (is_nan($kx)){
$erro = 9;
LAJEFLEXAOPURACONCRETOARMADO01_v00_erros($erro);
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
$lim34   = $epsiloncu / ($epsiloncu + $epsilonsyield);
$lnlim34 = $lim34*$d;

//  Step 3.2.4: Escolhendo o modelo de dimensionamento (pode ser armadura simples ou dupla)
$dominio  = "";
$armadura = "";

if ($kx < $lim23){
  $dominio  = "Domínio 2";
  $armadura = "SIMPLES";
} elseif ($kx >= $lim23 && $kx <= $duct){
  $dominio  = "Domínio 3";
  $armadura = "SIMPLES";
} elseif ($kx > $duct){
  $dominio  = "Domínio 3";
  $armadura = "DUPLA";
  $erro = 10;
  LAJEFLEXAOPURACONCRETOARMADO01_v00_erros($erro);
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
$kmdmin = $mdmin / ($bw * pow ($d, 2)* ($fcd/10));
$kxmin  = (1 - sqrt (1-(2*$kmdmin)/$alfac))/$lambda;
$kzmin  = (1-0.5*$lambda*$kxmin);

// Step 3.4: Área de aço mínima
$asmin = $mdmin/($kzmin*$d*$fyd/10);

// Step 3.4.1: Taxa de armadura da seção
$ro = ($asmin/$ac)*100;

// Step 3.4.2: Verificação da armadura mínima 
if ($ro < 0.15){
  $asmin = $ac * 0.15/100;
} else{
  $asmin = $asmin;
}

echo "-----------------------------------------------\n";
echo "CRITÉRIOS PARA ARMADURA:\n";
echo "-----------------------------------------------\n";
echo "Momento mínimo       = $mdmin kN.cm\n";
echo "kmdmin               = $kmdmin\n";
echo "kxmin                = $kz\n";
echo "kzmin                = $kzmin\n";
echo "Asmin                = $asmin cm2\n";
echo "Taxa mínima de aço   = $ro %\n";
echo "-----------------------------------------------\n\n";

// Step 3.5: Verificação da área de aço máxima
$asmax = $ac * (4/100);

// Step 3.6: Área de aço necessária
// Step 3.6.1: Condição de armadura simples
if ($armadura == "SIMPLES"){
  $ascalc = ($md*100)/($kz*$d*$fyd/10);
  $as2 = 0;
  if ($ascalc < $asmin){
    $as1 = $asmin;
  } else {
    $as1 = $ascalc;
  }
}
$astot  = $as1; 

// Step 3.6.2: Verificação da armadura máxima da seção
if ($astot > $asmax){
  $aviso = 2;
  LAJEFLEXAOPURACONCRETOARMADO01_v00_avisos($aviso); 
}

echo "-----------------------------------------------\n";
echo "ÁREA DE AÇO DA LAJE:\n";
echo "-----------------------------------------------\n";
echo "As     = $astot cm2/m\n";
echo "-----------------------------------------------\n";
}
?>
