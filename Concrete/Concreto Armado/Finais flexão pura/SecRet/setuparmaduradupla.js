function setup() {

  createCanvas(1000, 1000);
}

function draw() {
  
  
  // Dados do front end da seção
  let bw            = 25          //<----aqui usuário muda
  let h             = 90          //<----aqui usuário muda
  let cob           = 3           //<----aqui usuário muda
  let phiestribo    = 6.3 / 10;   //<----aqui usuário muda
  let phi           = 20/ 10;     //<----aqui usuário muda
  let ah            = 2.5;        //<----aqui usuário muda
  let av            = 2.5;        //<----aqui usuário muda
  let camadaBolinha = [3,2,2,1];      //<----aqui usuário muda
  
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
  

  
  let offset = phi;
  let spacing = [];
  for (let i = 0; i < camadaBolinha.length; i++ )
  { 
    bwdisponivel = bwEstriboInterno;
    //console.log(bwdisponivel);
    spacing[i]   = (bwdisponivel-phi) / (camadaBolinha[i] - 1);
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
  
  // Desenho das bitolas
  for (let j = 0; j < camadaBolinha.length ; j++) {
    for (let i = 0; i < camadaBolinha[j]; i++) {
        if(camadaBolinha[j] == 1)
        {
          colorMode(HSB);
          fill(131, 29, 28);
          circle(shearSteelInternox1 + (offset/2), shearSteelInternoy1 + (offset/2) + j * (av+offset), phi);
        }else{
          colorMode(HSB);
          fill(131, 29, 28);
          circle(shearSteelInternox1 + (offset/2) +(i *  spacing[j]), shearSteelInternoy1 + (offset/2) + j * (av+offset), phi);
        
        }
    }
  }
  pop();

}