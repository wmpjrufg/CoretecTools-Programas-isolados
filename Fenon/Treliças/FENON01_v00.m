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
% Realiza a análise estrutural de uma treliça 2D utilizando o Método dos Elementos Finitos
%
% <<<<<>>>>>      

% <<<<<>>>>>            Variáveis            <<<<<>>>>> %
%
% Escalares:
% ------------------------------------------------------------------------
% nNos                  - Quantidade de nós da estrutura
% sz                  	- Retorna a dimensão (linhas) da matriz
% nElem                 - Quantidade de elementos da estrutura
% nMat                  - Quantidade de materiais da estrutura
% nSec                  - Quantidade de seções transversais
% nApo                 	- Quantidade de nós com algum tipo de apoio externo
% nCargNo               - Quantidade de cargas nodais externas
% nRecalNo              - Quantidade de recalques nodais impostos
% el                    - Contador interno do elemento analisado de um laço
% tipoElemento          - Tipo de elemento finito utilizado na análise estrutural                       (Pode ser BAR1D ; TRUSS2D1 ; BEAM2D ; PORTICO2D)
% nGrauLiberdadePorNo   - Total de graus de liberdade por nó
% nNosPorElemento       - Total de graus de liberdade por elemento
% tipoMaterial          - Modelo do comportamento do material                                           (Pode ser NLEP1)
% tipoDePrescricao      - Tipo de prescrição adotada na análise. Podendo ser DESLOCAMENTO ou FORCA
% nPassos               - Número passos ou divisões de uma determinada prescrição. Processo incremental
% tolerancia            - Critério de parada para o processo iterativo
% alphaPasso            - Passo da busca no processo iterativo para X(t+1) = X(t) + alpha.Sk(t)
% nGrauLibTotal         - Número de graus de liberdade totais da estrutura
% nGrauLibRestrito      - Número de graus de liberdade restritos da estrutura
% nGrauLibLivre         - Número de graus de liberdade livres da estrutura
% passoPres             - Contador da quantidade de passos prescritos no processo incremental
% nIter               	- Número de iterações para determinação do equilibrio da estrutura Fext=Fint por exemplo
% ------------------------------------------------------------------------
%
% Vetores
% ------------------------------------------------------------------------
% nosTrajetoriaEqu      - Vetor que informa os nós onde se deseja visualizar a trajetória de equilibrio
% area                  - Vetor que armazena a área dos elementos da estrutura
% comprimento           - Vetor que armazena o comprimento dos elementos da estrutura
% cossenoDiretor        - Vetor que armazena o cos diretor dos elementos da estrutura
% senoDiretor           - Vetor que armazena o sin diretor dos elementos da estrutura
% grauLiberdadeTotal    - Vetor Grau de Liberdade Total da estrutura
% grauLiberdadeRestrito - Vetor Grau de Liberdade Restrito da estrutura
% grauLiberdadeLivre    - Vetor Grau de Liberdade Livre da estrutura
% forcaExterna          - Vetor de força nodal completo da estrutura
% recalExterna          - Vetor de recalque nodal completo da estrutura
% deltaForca            - Vetor de força nodal incremental completo	
% deltaRecal            - Vetor de recalque nodal incremental completo
% deslocAtual           - Vetor de deslocamento atualizado do passo incremental
% ------------------------------------------------------------------------
%
% Matrizes
% ------------------------------------------------------------------------
% coordenadas           - Matriz de coordenadas da treliça              [coordenadaX coordenadaY]
% elementos             - Matriz de elementos e suas conectividades 	[No1 No2 TipoMaterial TipoSecao]
% materiais             - Matriz de materiais que compõem a estrutura 	[ModuloE Poisson TensaoEscoamentoTrac DefEscoamentoTrac  TensaoEscoamentoComp DefEscoamentoComp]
% secoes                - Matriz de seções transversais dos elementos  	[AreaDaSecao]
% apoios                - Matriz de nós com apoios                      [No RestricaoEmX RestricaoEmY]
% cargasNodais          - Matriz de cargas nodais                       [No ForcaEmX ForcaEmY]
% recalqNodais          - Matriz de deslocamento nodais impostos        [No RecalqueEmX RecalqueEmY] Obs.: Em caso de controle de força (tipoDePrescricao = 'FORCA') utilizar recalqNodais = [0 0 0];
% ------------------------------------------------------------------------
%
% <<<<<>>>>>

function FENON01_v00(config)
%%
%
%
%=========================================================================%
% STEP 1: DADOS INICIAIS DO SISTEMA ESTRUTURAL VIA VARIÁVEL CONFIG
%=========================================================================%
%
%
% Step 1.1: Atribuição dos dados da entrada principal
coordenadas             = config.coordenadas;
nNos                    = config.nNos;
elementos               = config.elementos;
nElem                   = config.nElem;
materiais               = config.materiais;
nMat                    = config.nMat;
secoes                  = config.secoes;
nSec                    = config.nSec;
apoios                  = config.apoios ;
nApo                    = config.nApo;
cargasNodais            = config.cargasNodais;
nCargNo                 = config.nCargNo;
recalqNodais            = config.recalqNodais;
nRecalNo                = config.nRecalNo;
nPres                   = config.nPres;
prescricoes             = config.prescricoes;
tipoElemento            = config.tipoElemento;
nGrauLiberdadePorNo     = config.nGrauLiberdadePorNo;
nNosPorElemento         = config.nNosPorElemento;
tipoMaterial            = config.tipoMaterial; 
tipoDePrescricao        = config.tipoDePrescricao;
nPassos                 = config.nPassos;
tolerancia              = config.tolerancia;
iter                    = config.iter;
nosTrajetoriaEqu        = config.nosTrajetoriaEqu; 
nPlotagens              = config.nPlotagens; 

% Step 1.2: Determinação das propriedades dos elementos do sistema estrutural

% Step 1.2.1: Inicializando variáveis
area            = zeros(1,nElem);
comprimento     = zeros(1,nElem);
cossenoDiretor  = zeros(1,nElem);
senoDiretor     = zeros(1,nElem);

% Step 1.2.2: Detemrinação das propriedades dos elementos da malha de MEF
for el=1:nElem
    
    [area(el),comprimento(el),cossenoDiretor(el),senoDiretor(el)]=FENON01_v00_prop_elementos(coordenadas,elementos,secoes,el);
    
end
%%
%
%
%=========================================================================%
% STEP 2: DETERMINAÇÃO DOS GRAUS DE LIBERDADE DA ESTRUTURA E CRIAÇÃO DO
%         VETOR DE HISTÓRICO DAS VARIÁVEIS
%=========================================================================%
%
%
% Step 2.1: Determinação dos tipos de graus de liberdade da estrutura
[grauLiberdadeTotal,nGrauLibTotal,grauLiberdadeRestrito,nGrauLibRestrito,grauLiberdadeLivre,nGrauLibLivre]=FENON01_v00_grau_de_liberdade_estrutura(nNos,nGrauLiberdadePorNo,nPres,prescricoes);

% Etapa 2.2: Criação dos vetore de histórico das variáveis
historicoUnod = zeros (nGrauLibTotal,nPassos);
historicoFext = zeros (nGrauLibTotal,nPassos);
historicoFint = zeros (nGrauLibTotal,nPassos);
historicoTens = zeros (nElem,nPassos);
%%
%
%
%=========================================================================%
% STEP 3: FASE DE MONTAGEM DO VETOR DE PRESCRIÇÃO PARA PROCESSO ITERATIVO
%=========================================================================%
%
%
% Step 3.1: Monta o vetor de carregamentos nodais ou deslocamentos nodais de acordo com o tipo de pescrição adotada
if      strcmp(tipoDePrescricao,'FORCA')
    
    % Step 3.1.1: Monta o vetor de forças externas para a estrutura de acordo com o passo condicionado
    [forcaExterna]  = FENON01_v00_carga_dos_nos(nCargNo,cargasNodais,nGrauLiberdadePorNo,nNos);
    deltaForca      = forcaExterna/nPassos;
    
elseif  strcmp(tipoDePrescricao,'DESLOCAMENTO')
    
    % Step 3.1.2: Monta o vetor de forças externas para a estrutura de acordo com o passo condicionado
    [recalExterna]  = FENON01_v00_carga_dos_nos(nRecalNo,recalqNodais,nGrauLiberdadePorNo,nNos);
    deltaRecal      = recalExterna/nPassos;

elseif  strcmp(tipoDePrescricao,'ARC1')
    
% IMPLEMENTAR IMPLEMENTAR
% IMPLEMENTAR IMPLEMENTAR

end

fprintf('%s\n','Processando a estrutura...');
%%
%
%
%=========================================================================%
% STEP 4: FASE DE PROCESSAMENTO DA ESTRUTURA
%=========================================================================%
%
%
% Step 4.1: Cria o vetor de deslocamentos nodais e prescrições nodais
if      strcmp(tipoDePrescricao,'FORCA')
    
    % Step 4.1.1: Monta o vetor deslocamentos nodais para primeira rodada de carregamento para controle de força
    deslocNovo = zeros(nGrauLibTotal,1);
    
elseif  strcmp(tipoDePrescricao,'DESLOCAMENTO')
    
    % Step 4.1.2: Monta o vetor deslocamentos nodais para primeira rodada de carregamento para controle de deslocamentos
    deslocNovo = deltaRecal;
    
elseif  strcmp(tipoDePrescricao,'ARC1')
    
    % IMPLEMENTAR IMPLEMENTAR
    % IMPLEMENTAR IMPLEMENTAR
    
end

% Step 4.2: Início do processo incremental (rodada de carregamento da estrutura)

% Step 4.2.1: Inicializa o vetor de deslocamentos nodais e forças externas nodais
deslocAtual     = zeros(nGrauLibTotal,1);
forcaExtAtual   = zeros(nGrauLibTotal,1);

% Step 4.2.2: Processo incremental
for passoPres=1:nPassos
    
    % Step 4.2.2.1: Inicializando o vetor de deslocamentos e forças externas do passo atual
    if      strcmp(tipoDePrescricao,'FORCA')
        
        deslocAtual     = deslocNovo;
        forcaExtAtual   = forcaExtAtual + deltaForca;
        
    elseif  strcmp(tipoDePrescricao,'DESLOCAMENTO')
        
        deslocAtual     = deslocNovo+deltaRecal;
        
    elseif  strcmp(tipoDePrescricao,'ARC1')
        
    % IMPLEMENTAR IMPLEMENTAR
    % IMPLEMENTAR IMPLEMENTAR
        
    end
    deslocAtual
    forcaExtAtual
    
    
    % Step 4.2.2.3: Montagem do vetor de deformações dos elementos
    [deformacoes]       = FENON01_v00_deformacoes_barras(nElem,deslocAtual,elementos,nGrauLiberdadePorNo,nNosPorElemento,comprimento,cossenoDiretor,senoDiretor,tipoElemento);
    deformacoes
    
    % Step 4.2.2.4: Atualização do vetor de tensões internas nos elementos e módulo de elasticidade de acordo com a curva de referência do marterial
    [tensoes,modsE]     = FENON01_v00_modelo_material(materiais,nElem,deformacoes);
    tensoes
    modsE
    
    % Step 4.2.2.5: Atualização do vetor de forças internas dos elementos
    [forcasInternas]    = FENON01_v00_forcas_internas(nGrauLibTotal,nElem,tensoes,area,comprimento,cossenoDiretor,senoDiretor,elementos,nGrauLiberdadePorNo,nNosPorElemento);
    forcasInternas
    
    residuoForcas       = forcaExtAtual - forcasInternas
    
    conv                = norm(residuoForcas)/(1+norm(forcaExtAtual))
         
    % Newton-Raphson ou qualquer processo númerico para equilibro Fext=Fint
    while conv > tolerancia && iter < 100
        
        rigidezEstrut = zeros(nGrauLibTotal,nGrauLibTotal);
        for el=1:nElem
            % Step 4.2.2.5: Montagem da matriz de rigidez da estrutura
            [rigidezEstrut]=RigidezEstrutura(coordenadas,modsE(el),secoes,elementos,el,2,rigidezEstrut);
        end
    
    rigidezEstrut(grauLiberdadeLivre,grauLiberdadeLivre)
    rigidezEstrut
    
    deltaDesloc(grauLiberdadeLivre,1) = inv(rigidezEstrut(grauLiberdadeLivre,grauLiberdadeLivre))*residuoForcas(grauLiberdadeLivre,1)
    
    deslocIncremental = deslocAtual + deltaDesloc
    
    % Step 4.2.2.3: Montagem do vetor de deformações dos elementos
    [deformacoes]       = FENON01_v00_deformacoes_barras(nElem,deslocIncremental,elementos,nGrauLiberdadePorNo,nNosPorElemento,comprimento,cossenoDiretor,senoDiretor,tipoElemento);
    deformacoes
    
    % Step 4.2.2.4: Atualização do vetor de tensões internas nos elementos e módulo de elasticidade de acordo com a curva de referência do marterial
    [tensoes,modsE]     = FENON01_v00_modelo_material(materiais,nElem,deformacoes);
    tensoes
    modsE
    
    % Step 4.2.2.5: Atualização do vetor de forças internas dos elementos
    [forcasInternas]    = FENON01_v00_forcas_internas(nGrauLibTotal,nElem,tensoes,area,comprimento,cossenoDiretor,senoDiretor,elementos,nGrauLiberdadePorNo,nNosPorElemento);
    forcasInternas
    
    residuoForcas       = forcaExtAtual - forcasInternas
    
    conv                = norm(residuoForcas(grauLiberdadeLivre,1))/(1+norm(forcaExtAtual(grauLiberdadeLivre,1)))
    
    deslocAtual = deslocIncremental
        
    iter = iter +1
    end
    %%
    %
    %
    %=========================================================================%
    % STEP 5: ETAPA DE ARMAZENAMENTO DOS RESULTADOS DO PASSO
    %=========================================================================%
    %
    %
    % Step 5.1: Histórico das variáveis
    historicoUnod(:,passoPres) = deslocAtual(:,1);
    historicoFext(:,passoPres) = forcaExtAtual(:,1);
    historicoFint(:,passoPres) = forcasInternas(:,1);
    historicoTens(:,passoPres) = tensoes(:,1);
    
    
end
historicoUnod
historicoFext
historicoFint

%%
%
%
%=========================================================================%
% STEP 6: PLOTAGEM DAS TRAJETÓRIAS DE EQUILÍBRIO
%=========================================================================%
%
%
% Step 6.1: Plotagem das trajetórias de equilíbrio por grau de liberdade
for i=1:nPlotagens
    
    figure(i+1);
    xlabel('deslocamento (m)')
    ylabel('Carga (N)')
    grauAnalisado = nosTrajetoriaEqu(i);
    
    hold on
    title(['Trajetória de Equilíbrio grau de liberdade ',num2str(grauAnalisado)])
    plotDirecaoX=historicoUnod(grauAnalisado,:);
    plotDirecaoY=historicoFint(grauAnalisado,:);
    plot(plotDirecaoX,plotDirecaoY,'b--o');
    
    hold on
    plot(0,0,'-mo',...
    'LineWidth',2,...
    'MarkerEdgeColor','k',...
    'MarkerFaceColor',[.49 1 .63],...
    'MarkerSize',10);
    
end

% Step 6.2: Plotagem da trajetória de equilíbrio média da estrutura por direção

% Step 6.2.1: Vetor que separa os graus de liberdade da estrutura
for i=1:nNos
    graux(i)=2*i-1;
    grauy(i)=2*i;
end

% Step 6.2.2: Plotagem da curva média direção x
figure(nPlotagens+2);
xlabel('deslocamento (m)')
ylabel('Carga (N)')
title('Trajetória de Equilíbrio média estrutura direção X')

for i=1:nPassos

    hold on
    plotDirecaoX=norm(historicoUnod(:,i));
    plotDirecaoY=norm(historicoFint(:,i));
    plot(plotDirecaoX,plotDirecaoY,'b--o');
    
    hold on
    plot(0,0,'-mo',...
    'LineWidth',2,...
    'MarkerEdgeColor','k',...
    'MarkerFaceColor',[.49 1 .63],...
    'MarkerSize',10);
    
end

% Step 6.2.3: Plotagem da curva média direção y
figure(nPlotagens+3);
xlabel('deslocamento (m)')
ylabel('Carga (N)')
title('Trajetória de Equilíbrio média estrutura direção Y')

for i=1:nPassos

    hold on
    plotDirecaoX=norm(historicoUnod(:,i));
    plotDirecaoY=norm(historicoFint(:,i));
    plot(plotDirecaoX,plotDirecaoY,'b--o');
    
    hold on
    plot(0,0,'-mo',...
    'LineWidth',2,...
    'MarkerEdgeColor','k',...
    'MarkerFaceColor',[.49 1 .63],...
    'MarkerSize',10);
    
end

% Step 6.3: Plotagem da estrutura deformada

% Step 6.3.1: Introdução da escala desejada pelo usuário
escala=input('Digite a escala a ser utilizada(100):');
if (isempty(escala)==1)
   escala=100;
end

fprintf('%s\n','Desenhando a estrutura deformada...');
figure(nPlotagens+4);
axis equal;
axis off;
title({'Estrutura deformada';'      '});

% Step 6.3.2: Desenhando os elementos na posição indeformada
for el=1:nElem
    
    no1=elementos(el,1);
    no2=elementos(el,2);
    Xno1=coordenadas(no1,1);
    Yno1=coordenadas(no1,2);
    Xno2=coordenadas(no2,1);
    Yno2=coordenadas(no2,2);
    line([Xno1 Xno2],[Yno1 Yno2],'LineWidth',1);
    
end

% Step 6.3.3: Numerando os elementos na posição indeformada
for el=1:nElem
    no1=elementos(el,1);
    no2=elementos(el,2);
    Xno1=coordenadas(no1,1);
    Yno1=coordenadas(no1,2);
    Xno2=coordenadas(no2,1);
    Yno2=coordenadas(no2,2);
    texto=text((Xno1+Xno2)/2,(Yno1+Yno2)/2,num2str(el));
    set(texto,'Color','black','FontSize',14)
end

% Step 6.3.4: Desenhando os nós na posição indeformada
for no=1:nNos
    Xno=coordenadas(no,1);
    Yno=coordenadas(no,2);
    hold on
    raio=0.1;
    intervaloDesenho = 0:pi/50:2*pi;
    xIntervalo = raio * cos(intervaloDesenho) + Xno;
    yIntervalo = raio * sin(intervaloDesenho) + Yno;
    plot(xIntervalo, yIntervalo);
    hold off
    texto=text(Xno+0.1,Yno+0.1,num2str(no));
    set(texto,'Color','blue','FontSize',14)
end

% Step 6.3.5: Desenhando os nós na posição deformada
for i=1:nNos
    
   xi =  coordenadas (i,1);
   xd=xi + escala*deslocAtual(2*i-1,1);
   coordenadasAux(i,1) = xd;
   
   yi =  coordenadas (i,2);
   yd =yi + escala*deslocAtual(2*i,1);
   coordenadasAux(i,2) = yd;
   
   
end

% Step 6.3.6: Desenhando os elementos na posição deformada
figure(nPlotagens+4);
hold on
for el=1:nElem
    
    no1=elementos(el,1);
    no2=elementos(el,2);
    Xno1=coordenadasAux(no1,1);
    Yno1=coordenadasAux(no1,2);
    Xno2=coordenadasAux(no2,1);
    Yno2=coordenadasAux(no2,2);
    line([Xno1 Xno2],[Yno1 Yno2],'Color','red','LineStyle','--');
    
end

hold off



end