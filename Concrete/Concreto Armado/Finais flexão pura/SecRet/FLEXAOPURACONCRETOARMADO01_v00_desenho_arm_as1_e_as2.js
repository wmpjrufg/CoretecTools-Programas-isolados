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

// Nome:    Dimensionamento de seções retangulares submetidas à flexão pura
// Versão:  FLEXAOPURACONCRETOARMADO01_v00_desenho_arm_as1
// Notas:
// 01-01-19 - N. Peixoto finalizou a versão de desenho da seção
// 01-01-20 - Prof. W. M. Pereira Junior e N. Peixoto acrescentaram a versão de desenho com correção das bolinhas por camada
//
//
//
//
//
// ----------

// ---------- Descrição do programa
//
// O programa abaixo desenha uma seção transversal retangular com a quantidade de armadura Phi longitudinal necessária para armadura simples e dupla
// Para teste utilize: https://editor.p5js.org/
//
// ----------

// ---------- Variáveis e suas descrições
//
// Escalares
//
// bw            - Largura da seção de concreto                        (cm)
// h             - Altura da seção de concreto                         (cm)
// cob           - Cobrimento das armaduras                            (cm)
// phiestribo    - Diâmetro do estribo                                 (mm)
// phi           - Bitola longitudinal escolhida                       (mm)
// d             - Altura útil da peça (0.8.h <= d <= 0.9.h)           (cm)
// dlinha =ycg   - Altura útil complementar da peça                    (cm)
// av e ah       - Distância entre barras vindas do back end de dimensionamento (cm)
// bwdisponivel  - Espaço disponível para a peça
//
// Vetores:
//
// camadaBolinha - Vetor que contém para um determinado detalhamento a quantidade barras por camada
// spacing       - Vetor que contém o espaço disponível por camada analisada
//
//
// Matrizes:
//
// 
//
//
// ----------
function setup() {
  createCanvas(400, 400);
}

function draw() {
  background(220);
}function setup() {

  createCanvas(1000, 1000);
}

function draw() {
//
//
//=========================================================================%
// STEP 1: DADOS DO FRONT END
//=========================================================================%
//
//
  // Dados do front end da seção agora para armadura simples e dupla
  let bw               = 25                //<----aqui usuário muda
  let h                = 90                //<----aqui usuário muda
  let cob              = 3                 //<----aqui usuário muda
  let phiestribo       = 6.3 / 10;         //<----aqui usuário muda
  let phias1           = 12.5/ 10;         //<----aqui usuário muda
  let phias2           = 8.00/ 10;         //<----aqui usuário muda
  let ahas1            = 2.5;              //<----aqui usuário muda
  let avas1            = 2.5;              //<----aqui usuário muda
  let ahas2            = 2.5;              //<----aqui usuário muda
  let avas2            = 2.5;              //<----aqui usuário muda
  let camadaBolinhaas1 = [4,1,4,5,1];      //<----aqui usuário muda
  let camadaBolinhaas2 = [4,3,1];          //<----aqui usuário muda
  
  // Posição inicial no Canvas
  let Beamx1 = 50;
  let Beamy1 = 50;
  
  // Configurações de desenho
  var speed = 2;
  var posX  = 0;
  let zoom  = 1.00;
  let FirstmoveX, FirstmoveY;
  const Scalepixelcm = 10;
  
  // Transformando o desenho para pixels
  bw            = bw * Scalepixelcm;
  h             = h * Scalepixelcm;
  cob           = cob * Scalepixelcm;
  phiestribo    = phiestribo * Scalepixelcm;
  phias1        = phias1 * Scalepixelcm;
  phias2        = phias2 * Scalepixelcm;
  avas1         = avas1 * Scalepixelcm;
  ahas1         = ahas1 * Scalepixelcm;
  avas2         = avas2 * Scalepixelcm;
  ahas2         = ahas2 * Scalepixelcm;
  
  // Ponto de inicio do estribo externo (esquerda superior)
  let shearSteelExternox1 = Beamx1 + cob;
  let shearSteelExternoy1 = Beamy1 + cob;

  // Ponto de inicio do estribo interno (esquerda superior)
  let shearSteelInternox1 = shearSteelExternox1 + phiestribo;
  let shearSteelInternoy1 = shearSteelExternoy1 + phiestribo;

  // Tamanho das faces de largura e altura dos estribos externos e internos
  let bwEstriboExterno = bw - 2 * cob;
  let hEstriboExterno  = h - 2 * cob;
  let bwEstriboInterno = bw - 2 * (cob + phiestribo);
  let hEstriboInterno  = h - 2 * (cob + phiestribo);
  
//
//
//=========================================================================%
// STEP 2: DESENHO DAS CAMADAS DE ARMADURA NA SEÇÃO TRANSVERSAL
//=========================================================================%
//
//  
  
  // Determinanado o espaço disponível por camada na variávei vetorial spacing
  
  // Para armadura tracionada região inferior
  // Criação da variável spacing
  let spacingas1 = [];
  
  // Preenchimento do vetor
  for (let i = 0; i < camadaBolinhaas1.length; i++ )
  { 
    bwdisponivel = bwEstriboInterno;
    
    spacingas1[i]   = (bwdisponivel-phias1) / (camadaBolinhaas1[i] - 1);
  }
  
  
  // Para armadura comprimida região superior
  // Criação da variável spacing
  let spacingas2 = [];
  
  // Preenchimento do vetor
  for (let i = 0; i < camadaBolinhaas2.length; i++ )
  { 
    bwdisponivel = bwEstriboInterno;
    
    spacingas2[i]   = (bwdisponivel-phias2) / (camadaBolinhaas2[i] - 1);
  }
  
  
  // Desenho da seção e das bolinhas
  clear();
  push();
  // Desenho da seção da viga
  colorMode(HSB);
  fill('rgb(211,211,211)');
  rect(Beamx1, Beamy1, bw, h);
  
  // Desenho do estribo
  colorMode(HSB);
  stroke('blue');
  rect(shearSteelExternox1, shearSteelExternoy1, bwEstriboExterno, hEstriboExterno);
  colorMode(HSB);
  stroke('blue');
  rect(shearSteelInternox1, shearSteelInternoy1, bwEstriboInterno, hEstriboInterno);
  
  // Desenho das bitolas em cada camada para armadura inferior tracionada
  for (let j = 0; j < camadaBolinhaas1.length ; j++) {
    for (let i = 0; i < camadaBolinhaas1[j]; i++) {
        if(camadaBolinhaas1[j] == 1)
        {
          colorMode(HSB);
          fill(131, 29, 28);
          circle(shearSteelInternox1 + (phias1/2), shearSteelInternoy1 + hEstriboInterno - (phias1/2) - j * (avas1+phias1), phias1);
        }else{
          colorMode(HSB);
          fill(131, 29, 28);
          circle(shearSteelInternox1 + (phias1/2) + (i *  spacingas1[j]), shearSteelInternoy1 + hEstriboInterno - (phias1/2) - j * (avas1+phias1), phias1);
        
        }
    }
  }
  
  // Desenho das bitolas em cada camada para armadura superior comprimida
  for (let j = 0; j < camadaBolinhaas2.length ; j++) {
    for (let i = 0; i < camadaBolinhaas2[j]; i++) {
        if(camadaBolinhaas1[j] == 1)
        {
          colorMode(HSB);
          fill(131, 29, 28);
          circle(shearSteelInternox1 + (phias2/2), shearSteelInternoy1 + (phias2/2) + j * (avas2+phias2), phias2);
        }else{
          colorMode(HSB);
          fill(131, 29, 28);
          circle(shearSteelInternox1 + (phias2/2) +(i *  spacingas2[j]), shearSteelInternoy1 + (phias2/2) + j * (avas2+phias2), phias2);
        
        }
    }
  }
  
  pop();

}