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
// Nome:    Determinação da resistência a tração do concreto
// Versão:  CONCRETO01_v00_propriedade_dos_materiais
// Notas:
// 15-04-20 - C. M. S. Carneiro and W. C. do Valle finalizaram a versão beta
//
//
//
// ----------

// ---------- Descrição do programa
//
// O programa abaixo determina a resistência a tração do concreto na  falta  de  ensaios  para  obtenção  de  fct,sp  e  fct,f
//
// ----------

// ---------- Variáveis e suas descrições
//
//
//Escalares:
//fck           - Resistência característica à compressão do concreto (MPa)
//fckj   - Resistência característica à compressão do concreto aos j dias (MPa)
//Classeconcreto- Resistência característica à compressão do concreto (MPa) 
//fctm        - Resistência característica à tração média do concreto (MPa)
//fctkinf  - Resistência característica à tração inferior do concreto (MPa)
//fctksup  - Resistência característica à tração superior do concreto (MPa)
//alphae                - coeficiente que caracteriza o agragado graúdo (?)
//alphai                - coeficiente ?????? (?)
//Eci                                - Módulo de elasticidade inicial (MPa)
//Ecs                                - Módulo de elasticidade secante (MPa)
//Ecit - módulo  de  elasticidade  em  uma  idade  menor  que  28  dias (MPa)
//S                      - coeficiente para o tipo de cimento
//t                      - idade efetiva do concreto (dias)
//Beta1             - Coeficiente relacionado a idade e tipo cimento
//Gamac  - Coeficiente de ponderação da resistência do concreto           
//Fcd              - Resistência de cálculo do concreto (MPa)
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
// STEP 1: CÁLCULO DE FCTKINF E FCTKSUP
//=========================================================================%
//
//print setup
echo "-----------CORETEC TOOLS - CONCRETE-----------\n";
echo "DETERMINAÇÃO DA RESISTÊNCIA DE PROJETO\n";
echo "-----------------------------------------------\n\n";
//
// Step 1.1: Parâmetros de entrada do algoritmo
$fck        = 40; 
// print setup
echo "PARÂMETROS DE ENTRADA:\n";
echo "-----------------------------------------------\n";
echo "Resistência característica à compressão do concreto  = $fck MPa\n";  
//
//Step 1.2: Determinação de Fctm
if ($fck<50) {
	$fctm = 0.3*$fck^(2/3);
} else {
	$fctm = 2.12*log(1+0.11*$fck);
}
//
//Step 1.3 : Determinação do Fctkinf
	$fctkinf = 0.7*$fctm;
//Step 1.5 : Determinação do Fctksup
	$fctksup = 1.3*$fctm;
//
// print setup
//
echo "DETERMINAÇÃO DO FCTKINF E FCTKSUP:\n";
echo "-----------------------------------------------\n";
//
echo "Resistência característica à tração média do concreto  = $fctm MPa\n";
echo "Resistência característica à tração inferior do concreto  = $fctkinf MPa\n";
echo "Resistência característica à tração superior do concreto  = $fctksup MPa\n";
//
//=========================================================================%
// STEP 2: CÁLCULO DE ECI E ECS
//=========================================================================%
//
//
//print setup
echo "\n";
echo "-----------------------------------------------\n";
echo "CÁLCULO DE ECI E ECS:\n";
echo "-----------------------------------------------\n\n";
//
// Step 2.1: Parâmetros de entrada do algoritmo
$alphae                = 1.2;  
//
// print setup
echo "PARÂMETROS DE ENTRADA:\n";
echo "-----------------------------------------------\n";
echo "coeficiente que caracteriza o agragado graúdo  = $alphae \n";
//
//Step 2.2: Determinação do Eci
if ($fck<50) {
	$Eci = $alphae*5600*($fck)^(1/2);
} else {
	$Eci = 21500*$alphae*(($fck/10)+1.25)^(1/3);
}
//Step 2.3 : Determinação do Alphai
	$alphai = 0.8+0.2*($fck/80);
//como aplicar uma restrição que ele deve ser menor que 1?
//Step 2.4 : Determinação do Ecs
	$Ecs = $alphai*$Eci;
// print setup
echo "-----------------------------------------------\n";
echo "DETERMINAÇÃO DO Eci E Ecs:\n";
echo "-----------------------------------------------\n";
echo "Módulo de elasticidade inicial  = $Eci MPa\n";
echo "Módulo de elasticidade secante  = $Ecs MPa\n";
//
//
//=========================================================================%
// STEP 3: DETERMINAÇÃO DA RESISTÊNCIA DE CÁLCULO DO CONCRETO
//=========================================================================%
//
//print setup
echo "\n";
echo "-----------------------------------------------\n";
echo "DETERMINAÇÃO DA RESISTÊNCIA DE CÁLCULO DO CONCRETO\n";
echo "-----------------------------------------------\n\n";
//
// Step 3.1: Parâmetros de entrada do algoritmo
$S        = 0.2; 
$t        = 7;
$Gamac    =1.4;
// print setup
echo "PARÂMETROS DE ENTRADA:\n";
echo "-----------------------------------------------\n";
echo "Coeficiente para o tipo de cimento  = $S \n";
echo "Idade efetiva do concreto           = $t dias\n";
echo "Coeficiente de ponderação da resistência do concreto = $Gamac\n";
//
//Step 3.2: Determinação do Beta1
$Beta1    = exp($S*(1-sqrt(28/$t)));
//
//Step 3.3: Determinação do Fcd
$Fcd      = $Beta1*($fck/$Gamac);
// print setup
echo "-----------------------------------------------\n";
echo "RESULTADO PARA RESISTÊNCIA DE CÁLCULO DO CONCRETO:\n";
echo "-----------------------------------------------\n";
echo "Coeficiente relacionado a idade e tipo cimento  = $Beta1\n";
echo "Resistência de cálculo do concreto = $Fcd MPa\n";
//
//
//=========================================================================%
// STEP 4: DETERMINAÇÃO DO MÓDULO DE ELASTICIDADE EM CONCRETOS COM IDADE INFERIOR A 28 DIAS
//=========================================================================%
//
//print setup
echo "\n";
echo "-----------------------------------------------\n";
echo "DETERMINAÇÃO DO MÓDULO DE ELASTICIDADE EM CONCRETOS COM IDADE INFERIOR A 28 DIAS\n";
echo "-----------------------------------------------\n\n";
//
// Step 4.1: Parâmetros de entrada do algoritmo
$fckj          = 20;
// print setup
echo "PARÂMETROS DE ENTRADA:\n";
echo "-----------------------------------------------\n";
echo "Resistência característica à compressão do concreto aos j dias  = $fckj MPa \n";
//
//Step 4.2: Determinação do Ecit
if ($fck<50) {
	$Ecit = (pow(($fckj/$fck),(0.5)))*$Eci;
} else {
	$Ecit = (pow(($fckj/$fck),(0.3)))*$Eci;
}
//
// print setup
echo "-----------------------------------------------\n";
echo "RESULTADO PARA MÓDULO DE ELASTICIDADE:\n";
echo "-----------------------------------------------\n";
echo "MÓDULO COM IDADE INFERIOR A 28 DIAS = $Ecit MPa\n";
echo "-----------------------------------------------\n";
echo "-----------------------------------------------\n";
//
?>

