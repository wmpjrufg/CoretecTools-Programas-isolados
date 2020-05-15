% ######################################################################
% UNIVERSIDADE FEDERAL DE CATALÃO(UFCat)
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
% Versão:               v00
% Notas
% 01-05-20              - Versão inicial implementada por W M Pereira Junior e D C Borges
% <<<<<>>>>>

% <<<<<>>>>>            Descrição            <<<<<>>>>> %
%
% Desenha a estrutura 2D idealizada
%
% <<<<<>>>>>                                 <<<<<>>>>> %

% <<<<<>>>>>            Variáveis            <<<<<>>>>> %
%
% Escalares:
% ------------------------------------------------------------------------
% nNos                  - Quantidade de nós da estrutura
% nElem                 - Quantidade de elementos da estrutura
% no1                   - Nó 1 analisado
% no2                   - Nó 2 analisado
% xNo1                  - Coordenada X do nó 1 analisado
% xNo2                  - Coordenada X do nó 2 analisado
% xNo1                  - Coordenada Y do nó 1 analisado
% xNo2                  - Coordenada Y do nó 2 analisado	
% raio                  - Raio adotado para desenhar o circulo que representa o nó
% intervaloDesenho      - Intervalo do desenho da circunferência
% ------------------------------------------------------------------------
%
% Vetores
% ------------------------------------------------------------------------
% xIntervalo            - Todas as coordenadas X para desenho da circunferência do nó
% yIntervalo            - Todas as coordenadas Y para desenho da circunferência do nó
% ------------------------------------------------------------------------
%
% Matrizes
% ------------------------------------------------------------------------
% coordenadas           - Matriz de coordenadas da treliça              [coordenadaX coordenadaY]
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
% Step 1.1: Desenho da treliça 2D na configuração inicial
fprintf('%s\n','Desenhando a estrutura...');
figure(1);
clf;

% Step 1.1.1: Laço para desenho das barras
for el=1:nElem
    
    % Step 1.1.1.1:  Capturando os nós dos elementos
    no1=elementos(el,1);
    no2=elementos(el,2);
    
    % Step 1.1.1.2:  Coordenada dos nós
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

% Step 1.1.2: Laço para numeração das barras
for el=1:nElem
    
    % Step 1.1.2.1:  Capturando os nós dos elementos
    no1=elementos(el,1);
    no2=elementos(el,2);
    
    % Step 1.1.2.2:  Coordenada dos nós
    xNo1=coordenadas(no1,1);
    yNo1=coordenadas(no1,2);
    xNo2=coordenadas(no2,1);
    yNo2=coordenadas(no2,2);
    
    % Step 1.1.2.3:  Desenho do número dos elementos no pontos médio das linhas
    texto=text((xNo1+xNo2)/2,(yNo1+yNo2)/2,num2str(el));
    set(texto,'Color','black','FontSize',14)
end

% Step 1.1.3: Laço para numeração dos nós
for no=1:nNos
    
    % Step 1.1.3.1:  Capturando os nós dos elementos
    Xno=coordenadas(no,1);
    Yno=coordenadas(no,2);
    
    % Step 1.1.3.1:  Desenhando o circulo do nó
    hold on
    raio=0.1;
    intervaloDesenho = 0:pi/50:2*pi;
    xIntervalo = raio * cos(intervaloDesenho) + Xno;
    yIntervalo = raio * sin(intervaloDesenho) + Yno;
    plot(xIntervalo, yIntervalo);
    hold off
    
    % Step 1.1.3.3:  Desenho do número dos nós
    texto=text(Xno+0.1,Yno+0.1,num2str(no));
    set(texto,'Color','blue','FontSize',14)
end

end

