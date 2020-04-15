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
// deltaP  - Perda percentual por acomodação da ancoragem      (%)
// deltae  - Escoregamento / acomodação                        (mm)
// deltaL  - Alongamento do aço                                (mm)
// 
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

$deltae      = 5;   
$deltaL      = 500;

// Step 1.2: Determinação do percentual de perda da protenção

deltaP = (deltae/deltaL)*100

// Step 1.2: Determinação da força no cabo considerando a perda por escorregamento


