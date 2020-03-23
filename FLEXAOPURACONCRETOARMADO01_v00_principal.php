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
// Marcos Napoleão Rabelo 
// Wanderlei Malaquias Pereira Junior
// ######################################################################

// Nome:    Dimensionamento de seções retangulares à flexão pura
// Versão:  FLEXAOPURACONCRETOARMADO01_v00_principal
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
// O programa abaixo determina a quantidade armadura necessária para seções retangulares em concreto armado
//
// ----------

// ---------- Variáveis e suas descrições
//
// Escalares
//
// fck           - Resistência característica à compressão do concreto (MPa)
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
// x             - Linha neutra                                        (cm)
// epsilonsyield - Deformação de escoamento do aço escolhido
// lim23         - Fronteira do domínio 2 com o 3
// lim34         - Fronteira do domínio 3 com o 4
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


//
//
//=========================================================================%
// STEP 1: DECLARAÇÃO DE VARIÁVEIS E SETUP INICIAL
//=========================================================================%
//
//
// print setup
echo "-----------CORETEC TOOLS - CONCRETE-----------\n";
echo "CONCRETO ARMADO - DIMENSIONAMENTO DE SEÇÃO RETANGULARES SOB AÇÃO DE UM MOMENTO FLETOR\n";
echo "-----------------------------------------------\n\n";

// Step 1.1: Parâmetros de entrada do algoritmo
$fck        = 20;   
$fyk        = 500;
$bw         = 12;
$h          = 33;
$cob        = 2.5;
$phiestribo = 6.3;
$d          = 29;
$md         = 63;

// Step 1.2: Verificação de erros na fase STEP 1
if ($fck > 90){
$erro = 1;
function FLEXAOPURACONCRETOARMADO01_v00_erros($erro)
}
if ($cob > 5.5){
$erro = 2;
function FLEXAOPURACONCRETOARMADO01_v00_erros($erro)
}
if ($phiestribo > 16){
$erro = 3;
function FFLEXAOPURACONCRETOARMADO01_v00_erros($erro)
}
if ($fyk > 600){
$erro = 4;
function FLEXAOPURACONCRETOARMADO01_v00_erros($erro)
}
if ($fck > $fyk){
$erro = 5;
function FLEXAOPURACONCRETOARMADO01_v00_erros($erro)
}
if ($d >= $h){
$erro = 6;
function FLEXAOPURACONCRETOARMADO01_v00_erros($erro)
}

// print setup
echo "Parâmetros de entrada:\n\n";
echo "fck = $fck MPa\n";
echo "fyk = $fyk MPa\n";
echo "bw  = $bw cm\n";
echo "h   = $h cm\n";
echo "cob = $cob cm\n";
echo "d   = $d cm\n";
echo "Φe  = $phiestribo mm\n";
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

// Step 2.2: Determinação altura dlinha
$dlinha = $cob + ($phiestribo/10) + (1.0 / 2);

// Step 2.3: Propriedades da seção sem a consideração de barras de aço
$ac      = $bw * $h;
$inercia = ($bw*pow($h,3))/12;
$ycg     = $h/2;
$w       = $inercia/$ycg;

// Step 2.4: Outros parâmetros para dimensionamento em função do fck
if ($fck <= 50){
  $lambda    = 0.8;
  $alfac     = 0.85;
  $epsiloncu = 0.0035;
  $duct      = 0.45;
  $fctm      = 0.30*pow($fck,(2/3));
}
elseif ($fck > 50 && $fck <= 90){
  $lambda    = 0.8 - ($fck - 50)/400;
  $alfac     = 0.85 * (1 - ($fck - 50)/200);
  $epsiloncu = 0.0026+0.035*pow((90-$fck)/100,4);
  $duct      = 0.35;
  $fctm      = 2.12*log(1+0.11*$fck);
}
$fctksup     = 1.3*$fctm; //MPa
//
//
//=========================================================================%
// STEP 3: DIMENSIONAMENTO DA ARMADURA
//=========================================================================%
//
//
// Step 3.1: Dimensionamento por formulações admiensionais Capítulo 3 --> CARVALHO, R.C.; PINHEIRO, L.M. Cálculo e Detalhamento de Estruturas Usuais de Concreto Armado, v2. 1.ed. São Paulo: Editora PINI Ltda., 2009. 589p. 
$kmd = $md * 100 / ($bw * pow ($d, 2)* ($fcd/10));
$kx  = (1 - sqrt (1-(2*$kmd)/$alfac))/$lambda;
$kz  = (1-0.5*$lambda*$kx);
$x   = $kx * $d;

// Step 3.2: Checando o domínio da peça
// Step 3.2.1: Deformação de escoamento do aço a ser considerada
if ($fyk == 250){
  $epsilonsyield = 1.04/1000;
}
elseif ($fyk == 500){
  $epsilonsyield = 2.07/1000;
elseif ($fyk == 600){
  $epsilonsyield = 2.48/1000;
}

// Step 3.2.2: Determinação do limite entre domínio 2 e 3
$lim23 = $epsiloncu / ($epsiloncu + 0.01);

// Step 3.2.3: Determinação do limite entre domínio 3 e 4
$lim34 = $epsiloncu / ($epsiloncu + $epsilonsyield);

//  Step 3.2.4: Escolhendo o modelo de dimensionamento (pode ser armadura simples ou dupla)
if ($kx < $lim23){
  $dominio  = "Domínio 2";
  $armadura = "SIMPLES";
}
elseif ($kx >= $lim23 && $kx <= $duct){
  $dominio  = "Domínio 3";
  $armadura = "SIMPLES";
}
elseif ($kx > $duct){
  $dominio = "Domínio 3";
  $armadura = "DUPLA";
  $aviso = 1;
  function FLEXAOPURACONCRETOARMADO01_v00_avisos($aviso)
}

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
}
else{
  $asmin = $asmin;
}

// Step 3.5: Verificação da área de aço máxima
$asmax = $ac * (4/100);

// Step 3.6: Área de aço necessária
// Step 3.6.1: Condição de armadura simples
if ($armadura == "SIMPLES"){
  $ascalc = ($md*100)/($kz*$d*$fyd/10);
  $as2 = 0;
  if ($ascalc < $asmin){
    $as1 = $asmin;
  }
  else{
    $as1 = $ascalc;
  }
}

// Step 3.6.2: Condição de armadura dupla
else{
  $xlim = $duct * $d;
  $zlim = $d - 0.5 * $lambda * $xlim;
  $mlim = $bw * $alfac * ($fcd/10) * $lambda * $xlim * $zlim;
  $m2 = ($md*100) - $mlim;
 
  $as1 = $mlim / ($zlim * $fyd/10);
  $as2 = $m2 / (($d - $dlinha) * ($fyd/10));
}
$astot = $as1 + $as2; 

// Step 3.6.3: Verificação da armadura máxima da seção
if ($astot > $asmax){
  $aviso = 2;
  function FLEXAOPURACONCRETOARMADO01_v00_avisos($aviso) 
}

// Step 3.7: Armadura de pele na seção
if ($h <= 60){
  $aspele = 0;
}
else{
  $aspele = 0.10 * $ac / 100; //cm²
}
//
//
//=========================================================================%
// STEP 4: IMPRESSÃO DOS RESULTADOS
//=========================================================================%
//
//
// Step 4.1: Linha neutra e relação βx
// print setup
echo "RESULTADOS:\n\n";

if ($armadura == "DUPLA"){
  echo "x  = $xlim cm \n";
  echo "βx = x/d = $duct \n";
}
elseif ($armadura == "SIMPLES"){
  echo "x  = $x cm \n";
  echo "βx = x/d = $kx \n";
}

// Step 4.2: Dados da armadura
echo "$dominio\n";
echo "Asmín   = $asmin cm²\n";
echo "Asmáx   = $asmax cm²\n";
echo "As      = $as1 cm²\n";
echo "As'     = $as2 cm²\n";
echo "As,pele = $aspele cm²\n\n";
