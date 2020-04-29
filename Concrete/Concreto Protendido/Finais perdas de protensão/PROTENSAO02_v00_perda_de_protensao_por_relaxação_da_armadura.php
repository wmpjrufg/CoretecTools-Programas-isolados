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

// Name:    Determinação da perda de tensão por relaxação da armadura
// Version: PROTENSAO02_v00_perda_de_protensao_por_relaxação_da_armadura
// Notes:
// 
//
//
//
//
//
// ---------- Descrição do programa:
//
// O programa abaixo determina a perda de tensão devido a relaxação do aço, que é o alívo de tensões na armadura enquanto ela é mantida com comprimento constante ou deformação constante. 
//
// ----------

// ---------- Descrição das variáveis
//
// Escalares:
//
// psi1000        - Coeficiente de relaxação medidos após 1000 horas                         (%)
// t0             - Tempo inicial                                                            (horas)
// t              - Tempo final                                                              (horas)
// psit           - Intensidade da relaxação do aço no intervalo de t0 até o tempo t         (%)
// Ap             - Área do aço                                                              (mm²)
// Pi             - Carga inicial da protensão                                               (ton)
// P              - Carga após perda por relaxação do aço                                    (ton)
// sigmapi        - Tensão na armadura de protensão no instante de seu estiramento           (ton/mm²)
// deltasigmap    - Perda de tensão por relaxação no intervalo t0 até o tempo t              (ton/mm²)
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
echo "PROJETO DE PEÇAS PROTENDIDAS - CÁLCULO DA PERDA DE TENSAO POR RELAXAÇÃO DA ARMADURA\n";
echo "-----------------------------------------------\n\n";

// Step 1.1: Definição dos parâmetros de entrada

$psi1000  = 8.5;
$t0       = 0;
$t        = 25;
$Pi       = 10;
$Ap       = 10;

// print setup
echo "-----------------------------------------------\n";
echo "PARÂMETROS DE ENTRADA:\n";
echo "-----------------------------------------------\n";
echo "Carga de protensão                = $Pi ton\n";
echo "Área da seção transvesal do cabo  = $Ap mm\n";
echo "Tempo inicial                     = $t0 horas\n";
echo "Tempo final                       = $t horas\n";
echo "-----------------------------------------------\n\n";

// Step 1.2: Determinação da intensidade da relaxação do aço

$resultadoexp = pow((($t - $t0)/1000), 0.15);

$psit = $psi1000 * $resultadoexp;

// Step 1.3: Tensão na armadura de protensão no instante de seu estiramento

$sigmapi = $Pi / $Ap;

// Step 1.4: Perda de tensão por relaxação pura no tempo t

$deltasigmap = ($sigmapi/100) * $psit;

// Step 1.5: Carga após perda de tensão por relaxação do aço no tempo t

$P = $Pi - ($deltasigmap / $Ap);

// print setup
echo "-----------------------------------------------\n";
echo "PROCESSAMENTO DA PERDA DE CARGA Pi:\n";
echo "-----------------------------------------------\n";
echo "Perda de carga por relaxação do aço no périodo de $t dias = $deltasigmap\n";
echo "Carga após a perda por escorregamento no tempo de $t dias = $P ton\n";
echo "-----------------------------------------------\n\n";

?>