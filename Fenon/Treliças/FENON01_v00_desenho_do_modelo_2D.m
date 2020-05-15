% ######################################################################
% UNIVERSIDADE FEDERAL DE CATAL�O(UFCat)
% FACULDADE DE ENGENHARIA
% Autores:
% Wanderlei Malaquias Pereira Junior
% Danilo Cavalcanti Borges
% Sylvia Regina Mesquita de Almeida
% Colaboradores
%
%
% ######################################################################

% <<<<<>>>>>            FENON (Finite Element Non Linear Analysis)
% Vers�o:               v00
% Notas
% 01-05-20              - Vers�o inicial implementada por W M Pereira Junior e D C Borges
% <<<<<>>>>>

% <<<<<>>>>>            Descri��o            <<<<<>>>>> %
%
% Desenha a estrutura 2D idealizada
%
% <<<<<>>>>>                                 <<<<<>>>>> %

% <<<<<>>>>>            Vari�veis            <<<<<>>>>> %
%
% Escalares:
% ------------------------------------------------------------------------
% nNos                  - Quantidade de n�s da estrutura
% nElem                 - Quantidade de elementos da estrutura
% no1                   - N� 1 analisado
% no2                   - N� 2 analisado
% xNo1                  - Coordenada X do n� 1 analisado
% xNo2                  - Coordenada X do n� 2 analisado
% xNo1                  - Coordenada Y do n� 1 analisado
% xNo2                  - Coordenada Y do n� 2 analisado	
% raio                  - Raio adotado para desenhar o circulo que representa o n�
% intervaloDesenho      - Intervalo do desenho da circunfer�ncia
% ------------------------------------------------------------------------
%
% Vetores
% ------------------------------------------------------------------------
% xIntervalo            - Todas as coordenadas X para desenho da circunfer�ncia do n�
% yIntervalo            - Todas as coordenadas Y para desenho da circunfer�ncia do n�
% ------------------------------------------------------------------------
%
% Matrizes
% ------------------------------------------------------------------------
% coordenadas           - Matriz de coordenadas da treli�a              [coordenadaX coordenadaY]
% elementos             - Matriz de elementos e suas conectividades 	[No1 No2 TipoMaterial TipoSecao]
% ------------------------------------------------------------------------
%
% <<<<<>>>>>                                 <<<<<>>>>> %

function FENON01_v00_desenho_do_modelo_2D(coordenadas,elementos,nNos,nElem)
%%
%
%
%=========================================================================%
% STEP 1: DESENHO DA ESTRUTURA
%=========================================================================%
%
%
% Step 1.1: Desenho da treli�a 2D na configura��o inicial
fprintf('%s\n','Desenhando a estrutura...');
figure(1);
clf;

% Step 1.1.1: La�o para desenho das barras
for el=1:nElem
    
    % Step 1.1.1.1:  Capturando os n�s dos elementos
    no1=elementos(el,1);
    no2=elementos(el,2);
    
    % Step 1.1.1.2:  Coordenada dos n�s
    xNo1=coordenadas(no1,1);
    yNo1=coordenadas(no1,2);
    xNo2=coordenadas(no2,1);
    yNo2=coordenadas(no2,2);
    
    % Step 1.1.1.3:  Desenho das linhas
    line([xNo1 xNo2],[yNo1 yNo2],'LineWidth',1);
    
end

axis equal;
axis off;
title({'Estrutura em Estudo';'      '});

% Step 1.1.2: La�o para numera��o das barras
for el=1:nElem
    
    % Step 1.1.2.1:  Capturando os n�s dos elementos
    no1=elementos(el,1);
    no2=elementos(el,2);
    
    % Step 1.1.2.2:  Coordenada dos n�s
    xNo1=coordenadas(no1,1);
    yNo1=coordenadas(no1,2);
    xNo2=coordenadas(no2,1);
    yNo2=coordenadas(no2,2);
    
    % Step 1.1.2.3:  Desenho do n�mero dos elementos no pontos m�dio das linhas
    texto=text((xNo1+xNo2)/2,(yNo1+yNo2)/2,num2str(el));
    set(texto,'Color','black','FontSize',14)
end

% Step 1.1.3: La�o para numera��o dos n�s
for no=1:nNos
    
    % Step 1.1.3.1:  Capturando os n�s dos elementos
    Xno=coordenadas(no,1);
    Yno=coordenadas(no,2);
    
    % Step 1.1.3.1:  Desenhando o circulo do n�
    hold on
    raio=0.1;
    intervaloDesenho = 0:pi/50:2*pi;
    xIntervalo = raio * cos(intervaloDesenho) + Xno;
    yIntervalo = raio * sin(intervaloDesenho) + Yno;
    plot(xIntervalo, yIntervalo);
    hold off
    
    % Step 1.1.3.3:  Desenho do n�mero dos n�s
    texto=text(Xno+0.1,Yno+0.1,num2str(no));
    set(texto,'Color','blue','FontSize',14)
end

end

