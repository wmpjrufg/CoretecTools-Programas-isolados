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
// Versão:  FLEXAOPURACONCRETOARMADO01_v00_desenho
// Notas:
// 01-01-19 - N A Peixoto Neto finalizou a versão beta
//
//
//
//
//
// ----------

// ---------- Descrição do programa
//
// O programa abaixo determina a quantidade armadura necessária para seções retangulares de concreto armado submetidas à momento fletor
//
// ----------

// ---------- Variáveis e suas descrições
//
// Escalares
//
// cHeight - Altura do canvas em pixel
// cWidth  - Largura do canvas em pixels
// Scalepixelcm  - Escala de pixel para centímetros 
// 
//
//
//
//
// ----------

//
//
//=========================================================================%
// STEP 1: CRIAÇÃO DO CANVAS
//=========================================================================%
//
//
// Step 1.1: Setup para criação de um Canvas na tela do Coretec Concrete
                let cHeight     = 500;
                let cWidth      = 500;
                let zoom        = 1.00;
                let sensativity = 0.001;
                let moveX, moveY;
                let Beamx1      = 50;
                let Beamy1      = 50;

// Step 1.2: Inicialização da biblioteca de desenho (biblioteca p5)
                const script = function (p5) {
                    var speed = 2;
                    var posX = 0;

// Step 1.2: Setup da biblioteca p5
                    p5.setup = _ => {
                        var canvas = p5.createCanvas(cWidth, cHeight)
                        canvas.parent("canvasDad");
                        p5.cursor('grab');
                        moveX = 0;
                        moveY = 0;
                        p5.translate(cWidth / 2, cHeight / 2);
                    }
//
//
//=========================================================================%
// STEP 2: CRIAÇÃO DO DESENHO DA VIGA
//=========================================================================%
//
//
// Step 2.1: Leitura dos dados do formulário do front end
                    p5.draw = _ => {

                    let width      = document.getElementById('bw').value;
                    let height     = document.getElementById('h').value;
                    let cobrimento = document.getElementById('cob').value;
                    let phiEstribo = document.getElementById('phiestribo').value / 10;
                    let phiBitolaL = document.getElementById('phi').value / 10;          //    = (6.3/10)*Scalepixelcm; NEWTON OLHAR
                    let numCamadas               = 3;
                    let numBitolasPorCamada      = 3;


// Step 2.2: Transformando as entradas de centímetros para pixel             
                    const Scalepixelcm = 37;
                    width      = width*Scalepixelcm;
                    height     = height*Scalepixelcm;
                    cobrimento = cobrimento*Scalepixelcm;
                    phiEstribo = phiEstribo*Scalepixelcm;

// Step 2.3: Definindo o tamanho dos retângulos do desenho

// Step 2.3.1: Retângulo externo 
                    let shearSteelExternox1 = Beamx1+cobrimento;
                    let shearSteelExternoy1 = Beamy1+cobrimento;

// Step 2.3.1: Retângulo dos estribos
                    let shearSteelInternox1 = shearSteelExternox1+phiEstribo;
                    let shearSteelInternoy1 = shearSteelExternoy1+phiEstribo;
                    let lEstriboInterno = width-2*(cobrimento+phiEstribo);
                    let hEstriboInterno = height-2*(cobrimento+phiEstribo);
                    let lEstriboExterno = width-2*cobrimento;
                    let hEstriboExterno = height-2*cobrimento;

// Step 2.4: Cálculo do espaçamento (offset) entre as bitolas            
                    let numBitolasResto          = numBitolasPorCamada - 2;
                    let numEspacosCamada         = numBitolasResto + 1;
                    let espacamentoHorizontalBit = 2.5*Scalepixelcm;
                    let espacamentoVerticalBit   = 2.5*Scalepixelcm;
                    let offset                   = phiEstribo;
                    let spacing                  = ((( (lEstriboInterno)   ) - 2 * offset ) / (numBitolasPorCamada - 1));

// Step 2.5: Criação do desenho e seus efeitos

// Step 2.5.1: Zoom e pan
                    p5.translate(moveX, moveY);
                    p5.scale(zoom);
                    p5.clear();
                    p5.push();

// Step 2.5.1: Cores
                    p5.colorMode(p5.HSB);
                    p5.fill('rgb(128,128,128)');
                    p5.rect(Beamx1, Beamy1, width, height);
                    p5.colorMode(p5.HSB);
                    p5.stroke('red');

// Step 2.5.1: Desenho do retângulo
                    p5.rect(shearSteelExternox1, shearSteelExternoy1, lEstriboExterno, hEstriboExterno);
                    p5.colorMode(p5.HSB);
                    p5.stroke('red');
                    p5.rect(shearSteelInternox1, shearSteelInternoy1, lEstriboInterno, hEstriboInterno);

// Step 2.5.1: Desenho das bitolas
                    for(let j=0; j < numCamadas ;j++)
                    {
                        for (let i = 0; i < numBitolasPorCamada; i++)
                        {
                        p5.colorMode(p5.HSB);
                        p5.fill(348, 94, 95);
                        p5.circle(shearSteelInternox1+(i * spacing) + offset, shearSteelInternoy1+hEstriboInterno-offset-j* espacamentoVerticalBit, phiBitolaL);
                        }
                    }
                    p5.pop();
                    }

// Step 2.6: Efeitos do desenho

// Step 2.6.1: Download png
                    p5.mouseClicked = (event) => {
                        if($("#download:hover").length > 0)
                        {
                            p5.saveCanvas('EstriboImage', 'png');
                        }
                    }

// Step 2.6.2: Efeito do zoom
                    p5.mouseWheel = (event) => {
                        if( $("#defaultCanvas1:hover").length > 0 )
                        {
                            zoom += sensativity * event.delta;
                            zoom = p5.constrain(zoom, 0.05, 9);
                            return false;
                        }
                    }

// Step 2.6.3: Efeito de pan (arrastar)
                    p5.mouseDragged = (event) => {
                        moveX = p5.mouseX;
                        moveY = p5.mouseY;
                    }
                }
                

                const secondDraw = function (p) {
                    
                    p.setup = _ => {
                        var canvas = p.createCanvas(500, 500)
                        canvas.parent("canvasDad2");
                        p.cursor('grab');
                        moveX = 0;
                        moveY = 0;
                        p.translate(cWidth / 2, cHeight / 2);
                    }

                     p.draw = _ => {
                        p.translate(moveX, moveY);
                        p.scale(zoom);
                        let aux = 2;
                        let fatorLargura = 4;
                        let height = 100 * aux;
                        let width = height*fatorLargura;
                        let divWidth = width / 13.5;
                        let origin   = 50;
    
                        p.clear();
                        p.push();
                        p.rect(origin, origin, width, height);
                        //0 de deformação

                        //linha azul onde fica a ''parede'' do rect
                        p.colorMode(p.HSB); 
                        p.strokeWeight(2)
                        p.stroke(255, 204, 100);
                        p.line(origin,origin,origin,height+origin);

                        //Linha vertical na ''parede do rect''
                        p.strokeWeight(1); 
                        p.stroke(51);
                        p.line(origin+divWidth*10, origin,origin+divWidth*10, origin+height+height*0.2);
                        //2% de deformação
                        p.stroke(255, 204, 100);
                        p.line(origin+divWidth*12, origin,origin+divWidth*12, 50+height );
                        // face externa do gráfico
                        p.stroke(51);
                        p.line(origin, origin + height + height*0.2, origin+width,origin + height + height*0.2);
                        //reta dominio 1
                        p.stroke(255, 204, 100);
                        p.line(origin, origin+height, 50+divWidth*10,origin)
                        //reta dominio 2
                        p.stroke(255, 204, 100);
                        p.line(origin, origin+height, origin+divWidth*13.5,origin)
                        //reta dominio 3
                        p.stroke(255, 204, 100);
                        p.line(50+divWidth*7.5, origin+height, 50+divWidth*13.5,origin)
                        // reta dominio 4
                        p.stroke(255, 204, 100);
                        p.line(50+divWidth*10, origin + height + height*0.2, origin+divWidth*13.5,origin )
                        //reta 4A * tracejada
                        p.stroke(255, 204, 100);
                        p.strokeWeight(2); 
                        p.drawingContext.setLineDash([5, 15]);
                        p.line(50+divWidth*10, origin + height, origin+divWidth*13.5,origin )
                        p.pop();
                     }

                    p.mouseClicked = (event) => {
                        if($("#download:hover").length > 0)
                        {
                            p.saveCanvas('SecondDraw', 'png');
                        }
                    }

                     p.mouseWheel = (event) => {
                        if( $("#defaultCanvas0:hover").length > 0 )
                        {
                            zoom += sensativity * event.delta;
                            zoom = p.constrain(zoom, 0.05, 9);
                            return false;
                        }
                    }

                    p.mouseDragged = (event) => {
                        moveX = p.mouseX;
                        moveY = p.mouseY;
                    }

                }

                const P5 = require('p5');
                const SecondDraw = require('p5');
                new SecondDraw(secondDraw);
               new P5(script)
        }