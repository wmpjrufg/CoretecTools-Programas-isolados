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

// Nome:    Faz o desenho de uma seção de concreto com os dados informados no dimensionamento de seções retangulares submetidas à flexão pura
// Versão:  FLEXAOPURACONCRETOARMADO01_v00_desenhoSecao
// Notas:
// 01-01-19 - N A Peixoto Neto finalizou a versão beta
//
// ----------

// ---------- Descrição do programa
//
// O programa abaixo determina a quantidade armadura necessária para seções retangulares de concreto armado submetidas à momento fletor
//
// ----------
//
//
const script = function (p5) {
    let FirstmoveX, FirstmoveY;  // <---- Utilizada para reposicionar o desenho no evento de arrastar
    let zoom   = 1.00; // <----Váriavel para definir o zoom utilizado no evento de rolagem
    let sensativity = 0.001; // <----Váriavel para definir a sensibilidade utilizada no evento de rolagem

//=========================================================================%
// STEP 1: CRIAÇÃO DO CANVAS
//=========================================================================%
//
   p5.setup = _ => {
      var canvas = p5.createCanvas(cWidth, cHeight)
      canvas.parent("canvasDad");
      p5.cursor('grab');
      FirstmoveX = 0;
      FirstmoveY = 0;
      p5.translate(cWidth / 2, cHeight / 2);
    }
//=========================================================================%
// STEP 2: MÉTODO RESPONSÁVEL POR DESENHAR 
//=========================================================================%
    p5.draw = _ => {
        // Dados do front end da seção
        let bw            = 25          //largura <----aqui usuário muda
        let h             = 90          //altura <----aqui usuário muda
        let cob           = 3           //cobrimento <----aqui usuário muda
        let phiestribo    = 6.3 / 10;   //<----aqui usuário muda
        let phi           = 12.5/ 10;     //<----aqui usuário muda
        let ah            = 2.5;        //<----aqui usuário muda
        let av            = 2.5;        //<----aqui usuário muda
        let camadaBolinha = [3,2,2,5,1]; /* <----aqui usuário muda  
                                        dado do back-end posição 0 = camada 0 
                                         e seu valor é a quantidade de bitolas por camada
                                         */
        
        // Posição inicial no Canvas
        let Beamx1 = 50;
        let Beamy1 = 50;
        
        // Escala do desenho 
        const Scalepixelcm = 10;
        
        // Transformando o desenho para pixels
        bw         = bw * Scalepixelcm;
        h          = h * Scalepixelcm;
        cob        = cob * Scalepixelcm;
        phiestribo = phiestribo * Scalepixelcm;
        phi        = phi * Scalepixelcm;
        av         = av * Scalepixelcm;
        ah         = ah * Scalepixelcm;
        
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
        
        // definindo o espaçamento entre as bitolas de cada camada
        let offset = phi;
        let spacing = [];
        let bwdisponivel = 0;
        for (let i = 0; i < camadaBolinha.length; i++ )
        { 
          bwdisponivel = bwEstriboInterno;
          //console.log(bwdisponivel);
          spacing[i]   = (bwdisponivel-phi) / (camadaBolinha[i] - 1);
        }
        
        
        // dar o zoom e movimentar o desenho para as novas posições
        p5.translate(FirstmoveX, FirstmoveY);
        p5.scale(zoom);
        p5.clear();
        p5.push();
        // Desenho da seção da viga
        p5.colorMode(p5.HSB);
        p5.fill('rgb(211,211,211)');
        p5.rect(Beamx1, Beamy1, bw, h);
        
        // Desenho do estribo
        p5.colorMode(p5.HSB);
        p5.stroke('blue');
        p5.rect(shearSteelExternox1, shearSteelExternoy1, bwEstriboExterno, hEstriboExterno);
        p5.colorMode(p5.HSB);
        p5.stroke('blue');
        p5.rect(shearSteelInternox1, shearSteelInternoy1, bwEstriboInterno, hEstriboInterno);
        
        // Desenho das bitolas
        for (let j = 0; j < camadaBolinha.length ; j++) {
          for (let i = 0; i < camadaBolinha[j]; i++) {
              if(camadaBolinha[j] == 1)
              {
                p5.colorMode(p5.HSB);
                p5.fill(131, 29, 28);
                p5.circle(shearSteelInternox1 + (offset/2), shearSteelInternoy1 + hEstriboInterno - (offset/2) - j * (av+offset), phi);
              }else{
                p5.colorMode(p5.HSB);
                p5.fill(131, 29, 28);
                p5.circle(shearSteelInternox1 + (offset/2) + (i *  spacing[j]), shearSteelInternoy1 + hEstriboInterno - (offset/2) - j * (av+offset), phi);
              
              }
          }
        }
        p5.pop();
      }
//=========================================================================%
// STEP 3: AO CLICAR REALIZA O DOWNLOAD DO ARQUIVO EM PNG
//=========================================================================%
      p5.mouseClicked = (event) => {
          if($("#download:hover").length > 0)
          {
              p5.saveCanvas('EstriboImage', 'png');
          }
      }
//=========================================================================%
// STEP 4: MÉTODO RESPONSÁVEL PELO EVENTO DE ROLAGEM O QUE CAUSA O ZOOM
//=========================================================================%
      p5.mouseWheel = (event) => {
          if( $("#defaultCanvas0:hover").length > 0 )
          {
              zoom += sensativity * event.delta;
              zoom = p5.constrain(zoom, 0.05, 9);
              return false;
          }
      }

//=========================================================================%
// STEP 5: MÉTODO RESPONSÁVEL PELO EVENTO DE ARRASTAR O  DESENHO 
//=========================================================================%
      p5.mouseDragged = (event) => {
          if( $("#defaultCanvas0:hover").length > 0 )
          {
            FirstmoveX = p5.mouseX;
            FirstmoveY = p5.mouseY;
          }
      }
    }
    const P5 = require('p5');
    new P5(script)
    