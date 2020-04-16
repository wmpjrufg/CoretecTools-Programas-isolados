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
// ATUALIZAR ?????????????????????????????
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
//Classeconcreto- Resistência característica à compressão do concreto (MPa) 
//fctm        - Resistência característica à tração média do concreto (MPa)
//fctkinf  - Resistência característica à tração inferior do concreto (MPa)
//fctksup  - Resistência característica à tração superior do concreto (MPa)
//alphae                - coeficiente que caracteriza o agragado graúdo (?)
//alphai                - coeficiente ?????? (?)
//Eci                                - Módulo de elasticidade inicial (MPa)
//Ecs                                - Módulo de elasticidade secante (MPa)
//Tipoagregado                                          ?????
//Ep                     - Módulo de elasticidade do aço de protensão (MPa)
//Alphap                                                - Razão modular (?)
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
//Matrizes:
//
//
//=========================================================================%
// STEP 1: CÁLCULO DE FCTKINF E FCTKSUP
//=========================================================================%
//
//print setup
echo "-----------CORETEC TOOLS - CONCRETE-----------\n";
echo "CONCRETO - DETERMINAÇÃO DA RESISTÊNCIA DE PROJETO\n";
echo "-----------------------------------------------\n\n";
//
// Step 1.1: Parâmetros de entrada do algoritmo
$fck        = 20;   

//Step 1.2: Determinação de Fctm
if ($fck<50) {
	$fctm = 0.3*$fck^(2/3);
} else {
	$fctm = 2.12*log(1+0.11*$fck);
}

//Step 1.3 : Determinação do Fctkinf
	$fctkinf = 0.7*$fctm;
//Step 1.5 : Determinação do Fctksup
	$fctksup = 1.3*$fctm;

?>
