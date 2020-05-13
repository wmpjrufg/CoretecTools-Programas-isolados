<?php
// ######################################################################
// UNIVERSIDADE FEDERAL DE CATALÃO (UFCat)
// DEPARTAMENTO DE ENGENHARIA CIVIL & DEPARTAMENTO DE CIÊNCIAS DA COMPUTAÇÃO
// Autores
// Carlos Magno Silva Carneiro
// Walfrido Carvalho do Valle
// Colaboradores
// Luiz Eduardo Costa Mota Leite
// Wanderlei Malaquias Pereira Junior
// ######################################################################
//
//
// Nome:    Perda de protensão por deformação inicial
// Versão:  CONCRETO02_v00_perda_de_prot_por_def_inicial
// Notas:
// 15-04-20 - C. M. S. Carneiro and W. C. do Valle finalizaram a versão beta
//
//
//
// ----------

// ---------- Descrição do programa
//
// O programa abaixo determina a perda de protensão por encurtamento elástico imediato.
//
// ----------

// ---------- Variáveis e suas descrições
//
//
//Escalares:
//fck           - Resistência característica à compressão do concreto (MPa)
//Ep                     - Módulo de elasticidade do aço de protensão (MPa)
//Eci                                - Módulo de elasticidade inicial (MPa)
//Alphap                                              -Razão modular (MPa)
//Gamab                                         - Altura do CG da peça (cm)
//Apcg                     - Altura do centro da armadura de protensão (cm)
//ep                         - Excentricidade da armadura de protensão (cm)
//Sigmapa                               - Tensão na armadura ancorada (MPa)
//fptk                       - Resistência a tração do aço na ruptura (MPa)
//Ap                                  - Área da armadura de protensão (cm2)
//Pa                                     - Força de protensão ancorada (kN)
//gpp                                         - Peso próprio da viga (kN/cm)
//l                                                       - Vão da viga (cm) 
//Mpp                 - Momento fletor máximo devido ao peso próprio (kNcm)
//Po  - Força de protensão após transferência de protensão para a peça (kN)
//Ach                                    - área da seção homogenizada (cm2)
//Ac                                            - área da seção bruta (cm2)
//Ih                       - Momento de inércia da seção homogenizada (cm4)
//Ic                              - Momento de inércia da seção bruta (cm4)
//Sigmacp - tensão concreto em determinada seção ao nível da armadura de protensão (Mpa)
//
//
//Vetores:
//
//
//
//
//Matrizes:
//
//
//=========================================================================%
// STEP 1: CÁLCULO DA PERDA POR ENCURTAMENTO ELÁSTICO IMEDIATO
//=========================================================================%
//
//print setup
echo "Coretec Tools";
//
// Step 1.1: Parâmetros de entrada do algoritmo
$Ep        = 196000;
$Eci       = 39756;
$fptk      = 1900; 
$Ap        = 12;
$Ic        = 1788188;
$Gamab     =41.77;
$apcg      =8.5;
$Ac        =3525;
$l         =1400;
$gpp       =0.0881;
//
// print setup
echo "-----------------------------------------------\n";
echo "PARÂMETROS DE ENTRADA:\n";
echo "-----------------------------------------------\n";
echo "Módulo de elasticidade do aço de protensão  = $Ep MPa\n";
echo "Módulo de elasticidade inicial  = $Eci MPa\n";
echo "Resistência a tração do aço na ruptura  = $fptk MPa\n";
echo "Área da armadura de protensão  = $Ap cm2\n";
echo "Momento de inércia da seção bruta  = $Ic cm4\n";
echo "Altura do CG da peça  = $Gamab cm\n";
echo "Altura do centro da armadura de protensão  = $apcg cm\n";
echo "Vão da viga  = $l cm\n";
echo "Peso próprio da viga  = $gpp kN/cm\n";
echo "Área da seção bruta = $Ac cm2\n";
//
//
//Step 1.2: Cálculo da razão modular
$Alphap      = $Ep/$Eci;
//
//Step 1.3: Cálculo da excentricidade da armadura de protensão
$ep          = $Gamab-$apcg;
//Step 1.4 : Determinação da tensão na armadura ancorada
$Sigmapa     = 0.77*$fptk;
//
//Step 1.4 : Cálculo da força de protensão ancorada
$Pa          =(($Sigmapa*1000000/10000)*$Ap)/1000; 
//
//Step 1.4 : Cálculo do momento fletor máximo devido ao peso próprio
$Mpp         = ($gpp*$l*$l)/8;
//
//Step 1.5 : Cálculo da área da seção homogenizada
$Ach         = $Ac;
//
//Step 1.6 : Momento de inércia da seção homogenizada
$Ih          = $Ic;
//	
//Step 1.7 : Cálculo da tensão concreto em determinada seção ao nível da armadura de protensão
$Sigmacp     = ($Mpp*$ep/$Ih)-($Pa/$Ach)-(($Pa*$ep*$ep)/$Ih);
//
// print setup
echo "-----------------------------------------------\n";
echo "PARÂMETROS DE SAÍDA:\n";
echo "-----------------------------------------------\n";
echo "Razão modular  = $Alphap MPa\n";
echo "Excentricidade da armadura de protensão = $ep cm\n";
echo "Tensão na armadura ancorada = $Sigmapa MPa\n";
echo "Força de protensão ancorada = $Pa kN\n";
echo "Momento fletor máximo devido ao peso próprio - conferir esse resultado com trabalho unesp = $Mpp kNcm\n";
echo "Área da seção homogenizada = $Ach cm2\n";
echo "Momento de inércia da seção homogenizada = $Ih cm4\n";
echo "tensão concreto em determinada seção ao nível da armadura de protensão = $Sigmacp MPa\n";
//
?>