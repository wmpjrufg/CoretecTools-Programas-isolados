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

// Name:    Determinação da perda de tensão por escorregamento
// Version: PROTENSAO02_v00_perda_de_protensao_por_escorregamento_dos_cabos
// Notes:
// 
//
//
//
//
//
// ---------- Descrição do programa:
//
// O programa abaixo determina a perda de tensão devido ao escorregamento dos cabos, que ocorre devido a acomodação das cunhas na ancorragem.
//
// ----------

// ---------- Descrição das variáveis
//
// Escalares:
//
// L            - Comprimento da pista de protensão                 (mm)
// deltae       - Escoregamento / acomodação dado pelo fabricante   (mm)
// deltaL       - Alongamento do aço                                (mm)
// Pi           - Carga aplicada a protensão durante a operação     (ton)
// Ap           - Área da seção transvesal do cabo                  (mm²)
// sigmapi      - Tensão na armadura na operação de protensão       (kN/mm²)
// Ep           - Módulo de elasticidade do aço                     (kN/mm²)
// epsilonp     - Redução na deformação da armadura                 (%)
// deltasigmap  - Redução na tensão da armadura                     (%)
// deltap       - Carga perdida devido ao escorregamento dos cabos  (ton)
// P            - Carga após a perda                                (ton)
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
echo "PROJETO DE PEÇAS PROTENDIDAS - CÁLCULO DA PERDA DE TENSAO POR ESCORREGAMENTO\n";
echo "-----------------------------------------------\n\n";

// Step 1.1: Definição dos parâmetros de entrada

$L       = 150000;
$Pi      = 10;
$Ap      = 125;
$Ep      = 200;
$deltae  = 6;   

// print setup
echo "-----------------------------------------------\n";
echo "PARÂMETROS DE ENTRADA:\n";
echo "-----------------------------------------------\n";
echo "Comprimento da pista de protensão = $L mm\n";
echo "Carga de protensão                = $Pi ton\n";
echo "Área da seção transvesal do cabo  = $Ap mm\n";
echo "Módulo de elasticidade do aço     = $Ep kN/mm²\n";
echo "Escorregamento                    = $deltae mm\n";
echo "-----------------------------------------------\n\n";

// Step 1.2: Determinação da tensão inicial 

$sigmapi = (9.8067 * $Pi) / $Ap;

// Step 1.3: Determinação do pré-alongamento do cabo 

$deltaL = $L * ($sigmapi/$Ep);

// Step 1.4: Redução na deformação da armadura

$epsilonp = $deltae / ($L + $deltaL);

// Step 1.5: Redução na tensão da armadura 

$deltasigmap = $Ep * $epsilonp;

// Step 1.6: Carga após a perda de tensão por escorregamento

$deltaP = ($deltasigmap * $Ap) / 9.8067;

// Step 1.7: Carga após a perda de tensão por escorregamento

$P = $Pi - (($Ap * $deltasigmap) / 9.8067);

// print setup
echo "-----------------------------------------------\n";
echo "PROCESSAMENTO DA PERDA DE CARGA Pi:\n";
echo "-----------------------------------------------\n";
echo "Perda de carga por escorregamento = $deltaP ton\n";
echo "Carga após a perda por escorregamento = $P ton\n";
echo "-----------------------------------------------\n\n";

?>