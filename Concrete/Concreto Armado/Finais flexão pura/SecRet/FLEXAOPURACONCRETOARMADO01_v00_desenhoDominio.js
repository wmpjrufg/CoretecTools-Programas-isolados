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

// Nome:   Realiza o desenho dos domínios
// Versão:  FLEXAOPURACONCRETOARMADO01_v00_desenhoDominio
// Notas:
// 01-01-19 - N A Peixoto Neto finalizou a versão beta
//
// ----------

// ---------- Descrição do programa
//
// O programa abaixo realiza o desenho dos domínios
//
// ----------
//
//
const secondDraw = function (p) {
    let zoom  = 1.00; // <----Váriavel para definir o zoom utilizado no evento de rolagem
    let sensativity = 0.001; // <----Váriavel para definir a sensibilidade utilizada no evento de rolagem
    let SecondmoveX, SecondmoveY; // <---- Utilizada para reposicionar o desenho no evento de arrastar
//=========================================================================%
// STEP 1: CRIAÇÃO DO CANVAS
//=========================================================================%
//
    p.setup = _ => {
        var canvasDiv = document.getElementById('canvasW2');
        var cLargura = canvasDiv.offsetWidth;
        var canvas = p.createCanvas(1000, 500)
        canvas.parent("canvasDad2");
        p.cursor('grab');
        SecondmoveX = 0;
        SecondmoveY = 0;
        p.translate(cLargura / 2, 500 / 2);
    }
//=========================================================================%
// STEP 2: MÉTODO RESPONSÁVEL POR DESENHAR 
//=========================================================================%
      p.draw = _ => {
        p.translate(SecondmoveX, SecondmoveY);
        p.scale(zoom);
        let aux = 2; //<----Escala utilizada
        let fatorLargura = 4;
        let height = 100 * aux;
        let width = height*fatorLargura; 
        let divWidth = width / 13.5; //<----Dividindo a largura total em 13.5 pedaços
        let origin   = 50; //<----Ponto inicial do desenho no canvas

        // criação do retângulo
        p.clear();
        p.push();
        p.rect(origin, origin, width, height);

        //Linha azul na parte mais à esquerda do retângulo
        p.colorMode(p.HSB); 
        p.strokeWeight(2)
        p.stroke(255, 204, 100);
        p.line(origin,origin,origin,height+origin);

        //Linha azul na parte mais à esquerda do retângulo
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
//=========================================================================%
// STEP 3: AO CLICAR REALIZA O DOWNLOAD DO ARQUIVO EM PNG
//=========================================================================%
    p.mouseClicked = (event) => {
        if($("#download:hover").length > 0)
        {
            p.saveCanvas('SecondDraw', 'png');
        }
    }
//=========================================================================%
// STEP 4: MÉTODO RESPONSÁVEL PELO EVENTO DE ROLAGEM O QUE CAUSA O ZOOM
//=========================================================================%
      p.mouseWheel = (event) => {
        if( $("#defaultCanvas2:hover").length > 0 )
        {
            zoom += sensativity * event.delta;
            zoom = p.constrain(zoom, 0.05, 9);
            return false;
        }
    }
//=========================================================================%
// STEP 5: MÉTODO RESPONSÁVEL PELO EVENTO DE ARRASTAR O  DESENHO 
//=========================================================================%
    p.mouseDragged = (event) => {
        if( $("#defaultCanvas2:hover").length > 0 )
        {
          SecondmoveX = p.mouseX;
          SecondmoveY = p.mouseY;
        }
    }

}
const SecondDraw = require('p5');
new SecondDraw(secondDraw);